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
                    <th>Name</th>
                    <th>Fee</th>
                    <th>Required Information</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documentTypes as $documentType)
                    <tr>
                        <td>{{ $documentType->name }}</td>
                        <td>₱ {{ number_format($documentType->processing_fee, 2) }}</td>
                        <td><span class="text-muted small">{{ $documentType->required_information ?: 'Not specified' }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('document-types.edit', $documentType) }}" class="btn btn-sm btn-outline-primary me-1">Edit</a>
                            <form action="{{ route('document-types.destroy', $documentType) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this document type?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No document types configured yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end">
        {{ $documentTypes->links() }}
    </div>
@endsection
