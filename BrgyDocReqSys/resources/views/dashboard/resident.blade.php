@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold text-dark">Resident Portal</h1>
            <p class="text-muted mb-0">File document requests and track your application status.</p>
        </div>
        <div>
            @if($residentId)
                <button type="button" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#fileRequestModal">
                    <i class="bi bi-plus-circle me-2"></i>File New Request
                </button>
            @else
                <a href="{{ route('resident.profile') }}" class="btn btn-warning px-4 py-2 fw-semibold shadow-sm">
                    <i class="bi bi-person-fill-exclamation me-2"></i>Complete Profile First
                </a>
            @endif
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="bg-primary text-white rounded-3 p-3 d-inline-block mb-3">
                        <i class="bi bi-clipboard-check fs-2"></i>
                    </div>
                    <h3 class="mb-1">{{ $userRequests->count() }}</h3>
                    <p class="text-muted mb-0">Total Requests</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="bg-warning text-dark rounded-3 p-3 d-inline-block mb-3">
                        <i class="bi bi-clock fs-2"></i>
                    </div>
                    <h3 class="mb-1">{{ $userRequests->where('status', 'pending')->count() }}</h3>
                    <p class="text-muted mb-0">Pending</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="bg-success text-white rounded-3 p-3 d-inline-block mb-3">
                        <i class="bi bi-check-circle fs-2"></i>
                    </div>
                    <h3 class="mb-1">{{ $userRequests->where('status', 'released')->count() }}</h3>
                    <p class="text-muted mb-0">Completed</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h5 class="card-title mb-0">My Document Requests</h5>
                </div>
                <div class="card-body">
                    @if($userRequests->isEmpty())
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-clipboard-x fs-1 text-muted"></i>
                            </div>
                            <h5 class="text-muted">No requests found</h5>
                            <p class="text-muted mb-4">You haven't filed any document requests yet.</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#fileRequestModal">
                                File Your First Request
                            </button>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-muted text-uppercase" style="font-size: 0.75rem;">Request ID</th>
                                        <th class="text-muted text-uppercase" style="font-size: 0.75rem;">Document Type & Purpose</th>
                                        <th class="text-muted text-uppercase" style="font-size: 0.75rem;">Status</th>
                                        <th class="text-muted text-uppercase" style="font-size: 0.75rem;">Date Requested</th>
                                        <th class="text-muted text-uppercase" style="font-size: 0.75rem;">Processing Fee</th>
                                        <th class="text-muted text-uppercase" style="font-size: 0.75rem;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($userRequests as $request)
                                        <tr>
                                            <td>#{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}</td>
                                            <td>
                                                <div>
                                                    <strong>{{ $request->documentType->name ?? 'Unknown' }}</strong>
                                                    @if($request->purpose)
                                                        <br><small class="text-muted">{{ Str::limit($request->purpose, 50) }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $statusClasses = [
                                                        'pending' => 'warning',
                                                        'processing' => 'info',
                                                        'ready_for_pickup' => 'primary',
                                                        'released' => 'success',
                                                        'rejected' => 'danger'
                                                    ];
                                                    $statusClass = $statusClasses[$request->status] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $statusClass }}">
                                                    {{ ucwords(str_replace('_', ' ', $request->status)) }}
                                                </span>
                                            </td>
                                            <td>{{ $request->created_at->format('M d, Y') }}</td>
                                            <td>
                                                @if($request->is_paid)
                                                    <span class="text-success">₱{{ number_format($request->payment_amount, 2) }}</span>
                                                @else
                                                    <span class="text-muted">₱{{ number_format($request->documentType->processing_fee ?? 0, 2) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                            onclick="viewRequestDetails({{ $request->id }})">
                                                        <i class="bi bi-eye"></i> View
                                                    </button>
                                                    @if($request->status === 'ready_for_pickup')
                                                        <button type="button" class="btn btn-sm btn-success"
                                                                onclick="markAsReleased({{ $request->id }})">
                                                            <i class="bi bi-check-circle"></i> Mark Released
                                                        </button>
                                                    @endif
                                                </div>
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

    <!-- File Request Modal -->
    <div class="modal fade" id="fileRequestModal" tabindex="-1" aria-labelledby="fileRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fileRequestModalLabel">File New Document Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="fileRequestForm" method="POST" action="{{ route('document-requests.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="document_type_id" class="form-label">Document Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="document_type_id" name="document_type_id" required>
                                    <option value="">Select document type</option>
                                    @foreach($documentTypes as $type)
                                        <option value="{{ $type->id }}" data-fee="{{ $type->processing_fee }}">
                                            {{ $type->name }} - ₱{{ number_format($type->processing_fee, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="purpose" class="form-label">Purpose <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="purpose" name="purpose" rows="3"
                                          placeholder="Please specify the purpose of your request..." required></textarea>
                            </div>
                            <div class="col-12">
                                <label for="additional_notes" class="form-label">Additional Notes</label>
                                <textarea class="form-control" id="additional_notes" name="additional_notes" rows="2"
                                          placeholder="Any additional information..."></textarea>
                            </div>
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <strong>Processing Fee:</strong> <span id="processingFee">₱0.00</span><br>
                                    <small>Please prepare the exact amount when picking up your document.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Request Details Modal -->
    <div class="modal fade" id="requestDetailsModal" tabindex="-1" aria-labelledby="requestDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestDetailsModalLabel">Request Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" id="requestDetailsContent">
                    <div class="p-4 text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Update processing fee when document type changes
        document.getElementById('document_type_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const fee = selectedOption.getAttribute('data-fee') || 0;
            document.getElementById('processingFee').textContent = '₱' + parseFloat(fee).toFixed(2);
        });

        // View request details via AJAX
        function viewRequestDetails(requestId) {
            const modal = new bootstrap.Modal(document.getElementById('requestDetailsModal'));
            modal.show();
            
            const contentDiv = document.getElementById('requestDetailsContent');
            contentDiv.innerHTML = `
                <div class="p-5 text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Fetching details...</p>
                </div>
            `;
            
            fetch(`/document-requests/${requestId}`)
                .then(response => response.json())
                .then(data => {
                    const req = data.request;
                    const docType = req.document_type ? req.document_type.name : 'Unknown';
                    const resident = req.resident ? req.resident.full_name : 'Unknown';
                    const dateReq = new Date(req.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                    
                    let statusClass = 'secondary';
                    if(req.status === 'pending') statusClass = 'warning';
                    if(req.status === 'processing') statusClass = 'info';
                    if(req.status === 'ready_for_pickup') statusClass = 'primary';
                    if(req.status === 'released') statusClass = 'success';
                    if(req.status === 'rejected') statusClass = 'danger';
                    const statusText = req.status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());

                    let logsHtml = '';
                    if(req.status_logs && req.status_logs.length > 0) {
                        req.status_logs.forEach(log => {
                            const logDate = new Date(log.created_at).toLocaleString();
                            const sText = log.status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
                            logsHtml += `
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <strong>${sText}</strong>
                                        <small class="text-muted">${logDate}</small>
                                    </div>
                                    <div class="text-muted small">${log.remarks || ''}</div>
                                </li>
                            `;
                        });
                    } else {
                        logsHtml = '<li class="list-group-item text-muted">No status logs available.</li>';
                    }

                    const paymentStatus = req.is_paid 
                        ? `<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Paid (₱${parseFloat(req.payment_amount).toFixed(2)})</span>`
                        : `<span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Unpaid</span>`;
                    
                    contentDiv.innerHTML = `
                        <div class="p-4 border-bottom bg-light">
                            <div class="row align-items-center">
                                <div class="col-sm-8">
                                    <h6 class="text-uppercase text-muted mb-1" style="font-size: 0.75rem;">Document Type</h6>
                                    <h5 class="mb-0 fw-bold">${docType}</h5>
                                </div>
                                <div class="col-sm-4 text-sm-end mt-3 mt-sm-0">
                                    <span class="badge bg-${statusClass} px-3 py-2 fs-6 rounded-pill">${statusText}</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="row g-4 mb-4">
                                <div class="col-sm-6">
                                    <p class="text-muted mb-1 small text-uppercase fw-bold">Request ID</p>
                                    <p class="mb-0 fw-semibold">#${String(req.id).padStart(4, '0')}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="text-muted mb-1 small text-uppercase fw-bold">Date Requested</p>
                                    <p class="mb-0 fw-semibold">${dateReq}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="text-muted mb-1 small text-uppercase fw-bold">Payment Status</p>
                                    <p class="mb-0">${paymentStatus}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="text-muted mb-1 small text-uppercase fw-bold">Resident</p>
                                    <p class="mb-0 fw-semibold">${resident}</p>
                                </div>
                                <div class="col-12">
                                    <p class="text-muted mb-1 small text-uppercase fw-bold">Purpose</p>
                                    <p class="mb-0 p-3 bg-light rounded">${req.purpose || 'N/A'}</p>
                                </div>
                            </div>
                            
                            <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-2"></i>Status History</h6>
                            <ul class="list-group list-group-flush border rounded shadow-sm">
                                ${logsHtml}
                            </ul>
                        </div>
                    `;
                })
                .catch(error => {
                    contentDiv.innerHTML = `
                        <div class="p-5 text-center text-danger">
                            <i class="bi bi-exclamation-triangle fs-1"></i>
                            <p class="mt-3">Failed to load request details.</p>
                        </div>
                    `;
                });
        }

        // Mark as released (for pickup confirmation)
        function markAsReleased(requestId) {
            if (confirm('Confirm that you have received this document?')) {
                // This would make an AJAX call to update the status
                alert('Document marked as released. Thank you!');
                location.reload();
            }
        }

        // Form submission handling
        document.getElementById('fileRequestForm').addEventListener('submit', function(e) {
            // Add any client-side validation here if needed
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';
        });
    </script>
@endsection