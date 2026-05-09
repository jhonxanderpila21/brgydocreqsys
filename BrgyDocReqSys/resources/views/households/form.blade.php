<div class="row g-3">
    <div class="col-12">
        <label for="name" class="form-label">Household Name</label>
        <input id="name" name="name" value="{{ old('name', $household->name ?? '') }}" class="form-control" required>
    </div>

    <div class="col-12">
        <label for="purok_zone" class="form-label">Purok / Zone</label>
        <input id="purok_zone" name="purok_zone" value="{{ old('purok_zone', $household->purok_zone ?? '') }}" class="form-control">
    </div>
</div>
