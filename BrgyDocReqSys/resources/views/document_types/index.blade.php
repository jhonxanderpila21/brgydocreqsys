@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Document Types</h1>
            <p class="text-muted mb-0">Configure the available barangay document types and their requirements.</p>
        </div>

        <a href="{{ route('document-types.create') }}" class="btn btn-primary">Add Document Type</a>
    </div>

    <div class="table-responsive mb-4">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Document Type</th>
                    <th>Processing Fee</th>
                    <th>Usage Count</th>
                    <th>Required Information</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documentTypes as $documentType)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $documentType->name }}</div>
                            <small class="text-muted">Created {{ $documentType->created_at->diffForHumans() }}</small>
                        </td>
                        <td>
                            <span class="badge bg-success fs-6">₱ {{ number_format($documentType->processing_fee, 2) }}</span>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $documentType->requests_count ?? 0 }}</span>
                            <small class="text-muted d-block">requests</small>
                        </td>
                        <td>
                            @if($documentType->required_information)
                                <div class="text-truncate" style="max-width: 300px;" title="{{ $documentType->required_information }}">
                                    {{ Str::limit($documentType->required_information, 60) }}
                                </div>
                                <small class="text-muted">Click to expand</small>
                            @else
                                <span class="text-muted">Not specified</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group" role="group">
                                <a href="{{ route('document-types.edit', $documentType) }}" class="btn btn-sm btn-outline-primary" title="Edit Document Type">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('document-requests.create', ['document_type_id' => $documentType->id]) }}" class="btn btn-sm btn-outline-success" title="Create Request">
                                    <i class="bi bi-plus-circle"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-info" title="View Details" onclick="showDocumentTypeDetails({{ $documentType->id }})">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @if($documentType->requests_count == 0)
                                    <form action="{{ route('document-types.destroy', $documentType) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Document Type" onclick="return confirm('Delete this document type? This action cannot be undone.')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bi bi-file-earmark-text fs-1 mb-3"></i>
                            <h5>No document types configured</h5>
                            <p class="mb-3">Start by creating your first document type to enable residents to submit requests.</p>
                            <a href="{{ route('document-types.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Create First Document Type
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end">
        {{ $documentTypes->links() }}
    </div>
@endsection

<!-- Document Type Details Modal -->
<div class="modal fade" id="documentTypeDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-file-earmark-text me-2"></i>Document Type Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="documentTypeDetailsContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editDocumentTypeBtn">Edit Document Type</button>
            </div>
        </div>
    </div>
</div>

<script>
function showDocumentTypeDetails(documentTypeId) {
    // Find the document type data from the table
    const documentTypes = @json($documentTypes);
    const documentType = documentTypes.find(dt => dt.id === documentTypeId);

    if (documentType) {
        const content = `
            <div class="row g-3">
                <div class="col-12">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h4 class="card-title mb-3">${documentType.name}</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Processing Fee:</strong><br>
                                    <span class="badge bg-success fs-6">₱${parseFloat(documentType.processing_fee).toFixed(2)}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Usage Count:</strong><br>
                                    <span class="badge bg-primary">${documentType.requests_count || 0} requests</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <h6>Required Information:</h6>
                    ${documentType.required_information ?
                        `<div class="alert alert-info">
                            <pre class="mb-0" style="white-space: pre-wrap; font-family: inherit;">${documentType.required_information}</pre>
                        </div>` :
                        '<div class="text-muted">No specific requirements configured</div>'
                    }
                </div>
                <div class="col-12">
                    <div class="row text-center">
                        <div class="col-6">
                            <small class="text-muted">Created</small><br>
                            <strong>${new Date(documentType.created_at).toLocaleDateString()}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Last Updated</small><br>
                            <strong>${new Date(documentType.updated_at).toLocaleDateString()}</strong>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('documentTypeDetailsContent').innerHTML = content;
        document.getElementById('editDocumentTypeBtn').onclick = () => {
            window.location.href = '/document-types/' + documentTypeId + '/edit';
        };

        const modal = new bootstrap.Modal(document.getElementById('documentTypeDetailsModal'));
        modal.show();
    }
}
</script>
