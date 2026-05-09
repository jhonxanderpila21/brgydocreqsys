<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use App\Models\DocumentType;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function dashboard()
    {
        // Request counts by type and status
        $requestsByType = DocumentRequest::select('document_type_id', 'status', DB::raw('count(*) as count'))
            ->with('documentType')
            ->groupBy('document_type_id', 'status')
            ->get()
            ->groupBy('document_type_id');

        $requestsByStatus = DocumentRequest::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Monthly processed documents and fees
        $monthlyStats = DocumentRequest::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as total_requests'),
                DB::raw('sum(case when status = "released" then 1 else 0 end) as processed_documents'),
                DB::raw('sum(payment_amount) as total_fees')
            )
            ->where('is_paid', true)
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        // Resident demographics
        $residentStats = [
            'total_residents' => Resident::count(),
            'age_groups' => [
                '0-17' => Resident::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 0 AND 17')->count(),
                '18-30' => Resident::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 18 AND 30')->count(),
                '31-50' => Resident::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 31 AND 50')->count(),
                '51+' => Resident::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) >= 51')->count(),
            ],
        ];

        $documentTypes = DocumentType::all();

        return view('reports.dashboard', compact(
            'requestsByType',
            'requestsByStatus',
            'monthlyStats',
            'residentStats',
            'documentTypes'
        ));
    }

    public function exportRequests(Request $request)
    {
        $query = DocumentRequest::with(['resident', 'documentType', 'statusLogs']);

        // Apply filters if provided
        if ($request->filled('document_type_id')) {
            $query->where('document_type_id', $request->document_type_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $requests = $query->orderBy('created_at', 'desc')->get();

        $filename = 'document_requests_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($requests) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'Request ID',
                'Resident Name',
                'Document Type',
                'Purpose',
                'Status',
                'Date Filed',
                'Payment Status',
                'Payment Amount',
                'Payment Date',
                'Receipt Number',
                'Last Updated'
            ]);

            // CSV data
            foreach ($requests as $request) {
                fputcsv($file, [
                    $request->id,
                    $request->resident->full_name,
                    $request->documentType->name,
                    $request->purpose,
                    DocumentRequest::statuses()[$request->status] ?? ucfirst($request->status),
                    $request->created_at->format('Y-m-d H:i:s'),
                    $request->is_paid ? 'Paid' : 'Unpaid',
                    $request->payment_amount ? '₱' . number_format($request->payment_amount, 2) : '',
                    $request->payment_date ? $request->payment_date->format('Y-m-d H:i:s') : '',
                    $request->receipt_number ?? '',
                    $request->updated_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportResidents()
    {
        $residents = Resident::with('household')->orderBy('full_name')->get();

        $filename = 'residents_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($residents) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'ID',
                'Full Name',
                'Birth Date',
                'Age',
                'Address',
                'Contact Number',
                'Household Head',
                'Household Address',
                'Date Added'
            ]);

            // CSV data
            foreach ($residents as $resident) {
                fputcsv($file, [
                    $resident->id,
                    $resident->full_name,
                    $resident->date_of_birth->format('Y-m-d'),
                    $resident->age,
                    $resident->address,
                    $resident->contact_number ?? '',
                    $resident->household->head_name ?? '',
                    $resident->household->address ?? '',
                    $resident->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
