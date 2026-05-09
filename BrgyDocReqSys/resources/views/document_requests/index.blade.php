@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Document Requests</h1>
            <p class="text-muted mb-0">Track requests and update status from pending to release.</p>
        </div>
        <a href="{{ route('document-requests.create') }}" class="btn btn-primary">New Request</a>
    </div>

    <form method="GET" class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label class="form-label">Resident</label>
                    <select name="resident_id" class="form-select">
                        <option value="">All residents</option>
                        @foreach($residents as $resident)
                            <option value="{{ $resident->id }}" {{ request('resident_id') == $resident->id ? 'selected' : '' }}>{{ $resident->full_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-4">
                    <label class="form-label">Document Type</label>
                    <select name="document_type_id" class="form-select">
                        <option value="">All types</option>
                        @foreach($documentTypes as $documentType)
                            <option value="{{ $documentType->id }}" {{ request('document_type_id') == $documentType->id ? 'selected' : '' }}>{{ $documentType->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All statuses</option>
                        @foreach(\App\Models\DocumentRequest::statuses() as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-3 d-flex flex-column flex-sm-row gap-2 justify-content-end">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('document-requests.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>
    </form>

    <div class="table-responsive mb-4">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Resident</th>
                    <th>Document</th>
                    <th>Purpose</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Date Filed</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documentRequests as $request)
                    <tr>
                        <td>{{ $request->resident->full_name }}</td>
                        <td>{{ $request->documentType->name }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($request->purpose, 60) }}</td>
                        <td>{{ \App\Models\DocumentRequest::statuses()[$request->status] ?? ucfirst($request->status) }}</td>
                        <td>
                            @if($request->is_paid)
                                <span class="badge bg-success">Paid</span>
                                <small class="text-muted d-block">₱{{ number_format($request->payment_amount, 2) }}</small>
                            @else
                                <span class="badge bg-warning">Unpaid</span>
                            @endif
                        </td>
                        <td>{{ $request->created_at->format('M d, Y H:i') }}</td>
                        <td class="text-end">
                            <a href="{{ route('document-requests.edit', $request) }}" class="btn btn-sm btn-outline-primary">Update</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No document requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end">
        {{ $documentRequests->withQueryString()->links() }}
    </div>
@endsection
