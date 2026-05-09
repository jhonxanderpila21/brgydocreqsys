@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Reports & Statistics</h1>
            <p class="text-muted mb-0">Dashboard showing system analytics and data exports.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.export-requests') }}" class="btn btn-outline-primary">
                <i class="bi bi-download me-1"></i>Export Requests
            </a>
            <a href="{{ route('reports.export-residents') }}" class="btn btn-outline-secondary">
                <i class="bi bi-download me-1"></i>Export Residents
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Request Statistics by Type -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Document Requests by Type & Status</h5>
                </div>
                <div class="card-body">
                    @if($requestsByType->isEmpty())
                        <p class="text-muted mb-0">No document requests found.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Document Type</th>
                                        <th>Pending</th>
                                        <th>Processing</th>
                                        <th>Ready for Pickup</th>
                                        <th>Released</th>
                                        <th>Rejected</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requestsByType as $typeId => $statuses)
                                        <tr>
                                            <td class="fw-semibold">{{ $statuses->first()->documentType->name }}</td>
                                            <td>{{ $statuses->where('status', 'pending')->first()->count ?? 0 }}</td>
                                            <td>{{ $statuses->where('status', 'processing')->first()->count ?? 0 }}</td>
                                            <td>{{ $statuses->where('status', 'ready_for_pickup')->first()->count ?? 0 }}</td>
                                            <td>{{ $statuses->where('status', 'released')->first()->count ?? 0 }}</td>
                                            <td>{{ $statuses->where('status', 'rejected')->first()->count ?? 0 }}</td>
                                            <td class="text-end fw-semibold">{{ $statuses->sum('count') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Overall Status Summary -->
    <div class="row mb-4">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Overall Status Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach(\App\Models\DocumentRequest::statuses() as $status => $label)
                            <div class="col-6">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">{{ $label }}</span>
                                    <span class="badge bg-primary fs-6">{{ $requestsByStatus[$status] ?? 0 }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Resident Demographics -->
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Resident Demographics</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="text-center">
                                <div class="h4 mb-1">{{ $residentStats['total_residents'] }}</div>
                                <small class="text-muted">Total Residents</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <h6 class="mb-2">Age Distribution</h6>
                            @foreach($residentStats['age_groups'] as $ageGroup => $count)
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small">{{ $ageGroup }} years</span>
                                    <span class="small fw-semibold">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Statistics -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Monthly Processed Documents & Fees (Last 12 Months)</h5>
                </div>
                <div class="card-body">
                    @if($monthlyStats->isEmpty())
                        <p class="text-muted mb-0">No processed documents with payments found.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Month/Year</th>
                                        <th>Total Requests</th>
                                        <th>Processed Documents</th>
                                        <th>Total Fees Collected</th>
                                        <th>Average Fee</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($monthlyStats as $stat)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::create($stat->year, $stat->month)->format('M Y') }}</td>
                                            <td>{{ $stat->total_requests }}</td>
                                            <td>{{ $stat->processed_documents }}</td>
                                            <td>₱{{ number_format($stat->total_fees ?? 0, 2) }}</td>
                                            <td>
                                                @if($stat->processed_documents > 0)
                                                    ₱{{ number_format(($stat->total_fees ?? 0) / $stat->processed_documents, 2) }}
                                                @else
                                                    ₱0.00
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Export Filters -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Advanced Export Options</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('reports.export-requests') }}" method="GET" class="row g-3">
                        <div class="col-12 col-md-3">
                            <label class="form-label">Document Type</label>
                            <select name="document_type_id" class="form-select">
                                <option value="">All Types</option>
                                @foreach($documentTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                @foreach(\App\Models\DocumentRequest::statuses() as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-2">
                            <label class="form-label">From Date</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>

                        <div class="col-12 col-md-2">
                            <label class="form-label">To Date</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>

                        <div class="col-12 col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-download me-1"></i>Export Filtered
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection