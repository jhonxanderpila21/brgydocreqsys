<div class="row g-3">
    <div class="col-12">
        <label for="name" class="form-label">Document Type Name <span class="text-danger">*</span></label>
        <input id="name" name="name" value="{{ old('name', $documentType->name ?? '') }}" class="form-control" required placeholder="e.g., Barangay Clearance, Certificate of Residency">
        <div class="form-text">Enter the full name of the document type as it will appear to residents</div>
    </div>

    <div class="col-12 col-md-6">
        <label for="processing_fee" class="form-label">Processing Fee (₱) <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text">₱</span>
            <input id="processing_fee" name="processing_fee" type="number" step="0.01" min="0" max="10000" value="{{ old('processing_fee', $documentType->processing_fee ?? '') }}" class="form-control" required placeholder="0.00">
        </div>
        <div class="form-text">Set the fee for processing this document type</div>
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label">Fee Category</label>
        <select class="form-select" disabled>
            <option>Standard Fee - Configurable per document type</option>
        </select>
        <div class="form-text">Each document type can have its own processing fee</div>
    </div>

    <div class="col-12">
        <label for="required_information" class="form-label">Required Information</label>
        <textarea id="required_information" name="required_information" rows="5" class="form-control" placeholder="List all information and documents required for this document type...">{{ old('required_information', $documentType->required_information ?? '') }}</textarea>
        <div class="form-text">
            Specify what residents need to provide. Examples:<br>
            • Full name and date of birth<br>
            • Valid ID (e.g., driver's license, passport)<br>
            • Proof of residency (utility bill, lease agreement)<br>
            • Purpose of request<br>
            • Contact information
        </div>
    </div>

    <div class="col-12">
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Configuration Tips:</strong>
            <ul class="mb-0 mt-2">
                <li>Be specific about required documents to avoid incomplete submissions</li>
                <li>Include processing timeframes if applicable</li>
                <li>Mention any special conditions or restrictions</li>
                <li>Keep fees transparent and justifiable</li>
            </ul>
        </div>
    </div>
</div>
