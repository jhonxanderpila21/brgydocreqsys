@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h2 class="h4 mb-1">Barangay Document Request System</h2>
                            <p class="text-muted mb-0">Official Receipt</p>
                        </div>

                        <div class="row mb-4">
                            <div class="col-6">
                                <h6 class="text-muted mb-2">Receipt Details</h6>
                                <dl class="row mb-0 small">
                                    <dt class="col-5 fw-normal">Receipt No:</dt>
                                    <dd class="col-7 fw-semibold">{{ $documentRequest->receipt_number }}</dd>

                                    <dt class="col-5 fw-normal">Date:</dt>
                                    <dd class="col-7">{{ $documentRequest->payment_date->format('M d, Y') }}</dd>

                                    <dt class="col-5 fw-normal">Time:</dt>
                                    <dd class="col-7">{{ $documentRequest->payment_date->format('H:i') }}</dd>
                                </dl>
                            </div>
                            <div class="col-6 text-end">
                                <h6 class="text-muted mb-2">Request Details</h6>
                                <dl class="row mb-0 small">
                                    <dt class="col-5 fw-normal">Request ID:</dt>
                                    <dd class="col-7 fw-semibold">#{{ $documentRequest->id }}</dd>

                                    <dt class="col-5 fw-normal">Filed:</dt>
                                    <dd class="col-7">{{ $documentRequest->created_at->format('M d, Y') }}</dd>
                                </dl>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Resident Information</h6>
                            <div class="border rounded p-3 bg-light">
                                <div class="row">
                                    <div class="col-6">
                                        <strong>{{ $documentRequest->resident->full_name }}</strong><br>
                                        <small class="text-muted">{{ $documentRequest->resident->address }}</small>
                                    </div>
                                    <div class="col-6 text-end">
                                        <small class="text-muted">Contact</small><br>
                                        {{ $documentRequest->resident->contact_number ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Document Requested</h6>
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <thead>
                                        <tr class="border-bottom">
                                            <th class="ps-0">Document Type</th>
                                            <th>Purpose</th>
                                            <th class="text-end pe-0">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="ps-0 fw-semibold">{{ $documentRequest->documentType->name }}</td>
                                            <td class="text-muted">{{ $documentRequest->purpose }}</td>
                                            <td class="text-end pe-0 fw-semibold">₱{{ number_format($documentRequest->payment_amount, 2) }}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="border-top border-2">
                                            <td colspan="2" class="ps-0 fw-bold">Total Amount Paid</td>
                                            <td class="text-end pe-0 fw-bold fs-5">₱{{ number_format($documentRequest->payment_amount, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="border-top pt-3">
                                    <small class="text-muted">Received by:</small><br>
                                    <strong>Barangay Staff</strong>
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                <div class="border-top pt-3">
                                    <small class="text-muted">Payment Method:</small><br>
                                    <strong>Cash</strong>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <small class="text-muted">
                                This is a system-generated receipt. Keep this receipt for your records.<br>
                                For inquiries, contact the barangay office.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button onclick="window.print()" class="btn btn-primary me-2">
                        <i class="bi bi-printer me-1"></i>Print Receipt
                    </button>
                    <a href="{{ route('document-requests.edit', $documentRequest) }}" class="btn btn-outline-secondary">
                        Back to Request
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .btn, .mt-4 { display: none !important; }
            body { background: white !important; }
            .card { border: 1px solid #dee2e6 !important; box-shadow: none !important; }
        }
    </style>
@endsection