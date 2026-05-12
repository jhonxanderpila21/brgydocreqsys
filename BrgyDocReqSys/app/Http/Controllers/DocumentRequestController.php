<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use App\Models\DocumentType;
use App\Models\Resident;
use Illuminate\Http\Request;

class DocumentRequestController extends Controller
{
    public function index(Request $request)
    {
        $residents = Resident::orderBy('full_name')->get();
        $documentTypes = DocumentType::orderBy('name')->get();

        $documentRequests = DocumentRequest::with(['resident', 'documentType'])
            ->when($request->resident_id, fn($query, $residentId) => $query->where('resident_id', $residentId))
            ->when($request->document_type_id, fn($query, $documentTypeId) => $query->where('document_type_id', $documentTypeId))
            ->when($request->status, fn($query, $status) => $query->where('status', $status))
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('document_requests.index', compact('documentRequests', 'residents', 'documentTypes'));
    }

    public function create()
    {
        $residents = Resident::orderBy('full_name')->get();
        $documentTypes = DocumentType::orderBy('name')->get();

        return view('document_requests.create', compact('residents', 'documentTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'sometimes|exists:residents,id',
            'document_type_id' => 'required|exists:document_types,id',
            'purpose' => 'required|string|max:1000',
            'additional_notes' => 'nullable|string|max:1000',
        ]);

        // If resident_id not provided, assume it's the authenticated user (resident)
        if (!isset($validated['resident_id'])) {
            $validated['resident_id'] = auth()->id();
        }

        $documentRequest = DocumentRequest::create(array_merge($validated, ['status' => DocumentRequest::STATUS_PENDING]));

        $documentRequest->statusLogs()->create([
            'status' => DocumentRequest::STATUS_PENDING,
            'remarks' => 'Request submitted',
        ]);

        if (auth()->user()->isResident()) {
            return redirect()->route('dashboard.index')->with('success', 'Document request submitted successfully.');
        }

        return redirect()->route('document-requests.index')->with('success', 'Document request submitted successfully.');
    }

    public function edit(DocumentRequest $documentRequest)
    {
        $statuses = DocumentRequest::statuses();
        $documentRequest->load(['resident', 'documentType', 'statusLogs']);

        return view('document_requests.edit', compact('documentRequest', 'statuses'));
    }

    public function update(Request $request, DocumentRequest $documentRequest)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(DocumentRequest::statuses()))],
            'remarks' => 'nullable|string|max:1000',
        ]);

        $documentRequest->update(['status' => $validated['status']]);

        $documentRequest->statusLogs()->create([
            'status' => $validated['status'],
            'remarks' => $validated['remarks'] ?? 'Status updated',
        ]);

        return redirect()->route('document-requests.edit', $documentRequest)
            ->with('success', 'Request status updated successfully.');
    }

    public function pay(Request $request, DocumentRequest $documentRequest)
    {
        $validated = $request->validate([
            'payment_amount' => 'required|numeric|min:0',
        ]);

        if ($documentRequest->is_paid) {
            return redirect()->back()->with('error', 'This request has already been paid.');
        }

        $receiptNumber = 'RCP-' . strtoupper(uniqid());

        $documentRequest->update([
            'payment_amount' => $validated['payment_amount'],
            'payment_date' => now(),
            'receipt_number' => $receiptNumber,
            'is_paid' => true,
        ]);

        return redirect()->route('document-requests.receipt', $documentRequest)
            ->with('success', 'Payment recorded successfully.');
    }

    public function receipt(DocumentRequest $documentRequest)
    {
        if (!$documentRequest->is_paid) {
            return redirect()->route('document-requests.edit', $documentRequest)
                ->with('error', 'Payment not yet recorded for this request.');
        }

        $documentRequest->load(['resident', 'documentType']);

        return view('document_requests.receipt', compact('documentRequest'));
    }

    public function destroy(DocumentRequest $documentRequest)
    {
        $documentRequest->delete();

        return redirect()->route('document-requests.index')
            ->with('success', 'Document request deleted successfully.');
    }
}
