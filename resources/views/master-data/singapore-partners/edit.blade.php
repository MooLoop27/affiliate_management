@extends('layouts.app')

@section('title', 'Edit Singapore Partner')
@section('header', 'Edit Singapore Partner')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="table-container">
            <form action="{{ route('singapore-partners.update', $singaporePartner) }}" method="POST">
                @csrf @method('PUT')

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">SG Code</label>
                        <input type="text" class="form-control" value="{{ $singaporePartner->sg_code }}" disabled>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Partner Name <span class="text-danger">*</span></label>
                        <input type="text" name="partner_name" class="form-control @error('partner_name') is-invalid @enderror" value="{{ old('partner_name', $singaporePartner->partner_name) }}" required>
                        @error('partner_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">WhatsApp</label>
                        <input type="text" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror" value="{{ old('whatsapp', $singaporePartner->whatsapp) }}">
                        @error('whatsapp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $singaporePartner->email) }}">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="active" {{ old('status', $singaporePartner->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $singaporePartner->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $singaporePartner->notes) }}</textarea>
                        @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('singapore-partners.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Update Partner
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

