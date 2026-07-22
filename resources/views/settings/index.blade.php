@extends('layouts.app')

@section('title', 'Pengaturan')
@section('header', 'Pengaturan Sistem')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="table-container">
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3 mb-4">
                    <h5 class="fw-bold border-bottom pb-2">Pengaturan Umum</h5>

                    <div class="col-md-6">
                        <label class="form-label">Nama Perusahaan</label>
                        <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror"
                               value="{{ old('company_name', $settings->get('company_name')?->value ?? config('app.name')) }}" required>
                        @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Logo Perusahaan</label>
                        <input type="file" name="company_logo" class="form-control @error('company_logo') is-invalid @enderror" accept="image/jpg,image/jpeg,image/png">
                        @error('company_logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        @if($settings->get('company_logo')?->value)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $settings->get('company_logo')->value) }}" alt="Logo" class="transfer-proof-preview">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <h5 class="fw-bold border-bottom pb-2">Default Komisi</h5>

                    <div class="col-md-4">
                        <label class="form-label">Komisi SG (%)</label>
                        <div class="input-group">
                            <input type="number" name="sg_commission_percentage" class="form-control @error('sg_commission_percentage') is-invalid @enderror"
                                   value="{{ old('sg_commission_percentage', $settings->get('sg_commission_percentage')?->value ?? '5') }}" step="0.01" min="0" max="100">
                            <span class="input-group-text">%</span>
                        </div>
                        @error('sg_commission_percentage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Komisi Leader (%)</label>
                        <div class="input-group">
                            <input type="number" name="leader_commission_percentage" class="form-control @error('leader_commission_percentage') is-invalid @enderror"
                                   value="{{ old('leader_commission_percentage', $settings->get('leader_commission_percentage')?->value ?? '10') }}" step="0.01" min="0" max="100">
                            <span class="input-group-text">%</span>
                        </div>
                        @error('leader_commission_percentage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Default Komisi Penerima (%)</label>
                        <div class="input-group">
                            <input type="number" name="default_recipient_commission_percentage" class="form-control @error('default_recipient_commission_percentage') is-invalid @enderror"
                                   value="{{ old('default_recipient_commission_percentage', $settings->get('default_recipient_commission_percentage')?->value ?? '2') }}" step="0.01" min="0" max="100">
                            <span class="input-group-text">%</span>
                        </div>
                        @error('default_recipient_commission_percentage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <h5 class="fw-bold border-bottom pb-2">Tampilan</h5>

                    <div class="col-md-6">
                        <label class="form-label">Tema Sistem</label>
                        <select name="system_theme" class="form-select @error('system_theme') is-invalid @enderror">
                            <option value="light" {{ old('system_theme', $settings->get('system_theme')?->value ?? 'light') === 'light' ? 'selected' : '' }}>Terang</option>
                            <option value="dark" {{ old('system_theme', $settings->get('system_theme')?->value ?? 'light') === 'dark' ? 'selected' : '' }}>Gelap</option>
                        </select>
                        @error('system_theme') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

