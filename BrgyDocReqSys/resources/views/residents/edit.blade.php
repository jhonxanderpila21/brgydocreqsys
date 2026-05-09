@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Edit Resident</h1>
            <p class="text-muted mb-0">Update resident information and household assignment.</p>
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
            <form action="{{ route('residents.update', $resident) }}" method="POST">
                @csrf
                @method('PUT')
                @include('residents.form')

                <div class="d-flex flex-column flex-sm-row gap-2 justify-content-end mt-4">
                    <a href="{{ route('residents.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Resident</button>
                </div>
            </form>
        </div>
    </div>
@endsection
