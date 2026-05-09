@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Update Request Status</h1>
            <p class="text-muted mb-0">Manage the current status and log remarks for this request.</p>
        </div>
        <div class="text-end">
            <div class="fw-semibold">Request ID: {{ $documentRequest->id }}</div>
            <div class="text-muted">Filed: {{ $documentRequest->created_at->format('M d, Y H:i') }}</div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-12 col-xl-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Request Details</h5>
                    <dl class="row mb-0">
                        <dt class="col-5">Resident</dt>
                        <dd class="col-7">{{ $documentRequest->resident->full_name }}</dd>

                        <dt class="col-5">Document Type</dt>
                        <dd class="col-7">{{ $documentRequest->documentType->name }}</dd>

                        <dt class="col-5">Purpose</dt>
                        <dd class="col-7">{{ $documentRequest->purpose }}</dd>

                        <dt class="col-5">Current Status</dt>
                        <dd class="col-7">{{ $statuses[$documentRequest->status] ?? ucfirst($documentRequest->status) }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Change Status</h5>
                    <form action="{{ route('document-requests.update', $documentRequest) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select" required>
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ $documentRequest->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="remarks" class="form-label">Staff Remarks</label>
                            <textarea id="remarks" name="remarks" rows="3" class="form-control">{{ old('remarks') }}</textarea>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('document-requests.index') }}" class="btn btn-outline-secondary">Back</a>
                            <button type="submit" class="btn btn-primary">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>

            @if($documentRequest->status === \App\Models\DocumentRequest::STATUS_RELEASED && !$documentRequest->is_paid)
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Record Payment</h5>
                        <p class="text-muted">Record payment for this released document.</p>
                        <form action="{{ route('document-requests.pay', $documentRequest) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="payment_amount" class="form-label">Payment Amount (₱)</label>
                                <input type="number" id="payment_amount" name="payment_amount" class="form-control" step="0.01" min="0" value="{{ $documentRequest->documentType->processing_fee ?? '' }}" required>
                                <div class="form-text">Suggested fee: ₱{{ number_format($documentRequest->documentType->processing_fee ?? 0, 2) }}</div>
                            </div>

                            <div class="d-flex gap-2 justify-content-end">
                                <button type="submit" class="btn btn-success">Record Payment</button>
                            </div>
                        </form>
                    </div>
                </div>
            @elseif($documentRequest->is_paid)
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Payment Details</h5>
                        <dl class="row mb-0">
                            <dt class="col-5">Amount Paid</dt>
                            <dd class="col-7">₱{{ number_format($documentRequest->payment_amount, 2) }}</dd>

                            <dt class="col-5">Payment Date</dt>
                            <dd class="col-7">{{ $documentRequest->payment_date->format('M d, Y H:i') }}</dd>

                            <dt class="col-5">Receipt Number</dt>
                            <dd class="col-7">{{ $documentRequest->receipt_number }}</dd>
                        </dl>
                        <div class="mt-3">
                            <a href="{{ route('document-requests.receipt', $documentRequest) }}" class="btn btn-outline-primary" target="_blank">View Receipt</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-12 col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Status Log</h5>
                    @if($documentRequest->statusLogs->isEmpty())
                        <p class="text-muted mb-0">No status updates have been logged yet.</p>
                    @else
                        <ul class="list-group">
                            @foreach($documentRequest->statusLogs as $log)
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-semibold">{{ $statuses[$log->status] ?? ucfirst($log->status) }}</div>
                                            <div class="small text-muted">{{ $log->created_at->format('M d, Y H:i') }}</div>
                                        </div>
                                    </div>
                                    @if($log->remarks)
                                        <div class="mt-2">{{ $log->remarks }}</div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
