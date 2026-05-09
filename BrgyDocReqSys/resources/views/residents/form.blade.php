<div class="row g-3">
    <div class="col-12">
        <label class="form-label" for="full_name">Full Name</label>
        <input id="full_name" name="full_name" value="{{ old('full_name', $resident->full_name ?? '') }}" class="form-control" required>
    </div>

    <div class="col-12">
        <label class="form-label" for="household_id">Household</label>
        <select id="household_id" name="household_id" class="form-select">
            <option value="">Select household</option>
            @foreach($households as $household)
                <option value="{{ $household->id }}" {{ old('household_id', $resident->household_id ?? '') == $household->id ? 'selected' : '' }}>
                    {{ $household->name }}{{ $household->purok_zone ? ' — ' . $household->purok_zone : '' }}
                </option>
            @endforeach
        </select>
        <div class="form-text">If no household exists, create one from the <a href="{{ route('households.create') }}">Households</a> section.</div>
    </div>

    <div class="col-12">
        <label class="form-label" for="address">Address</label>
        <textarea id="address" name="address" rows="3" class="form-control" required>{{ old('address', $resident->address ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <label class="form-label" for="purok_zone">Purok / Zone</label>
        <input id="purok_zone" name="purok_zone" value="{{ old('purok_zone', $resident->purok_zone ?? '') }}" class="form-control">
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="date_of_birth">Date of Birth</label>
        <input id="date_of_birth" name="date_of_birth" type="date" value="{{ old('date_of_birth', isset($resident) ? $resident->date_of_birth->format('Y-m-d') : '') }}" class="form-control" required>
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="civil_status">Civil Status</label>
        <input id="civil_status" name="civil_status" value="{{ old('civil_status', $resident->civil_status ?? '') }}" class="form-control" required>
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="occupation">Occupation</label>
        <input id="occupation" name="occupation" value="{{ old('occupation', $resident->occupation ?? '') }}" class="form-control">
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="contact_number">Contact Number</label>
        <input id="contact_number" name="contact_number" value="{{ old('contact_number', $resident->contact_number ?? '') }}" class="form-control" required>
    </div>
</div>
