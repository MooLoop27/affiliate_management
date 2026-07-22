@extends('layouts.app')

@section('title', 'Tambah Penerima Komisi')
@section('header', 'Tambah Penerima Komisi')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="table-container">
            <form action="{{ route('recipients.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Nama Penerima <span class="text-danger">*</span></label>
                        <input type="text" name="recipient_name" class="form-control @error('recipient_name') is-invalid @enderror" value="{{ old('recipient_name') }}" required>
                        @error('recipient_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">WhatsApp</label>
                        <input type="text" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror" value="{{ old('whatsapp') }}">
                        @error('whatsapp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nama Bank</label>
                        <input type="text" name="bank_name" class="form-control @error('bank_name') is-invalid @enderror" value="{{ old('bank_name') }}">
                        @error('bank_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">No. Rekening</label>
                        <input type="text" name="bank_account_number" class="form-control @error('bank_account_number') is-invalid @enderror" value="{{ old('bank_account_number') }}">
                        @error('bank_account_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                        @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('recipients.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Simpan Penerima
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

