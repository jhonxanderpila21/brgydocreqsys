@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Resident Portal</h1>
            <p class="text-muted mb-0">File document requests and track your application status.</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#fileRequestModal">
                <i class="bi bi-plus-circle me-1"></i>File New Request
            </button>
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
                                        <th>Request ID</th>
                                        <th>Document Type</th>
                                        <th>Status</th>
                                        <th>Submitted</th>
                                        <th>Processing Fee</th>
                                        <th>Actions</th>
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
                <div class="modal-body" id="requestDetailsContent">
                    <!-- Content will be loaded here -->
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

        // View request details
        function viewRequestDetails(requestId) {
            // This would typically make an AJAX call to get request details
            // For now, we'll show a placeholder
            const content = `
                <div class="text-center">
                    <p>Request details for ID: ${requestId}</p>
                    <p class="text-muted">This feature would show full request information, status history, and requirements.</p>
                </div>
            `;
            document.getElementById('requestDetailsContent').innerHTML = content;
            new bootstrap.Modal(document.getElementById('requestDetailsModal')).show();
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