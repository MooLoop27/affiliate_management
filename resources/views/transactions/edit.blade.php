@extends('layouts.app')

@section('title', 'Ubah Transaksi')
@section('header', 'Ubah Transaksi')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="table-container">
            <form action="{{ route('transactions.update', $transaction) }}" method="POST">
                @csrf @method('PUT')

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', $transaction->date->format('Y-m-d')) }}" required>
                        @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Partner SG <span class="text-danger">*</span></label>
                        <select name="singapore_partner_id" class="form-select @error('singapore_partner_id') is-invalid @enderror" required>
                            <option value="">Pilih Partner</option>
                            @foreach($singaporePartners as $partner)
                            <option value="{{ $partner->id }}" {{ old('singapore_partner_id', $transaction->singapore_partner_id) == $partner->id ? 'selected' : '' }}>{{ $partner->sg_code }} - {{ $partner->partner_name }}</option>
                            @endforeach
                        </select>
                        @error('singapore_partner_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Leader <span class="text-danger">*</span></label>
                        <select name="leader_id" class="form-select @error('leader_id') is-invalid @enderror" required>
                            <option value="">Pilih Leader</option>
                            @foreach($leaders as $leader)
                            <option value="{{ $leader->id }}" {{ old('leader_id', $transaction->leader_id) == $leader->id ? 'selected' : '' }}>{{ $leader->leader_code }} - {{ $leader->leader_name }}</option>
                            @endforeach
                        </select>
                        @error('leader_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Jumlah Saldo <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="company_balance_amount" class="form-control @error('company_balance_amount') is-invalid @enderror" value="{{ old('company_balance_amount', $transaction->company_balance_amount) }}" step="0.01" required>
                        </div>
                        @error('company_balance_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Komisi SG (%)</label>
                        <div class="input-group">
                            <input type="number" name="sg_commission_percentage" class="form-control" value="{{ old('sg_commission_percentage', $transaction->sg_commission_percentage) }}" step="0.01">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Komisi Leader (%)</label>
                        <div class="input-group">
                            <input type="number" name="leader_commission_percentage" class="form-control" value="{{ old('leader_commission_percentage', $transaction->leader_commission_percentage) }}" step="0.01">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes', $transaction->notes) }}</textarea>
                        @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Perbarui Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

