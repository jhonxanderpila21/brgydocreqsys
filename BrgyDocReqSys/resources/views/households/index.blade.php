@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Households</h1>
            <p class="text-muted mb-0">Manage household units and link resident records to them.</p>
        </div>

        <a href="{{ route('households.create') }}" class="btn btn-primary">Add Household</a>
    </div>

    <div class="table-responsive mb-4">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Household</th>
                    <th>Purok / Zone</th>
                    <th>Resident Count</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($households as $household)
                    <tr>
                        <td class="fw-semibold">{{ $household->name }}</td>
                        <td>{{ $household->purok_zone ?? '—' }}</td>
                        <td>{{ $household->residents_count }}</td>
                        <td class="text-end">
                            <a href="{{ route('households.edit', $household) }}" class="btn btn-sm btn-outline-primary me-1">Edit</a>
                            <form action="{{ route('households.destroy', $household) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this household? Residents linked to it will remain without a household assignment.')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No household units found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end">
        {{ $households->links() }}
    </div>
@endsection
