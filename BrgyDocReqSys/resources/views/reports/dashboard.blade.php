@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Dashboard</h1>
            <p class="text-muted mb-0">Overview of system analytics and key statistics.</p>
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

    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="text-uppercase text-muted mb-1">Total Requests</h6>
                            <h2 class="mb-0">{{ number_format($totalRequests) }}</h2>
                        </div>
                        <div class="bg-primary text-white rounded-3 p-3">
                            <i class="bi bi-clipboard-check fs-4"></i>
                        </div>
                    </div>
                    <p class="text-muted mb-0">All submitted document requests across the system.</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="text-uppercase text-muted mb-1">Processed</h6>
                            <h2 class="mb-0">{{ number_format($totalProcessed) }}</h2>
                        </div>
                        <div class="bg-success text-white rounded-3 p-3">
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>
                    </div>
                    <p class="text-muted mb-0">Requests released and completed successfully.</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="text-uppercase text-muted mb-1">Total Revenue</h6>
                            <h2 class="mb-0">₱{{ number_format($totalRevenue, 2) }}</h2>
                        </div>
                        <div class="bg-warning text-dark rounded-3 p-3">
                            <i class="bi bi-currency-dollar fs-4"></i>
                        </div>
                    </div>
                    <p class="text-muted mb-0">Collected fees for paid document requests.</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="text-uppercase text-muted mb-1">Pending Requests</h6>
                            <h2 class="mb-0">{{ number_format($pendingRequests) }}</h2>
                        </div>
                        <div class="bg-danger text-white rounded-3 p-3">
                            <i class="bi bi-hourglass-split fs-4"></i>
                        </div>
                    </div>
                    <p class="text-muted mb-0">Requests that are still in progress or awaiting approval.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="text-uppercase text-muted mb-1">Residents</h6>
                            <h2 class="mb-0">{{ number_format($residentStats['total_residents']) }}</h2>
                        </div>
                        <div class="bg-info text-white rounded-3 p-3">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                    </div>
                    <p class="text-muted mb-0">Registered residents in the barangay.</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="text-uppercase text-muted mb-1">Households</h6>
                            <h2 class="mb-0">{{ number_format($totalHouseholds) }}</h2>
                        </div>
                        <div class="bg-secondary text-white rounded-3 p-3">
                            <i class="bi bi-house-door fs-4"></i>
                        </div>
                    </div>
                    <p class="text-muted mb-0">Household records currently stored.</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="text-uppercase text-muted mb-1">Document Types</h6>
                            <h2 class="mb-0">{{ number_format($documentTypes->count()) }}</h2>
                        </div>
                        <div class="bg-dark text-white rounded-3 p-3">
                            <i class="bi bi-journal-text fs-4"></i>
                        </div>
                    </div>
                    <p class="text-muted mb-0">Different document kinds available for request.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Monthly Requests</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyRequestsChart" height="220"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Request Status Breakdown</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusBreakdownChart" height="220"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h5 class="card-title mb-0">Document Requests by Type</h5>
                </div>
                <div class="card-body">
                    @if($requestsByType->isEmpty())
                        <p class="text-muted mb-0">No document requests found.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const monthlyLabels = @json($monthlyStats->map(fn($stat) => \Carbon\Carbon::create($stat->year, $stat->month)->format('M')));
        const monthlyData = @json($monthlyStats->pluck('total_requests'));
        const statusLabels = @json($requestsByStatus->keys()->map(fn($status) => ucwords(str_replace('_', ' ', $status))));
        const statusData = @json($requestsByStatus->values());

        const monthlyCtx = document.getElementById('monthlyRequestsChart');
        if (monthlyCtx) {
            new Chart(monthlyCtx, {
                type: 'bar',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: 'Requests',
                        data: monthlyData,
                        backgroundColor: 'rgba(13, 110, 253, 0.8)',
                        borderRadius: 8,
                        barThickness: 26,
                    }]
                },
                options: {
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(15, 23, 32, 0.08)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        const statusCtx = document.getElementById('statusBreakdownChart');
        if (statusCtx) {
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusData,
                        backgroundColor: [
                            '#0d6efd',
                            '#198754',
                            '#ffc107',
                            '#dc3545',
                            '#6c757d'
                        ],
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 12, padding: 16 }
                        }
                    }
                }
            });
        }
    </script>
@endsection