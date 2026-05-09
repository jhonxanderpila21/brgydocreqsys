@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Residents</h1>
            <p class="text-muted mb-0">Manage resident records, search by name, purok/zone, or household unit.</p>
        </div>
        <a href="{{ route('residents.create') }}" class="btn btn-primary">Add Resident</a>
    </div>

    <form method="GET" class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label class="form-label">Search</label>
                    <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Name, occupation, contact">
                </div>

                <div class="col-12 col-md-4">
                    <label class="form-label">Purok / Zone</label>
                    <input type="text" name="purok_zone" value="{{ request('purok_zone') }}" class="form-control" placeholder="Purok / Zone">
                </div>

                <div class="col-12 col-md-4">
                    <label class="form-label">Household</label>
                    <select name="household_id" class="form-select">
                        <option value="">All households</option>
                        @foreach($households as $household)
                            <option value="{{ $household->id }}" {{ request('household_id') == $household->id ? 'selected' : '' }}>{{ $household->name }}{{ $household->purok_zone ? ' — ' . $household->purok_zone : '' }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-3 d-flex flex-column flex-sm-row gap-2 justify-content-end">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('residents.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>
    </form>

    <div class="table-responsive mb-4">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Full Name</th>
                    <th>Household / Purok</th>
                    <th>Civil Status</th>
                    <th>Contact</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($residents as $resident)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $resident->full_name }}</div>
                            <div class="text-muted small">{{ $resident->address }}</div>
                        </td>
                        <td>
                            <div>{{ optional($resident->household)->name ?? 'No household' }}</div>
                            <div class="text-muted small">{{ $resident->purok_zone ?? '—' }}</div>
                        </td>
                        <td>{{ $resident->civil_status }}</td>
                        <td>{{ $resident->contact_number }}</td>
                        <td class="text-end">
                            <a href="{{ route('residents.edit', $resident) }}" class="btn btn-sm btn-outline-primary me-1">Edit</a>
                            <form action="{{ route('residents.destroy', $resident) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this resident?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No resident records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end">
        {{ $residents->withQueryString()->links() }}
    </div>
@endsection
