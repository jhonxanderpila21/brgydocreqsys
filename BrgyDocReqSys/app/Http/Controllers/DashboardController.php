<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use App\Models\DocumentType;
use App\Models\Resident;
use App\Models\Household;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Key Statistics
        $stats = [
            'total_residents' => Resident::count(),
            'total_households' => Household::count(),
            'total_requests' => DocumentRequest::count(),
            'total_revenue' => DocumentRequest::where('is_paid', true)->sum('payment_amount'),
            'pending_requests' => DocumentRequest::where('status', 'pending')->count(),
            'processed_requests' => DocumentRequest::where('status', 'released')->count(),
        ];

        // Request Status Breakdown
        $requestsByStatus = DocumentRequest::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        // Top Document Types by Request Count
        $topDocumentTypes = DocumentRequest::select('document_type_id', DB::raw('count(*) as count'))
            ->with('documentType')
            ->groupBy('document_type_id')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Recent Document Requests (Last 10)
        $recentRequests = DocumentRequest::with(['resident', 'documentType'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Monthly Trend (Last 6 months)
        $monthlyTrend = DocumentRequest::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as total'),
                DB::raw('sum(case when status = "released" then 1 else 0 end) as completed')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get()
            ->reverse()
            ->values();

        // Revenue by Month (Last 6 months)
        $revenueByMonth = DocumentRequest::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('sum(payment_amount) as revenue')
            )
            ->where('is_paid', true)
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get()
            ->reverse()
            ->values();

        // Age Demographics
        $ageDemographics = [
            '0-17' => Resident::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 0 AND 17')->count(),
            '18-30' => Resident::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 18 AND 30')->count(),
            '31-50' => Resident::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 31 AND 50')->count(),
            '51+' => Resident::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) >= 51')->count(),
        ];

        $documentTypes = DocumentType::all();
        $user = auth()->user();

        if ($user->isAdmin()) {
            return view('dashboard.admin', compact(
                'stats',
                'requestsByStatus',
                'topDocumentTypes',
                'recentRequests',
                'monthlyTrend',
                'revenueByMonth',
                'ageDemographics',
                'documentTypes'
            ));
        } elseif ($user->isStaff()) {
            // Get households for the search dropdown
            $households = Household::orderBy('name')->get();

            // Get recent residents (last 5 added)
            $recentResidents = Resident::with('household')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Get households with residents
            $householdsWithResidents = Household::has('residents')->get();

            // Get document types with request counts
            $documentTypes = DocumentType::withCount('requests')
                ->orderBy('name')
                ->get();

            // Calculate total fees
            $totalFees = $documentTypes->sum('processing_fee');

            return view('dashboard.staff', compact(
                'stats',
                'requestsByStatus',
                'topDocumentTypes',
                'recentRequests',
                'monthlyTrend',
                'revenueByMonth',
                'ageDemographics',
                'documentTypes',
                'households',
                'recentResidents',
                'householdsWithResidents',
                'totalFees'
            ));
        } else {
            // Resident dashboard
            $userRequests = DocumentRequest::where('resident_id', $user->id)
                ->with(['documentType'])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('dashboard.resident', compact(
                'userRequests',
                'documentTypes'
            ));
        }
    }
}
