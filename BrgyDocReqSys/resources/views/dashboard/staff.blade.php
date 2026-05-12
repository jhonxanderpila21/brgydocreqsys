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
                <i class="bi bi-people me-1"></i>View Residents
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
    </script>
@endsection