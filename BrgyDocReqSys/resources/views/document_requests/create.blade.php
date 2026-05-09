@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">New Document Request</h1>
            <p class="text-muted mb-0">Submit a document request for an existing resident.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('document-requests.store') }}" method="POST">
                @csrf
                @include('document_requests.form')

                <div class="d-flex flex-column flex-sm-row gap-2 justify-content-end mt-4">
                    <a href="{{ route('document-requests.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
@endsection
