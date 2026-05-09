<div class="row g-3">
    <div class="col-12">
        <label for="name" class="form-label">Document Type</label>
        <input id="name" name="name" value="{{ old('name', $documentType->name ?? '') }}" class="form-control" required>
    </div>

    <div class="col-12 col-md-6">
        <label for="processing_fee" class="form-label">Processing Fee</label>
        <div class="input-group">
            <span class="input-group-text">₱</span>
            <input id="processing_fee" name="processing_fee" type="number" step="0.01" min="0" value="{{ old('processing_fee', $documentType->processing_fee ?? '') }}" class="form-control" required>
        </div>
    </div>

    <div class="col-12">
        <label for="required_information" class="form-label">Required Information</label>
        <textarea id="required_information" name="required_information" rows="4" class="form-control">{{ old('required_information', $documentType->required_information ?? '') }}</textarea>
        <div class="form-text">Example: full name, address, ID number, purpose.</div>
    </div>
</div>
