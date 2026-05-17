@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-person-circle me-2 text-primary"></i>My Profile</h5>
                </div>
                
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(!$resident)
                        <div class="alert alert-warning mb-4">
                            <i class="bi bi-info-circle me-1"></i> Please complete your profile information to start filing document requests.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('resident.profile.update') }}">
                        @csrf
                        
                        <div class="row g-3">
                            <div class="col-md-12 mb-2">
                                <label for="full_name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="full_name" name="full_name" value="{{ old('full_name', $resident->full_name ?? $user->name) }}" required>
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-2">
                                <label for="date_of_birth" class="form-label fw-semibold">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', optional($resident?->date_of_birth)->format('Y-m-d')) }}" required>
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-2">
                                <label for="contact_number" class="form-label fw-semibold">Contact Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('contact_number') is-invalid @enderror" id="contact_number" name="contact_number" value="{{ old('contact_number', $resident->contact_number ?? '') }}" required placeholder="e.g. 09123456789">
                                @error('contact_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-2">
                                <label for="civil_status" class="form-label fw-semibold">Civil Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('civil_status') is-invalid @enderror" id="civil_status" name="civil_status" required>
                                    <option value="" disabled {{ !$resident ? 'selected' : '' }}>Select status</option>
                                    <option value="Single" {{ old('civil_status', $resident->civil_status ?? '') === 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ old('civil_status', $resident->civil_status ?? '') === 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Widowed" {{ old('civil_status', $resident->civil_status ?? '') === 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                    <option value="Legally Separated" {{ old('civil_status', $resident->civil_status ?? '') === 'Legally Separated' ? 'selected' : '' }}>Legally Separated</option>
                                </select>
                                @error('civil_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-2">
                                <label for="occupation" class="form-label fw-semibold">Occupation</label>
                                <input type="text" class="form-control @error('occupation') is-invalid @enderror" id="occupation" name="occupation" value="{{ old('occupation', $resident->occupation ?? '') }}">
                                @error('occupation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 mb-2">
                                <label for="address" class="form-label fw-semibold">Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $resident->address ?? '') }}" required placeholder="House No., Street, Subdivision">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-2">
                                <label for="purok_zone" class="form-label fw-semibold">Purok / Zone</label>
                                <input type="text" class="form-control @error('purok_zone') is-invalid @enderror" id="purok_zone" name="purok_zone" value="{{ old('purok_zone', $resident->purok_zone ?? '') }}">
                                @error('purok_zone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-2">
                                <label for="household_id" class="form-label fw-semibold">Household (Optional)</label>
                                <select class="form-select @error('household_id') is-invalid @enderror" id="household_id" name="household_id">
                                    <option value="">None / Not listed</option>
                                    @foreach($households as $household)
                                        <option value="{{ $household->id }}" {{ old('household_id', $resident->household_id ?? '') == $household->id ? 'selected' : '' }}>
                                            {{ $household->name }} ({{ $household->household_number }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('household_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-3 border-top text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> {{ $resident ? 'Update Profile' : 'Save Profile' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
