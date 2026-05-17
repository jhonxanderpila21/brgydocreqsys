@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-bell-fill text-primary me-2"></i>My Notifications</h5>
                </div>
                
                <div class="card-body p-0">
                    @if(isset($notifications) && $notifications->isNotEmpty())
                        <div class="list-group list-group-flush">
                            @foreach($notifications as $log)
                                @php
                                    $statusClass = 'secondary';
                                    if($log->status === 'pending') $statusClass = 'warning';
                                    if($log->status === 'processing') $statusClass = 'info';
                                    if($log->status === 'ready_for_pickup') $statusClass = 'primary';
                                    if($log->status === 'released') $statusClass = 'success';
                                    if($log->status === 'rejected') $statusClass = 'danger';
                                    
                                    $documentName = $log->documentRequest->documentType->name ?? 'Document';
                                @endphp
                                <div class="list-group-item list-group-item-action p-4">
                                    <div class="d-flex w-100 justify-content-between mb-2">
                                        <h6 class="mb-1 fw-bold text-dark">
                                            Update on {{ $documentName }} 
                                            <span class="text-muted fw-normal fs-6 ms-1">(Request #{{ str_pad($log->document_request_id, 4, '0', STR_PAD_LEFT) }})</span>
                                        </h6>
                                        <small class="text-muted d-flex align-items-center">
                                            <i class="bi bi-clock me-1"></i> {{ $log->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <p class="mb-2">
                                        Status changed to <span class="badge bg-{{ $statusClass }} ms-1">{{ ucwords(str_replace('_', ' ', $log->status)) }}</span>
                                    </p>
                                    
                                    @if($log->remarks)
                                    <div class="bg-light p-3 rounded text-muted small mt-2 border-start border-3 border-{{ $statusClass }}">
                                        <strong>Staff Remarks:</strong> {{ $log->remarks }}
                                    </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div class="p-3 bg-light border-top">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="p-5 text-center text-muted">
                            <i class="bi bi-bell-slash text-secondary opacity-50 mb-3" style="font-size: 4rem;"></i>
                            <h5 class="fw-bold">No notifications yet</h5>
                            <p>When there are updates to your document requests, they will appear here.</p>
                            <a href="{{ route('dashboard.index') }}" class="btn btn-primary mt-2">Go to Dashboard</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
