<div class="row g-3">
    <div class="col-12 col-md-6">
        <label for="resident_id" class="form-label">Resident</label>
        <select id="resident_id" name="resident_id" class="form-select" required>
            <option value="">Select resident</option>
            @foreach($residents as $resident)
                <option value="{{ $resident->id }}" {{ old('resident_id', $documentRequest->resident_id ?? '') == $resident->id ? 'selected' : '' }}>
                    {{ $resident->full_name }} — {{ $resident->address }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-12 col-md-6">
        <label for="document_type_id" class="form-label">Document Type</label>
        <select id="document_type_id" name="document_type_id" class="form-select" required>
            <option value="">Select document type</option>
            @foreach($documentTypes as $documentType)
                <option value="{{ $documentType->id }}" {{ old('document_type_id', $documentRequest->document_type_id ?? '') == $documentType->id ? 'selected' : '' }}>
                    {{ $documentType->name }} — ₱ {{ number_format($documentType->processing_fee, 2) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-12">
        <label for="purpose" class="form-label">Purpose</label>
        <textarea id="purpose" name="purpose" rows="4" class="form-control" required>{{ old('purpose', $documentRequest->purpose ?? '') }}</textarea>
        <div class="form-text">Describe the purpose of the document request.</div>
    </div>
</div>
