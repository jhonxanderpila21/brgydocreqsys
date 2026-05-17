@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Staff Dashboard</h1>
            <p class="text-muted mb-0">Overview for processing document requests and managing resident data.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('document-requests.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-clipboard-check me-1"></i>Process Requests
            </a>
            <a href="{{ route('residents.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-people me-1"></i>Manage Residents
            </a>
            <a href="{{ route('residents.create') }}" class="btn btn-success">
                <i class="bi bi-person-plus me-1"></i>Add Resident
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="text-uppercase text-muted mb-1">Total Residents</h6>
                            <h2 class="mb-0">{{ number_format($stats['total_residents']) }}</h2>
                        </div>
                        <div class="bg-info text-white rounded-3 p-3">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                    </div>
                    <p class="text-muted mb-0">Registered residents in the barangay.</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="text-uppercase text-muted mb-1">Total Households</h6>
                            <h2 class="mb-0">{{ number_format($stats['total_households']) }}</h2>
                        </div>
                        <div class="bg-secondary text-white rounded-3 p-3">
                            <i class="bi bi-house-door fs-4"></i>
                        </div>
                    </div>
                    <p class="text-muted mb-0">Household records currently stored.</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="text-uppercase text-muted mb-1">Pending Requests</h6>
                            <h2 class="mb-0">{{ number_format($stats['pending_requests']) }}</h2>
                        </div>
                        <div class="bg-warning text-dark rounded-3 p-3">
                            <i class="bi bi-clock fs-4"></i>
                        </div>
                    </div>
                    <p class="text-muted mb-0">Requests awaiting processing.</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="text-uppercase text-muted mb-1">Processed Today</h6>
                            <h2 class="mb-0">{{ number_format($stats['processed_requests']) }}</h2>
                        </div>
                        <div class="bg-success text-white rounded-3 p-3">
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>
                    </div>
                    <p class="text-muted mb-0">Requests completed and released.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Resident Management Section -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">
                                <i class="bi bi-people me-2"></i>🏠 Resident Management
                            </h5>
                            <p class="text-muted mb-0 mt-1">Manage resident records, households, and community data</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('residents.create') }}" class="btn btn-success btn-sm">
                                <i class="bi bi-plus-circle me-1"></i>Add New Resident
                            </a>
                            <a href="{{ route('households.create') }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-house-add me-1"></i>Create Household
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Quick Stats -->
                        <div class="col-12 col-md-3">
                            <div class="text-center">
                                <div class="bg-info bg-opacity-10 rounded-3 p-3 mb-3">
                                    <i class="bi bi-people-fill fs-2 text-info"></i>
                                </div>
                                <h4 class="mb-1">{{ number_format($stats['total_residents']) }}</h4>
                                <p class="text-muted mb-0">Total Residents</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="text-center">
                                <div class="bg-secondary bg-opacity-10 rounded-3 p-3 mb-3">
                                    <i class="bi bi-house-fill fs-2 text-secondary"></i>
                                </div>
                                <h4 class="mb-1">{{ number_format($stats['total_households']) }}</h4>
                                <p class="text-muted mb-0">Households</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="text-center">
                                <div class="bg-warning bg-opacity-10 rounded-3 p-3 mb-3">
                                    <i class="bi bi-search fs-2 text-warning"></i>
                                </div>
                                <h4 class="mb-1">{{ $recentResidents->count() }}</h4>
                                <p class="text-muted mb-0">Recent Additions</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="text-center">
                                <div class="bg-success bg-opacity-10 rounded-3 p-3 mb-3">
                                    <i class="bi bi-check-circle-fill fs-2 text-success"></i>
                                </div>
                                <h4 class="mb-1">{{ $householdsWithResidents->count() }}</h4>
                                <p class="text-muted mb-0">Active Households</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row g-3 mt-4">
                        <div class="col-12 col-md-6 col-lg-3">
                            <a href="{{ route('residents.index') }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 py-3">
                                <i class="bi bi-list-ul"></i>
                                <span>View All Residents</span>
                            </a>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <a href="{{ route('households.index') }}" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2 py-3">
                                <i class="bi bi-house"></i>
                                <span>Manage Households</span>
                            </a>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <button type="button" class="btn btn-outline-info w-100 d-flex align-items-center justify-content-center gap-2 py-3" onclick="toggleSearchSection()">
                                <i class="bi bi-search"></i>
                                <span>Quick Search</span>
                            </button>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <a href="{{ route('reports.export-residents') }}" class="btn btn-outline-success w-100 d-flex align-items-center justify-content-center gap-2 py-3">
                                <i class="bi bi-download"></i>
                                <span>Export Data</span>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Search Section (Hidden by default) -->
                    <div id="quickSearchSection" class="mt-4" style="display: none;">
                        <div class="card border">
                            <div class="card-body">
                                <h6 class="card-title mb-3">
                                    <i class="bi bi-search me-2"></i>Quick Resident Search
                                </h6>
                                <form method="GET" action="{{ route('residents.index') }}" class="row g-3">
                                    <div class="col-12 col-md-4">
                                        <input type="text" name="search" class="form-control" placeholder="Name, occupation, contact" value="{{ request('search') }}">
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="text" name="purok_zone" class="form-control" placeholder="Purok / Zone" value="{{ request('purok_zone') }}">
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <select name="household_id" class="form-select">
                                            <option value="">All households</option>
                                            @foreach($households as $household)
                                                <option value="{{ $household->id }}">{{ $household->name }}{{ $household->purok_zone ? ' — ' . $household->purok_zone : '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary me-2">
                                            <i class="bi bi-search me-1"></i>Search Residents
                                        </button>
                                        <a href="{{ route('residents.index') }}" class="btn btn-outline-secondary">View All</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Residents Table -->
                    <div class="mt-4">
                        <h6 class="mb-3">
                            <i class="bi bi-clock-history me-2"></i>Recently Added Residents
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Household</th>
                                        <th>Purok/Zone</th>
                                        <th>Contact</th>
                                        <th>Added</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentResidents as $resident)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $resident->full_name }}</div>
                                                <small class="text-muted">{{ $resident->civil_status }} • {{ $resident->age }} years old</small>
                                            </td>
                                            <td>{{ optional($resident->household)->name ?? 'No household' }}</td>
                                            <td>{{ $resident->purok_zone ?? '—' }}</td>
                                            <td>{{ $resident->contact_number }}</td>
                                            <td>{{ $resident->created_at->diffForHumans() }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('residents.edit', $resident) }}" class="btn btn-sm btn-outline-primary me-1">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="{{ route('residents.index', ['search' => $resident->full_name]) }}" class="btn btn-sm btn-outline-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="bi bi-people fs-2 mb-2"></i>
                                                <div>No recent residents added</div>
                                                <a href="{{ route('residents.create') }}" class="btn btn-sm btn-primary mt-2">Add First Resident</a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($recentResidents->count() > 0)
                            <div class="text-end mt-3">
                                <a href="{{ route('residents.index') }}" class="btn btn-sm btn-outline-primary">
                                    View All Residents <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Document Type Configuration Section -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">
                                <i class="bi bi-file-earmark-text me-2"></i>📑 Document Type Configuration
                            </h5>
                            <p class="text-muted mb-0 mt-1">Manage document types, processing fees, and required information</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('document-types.create') }}" class="btn btn-success btn-sm">
                                <i class="bi bi-plus-circle me-1"></i>Add Document Type
                            </a>
                            <a href="{{ route('document-types.index') }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-gear me-1"></i>Manage All Types
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Quick Stats -->
                        <div class="col-12 col-md-3">
                            <div class="text-center">
                                <div class="bg-primary bg-opacity-10 rounded-3 p-3 mb-3">
                                    <i class="bi bi-file-earmark-text-fill fs-2 text-primary"></i>
                                </div>
                                <h4 class="mb-1">{{ $documentTypes->count() }}</h4>
                                <p class="text-muted mb-0">Document Types</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="text-center">
                                <div class="bg-success bg-opacity-10 rounded-3 p-3 mb-3">
                                    <i class="bi bi-cash-coin fs-2 text-success"></i>
                                </div>
                                <h4 class="mb-1">₱{{ number_format($totalFees, 2) }}</h4>
                                <p class="text-muted mb-0">Total Fees</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="text-center">
                                <div class="bg-warning bg-opacity-10 rounded-3 p-3 mb-3">
                                    <i class="bi bi-clipboard-check fs-2 text-warning"></i>
                                </div>
                                <h4 class="mb-1">{{ $stats['total_requests'] }}</h4>
                                <p class="text-muted mb-0">Total Requests</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="text-center">
                                <div class="bg-info bg-opacity-10 rounded-3 p-3 mb-3">
                                    <i class="bi bi-graph-up fs-2 text-info"></i>
                                </div>
                                <h4 class="mb-1">₱{{ number_format($stats['total_revenue'], 2) }}</h4>
                                <p class="text-muted mb-0">Revenue Generated</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row g-3 mt-4">
                        <div class="col-12 col-md-6 col-lg-3">
                            <a href="{{ route('document-types.index') }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 py-3">
                                <i class="bi bi-list-ul"></i>
                                <span>View All Types</span>
                            </a>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <a href="{{ route('document-types.create') }}" class="btn btn-outline-success w-100 d-flex align-items-center justify-content-center gap-2 py-3">
                                <i class="bi bi-plus-circle"></i>
                                <span>Add New Type</span>
                            </a>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <button type="button" class="btn btn-outline-info w-100 d-flex align-items-center justify-content-center gap-2 py-3" onclick="toggleFeeCalculator()">
                                <i class="bi bi-calculator"></i>
                                <span>Fee Calculator</span>
                            </button>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <a href="{{ route('reports.dashboard') }}" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2 py-3">
                                <i class="bi bi-bar-chart"></i>
                                <span>View Reports</span>
                            </a>
                        </div>
                    </div>

                    <!-- Fee Calculator Section (Hidden by default) -->
                    <div id="feeCalculatorSection" class="mt-4" style="display: none;">
                        <div class="card border">
                            <div class="card-body">
                                <h6 class="card-title mb-3">
                                    <i class="bi bi-calculator me-2"></i>Document Fee Calculator
                                </h6>
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Select Document Type</label>
                                        <select id="feeCalculatorType" class="form-select">
                                            <option value="">Choose document type...</option>
                                            @foreach($documentTypes as $type)
                                                <option value="{{ $type->processing_fee }}" data-info="{{ $type->required_information }}">
                                                    {{ $type->name }} - ₱{{ number_format($type->processing_fee, 2) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Quantity</label>
                                        <input type="number" id="feeCalculatorQty" class="form-control" min="1" value="1" placeholder="Number of copies">
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>Total Fee: <span id="calculatedFee">₱0.00</span></strong>
                                            </div>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetCalculator()">Reset</button>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div id="requiredInfoDisplay" class="alert alert-info" style="display: none;">
                                            <strong>Required Information:</strong><br>
                                            <span id="requiredInfoText"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Document Types Table -->
                    <div class="mt-4">
                        <h6 class="mb-3">
                            <i class="bi bi-file-earmark-text me-2"></i>Available Document Types
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Document Type</th>
                                        <th>Processing Fee</th>
                                        <th>Required Information</th>
                                        <th>Usage</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($documentTypes->take(5) as $documentType)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $documentType->name }}</div>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">₱{{ number_format($documentType->processing_fee, 2) }}</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ Str::limit($documentType->required_information ?: 'Not specified', 50) }}
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $documentType->requests_count ?? 0 }}</span>
                                                <small class="text-muted d-block">requests</small>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('document-types.edit', $documentType) }}" class="btn btn-sm btn-outline-primary me-1">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="{{ route('document-requests.create', ['document_type_id' => $documentType->id]) }}" class="btn btn-sm btn-outline-success">
                                                    <i class="bi bi-plus"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="bi bi-file-earmark-text fs-2 mb-2"></i>
                                                <div>No document types configured</div>
                                                <a href="{{ route('document-types.create') }}" class="btn btn-sm btn-primary mt-2">Create First Document Type</a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($documentTypes->count() > 5)
                            <div class="text-end mt-3">
                                <a href="{{ route('document-types.index') }}" class="btn btn-sm btn-outline-primary">
                                    View All Document Types <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Request Status Breakdown</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusBreakdownChart" height="220"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Monthly Request Trend</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyTrendChart" height="220"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top Document Types</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($topDocumentTypes as $item)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $item->documentType->name }}</span>
                                <span class="badge bg-primary">{{ $item->count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($recentRequests->take(5) as $request)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $request->documentType->name ?? 'Unknown' }}</h6>
                                        <p class="mb-1 text-muted">{{ $request->resident->full_name ?? 'Unknown Resident' }}</p>
                                        <small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                                    </div>
                                    <span class="badge bg-{{ $request->status === 'pending' ? 'warning' : ($request->status === 'released' ? 'success' : 'secondary') }}">
                                        {{ ucwords(str_replace('_', ' ', $request->status)) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Pending Document Requests</h5>
                    <a href="{{ route('document-requests.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Request ID</th>
                                    <th>Resident</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRequests->where('status', 'pending')->take(10) as $request)
                                    <tr>
                                        <td>{{ $request->id }}</td>
                                        <td>{{ $request->resident->full_name ?? 'N/A' }}</td>
                                        <td>{{ $request->documentType->name ?? 'N/A' }}</td>
                                        <td><span class="badge bg-warning">{{ ucwords(str_replace('_', ' ', $request->status)) }}</span></td>
                                        <td>{{ $request->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('document-requests.show', $request) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const statusLabels = @json($requestsByStatus->keys()->map(fn($status) => ucwords(str_replace('_', ' ', $status))));
        const statusData = @json($requestsByStatus->values());

        const monthlyLabels = @json($monthlyTrend->map(fn($item) => \Carbon\Carbon::create($item->year, $item->month)->format('M')));
        const monthlyTotal = @json($monthlyTrend->pluck('total'));

        if (document.getElementById('statusBreakdownChart')) {
            new Chart(document.getElementById('statusBreakdownChart'), {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusData,
                        backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6c757d'],
                    }],
                },
                options: {
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }

        if (document.getElementById('monthlyTrendChart')) {
            new Chart(document.getElementById('monthlyTrendChart'), {
                type: 'bar',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: 'Requests',
                        data: monthlyTotal,
                        backgroundColor: '#0d6efd',
                        borderRadius: 8,
                        barThickness: 24,
                    }],
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(15, 23, 32, 0.08)' } },
                        x: { grid: { display: false } },
                    },
                }
            });
        }

        // Toggle quick search section
        function toggleSearchSection() {
            const section = document.getElementById('quickSearchSection');
            if (section.style.display === 'none' || section.style.display === '') {
                section.style.display = 'block';
                section.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else {
                section.style.display = 'none';
            }
        }

        // Fee Calculator functions
        function toggleFeeCalculator() {
            const section = document.getElementById('feeCalculatorSection');
            if (section.style.display === 'none' || section.style.display === '') {
                section.style.display = 'block';
                section.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else {
                section.style.display = 'none';
            }
        }

        function resetCalculator() {
            document.getElementById('feeCalculatorType').value = '';
            document.getElementById('feeCalculatorQty').value = '1';
            document.getElementById('calculatedFee').textContent = '₱0.00';
            document.getElementById('requiredInfoDisplay').style.display = 'none';
        }

        // Initialize fee calculator
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('feeCalculatorType');
            const qtyInput = document.getElementById('feeCalculatorQty');
            const feeDisplay = document.getElementById('calculatedFee');
            const infoDisplay = document.getElementById('requiredInfoDisplay');
            const infoText = document.getElementById('requiredInfoText');

            function updateCalculator() {
                const selectedOption = typeSelect.options[typeSelect.selectedIndex];
                const fee = parseFloat(typeSelect.value) || 0;
                const qty = parseInt(qtyInput.value) || 1;
                const total = fee * qty;

                feeDisplay.textContent = '₱' + total.toFixed(2);

                if (selectedOption && selectedOption.getAttribute('data-info')) {
                    infoText.textContent = selectedOption.getAttribute('data-info');
                    infoDisplay.style.display = 'block';
                } else {
                    infoDisplay.style.display = 'none';
                }
            }

            typeSelect.addEventListener('change', updateCalculator);
            qtyInput.addEventListener('input', updateCalculator);
        });
    </script>
@endsection