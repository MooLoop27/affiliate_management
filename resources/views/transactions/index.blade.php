@extends('layouts.app')

@section('title', 'Transaksi')
@section('header', 'Transaksi')
@section('header-actions')
@if(auth()->user()->hasAnyRole(['owner', 'admin']))
<a href="{{ route('transactions.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-1"></i> Buat Transaksi
</a>
@endif
@endsection

@section('content')
<!-- Search & Filter Bar -->
<div class="search-filter-bar">
    <form method="GET" action="{{ route('transactions.index') }}">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-3">
                <label class="form-label small fw-semibold">Cari</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Kode, Partner, Leader..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small fw-semibold">Partner SG</label>
                <select name="singapore_partner_id" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach($singaporePartners as $partner)
                    <option value="{{ $partner->id }}" {{ request('singapore_partner_id') == $partner->id ? 'selected' : '' }}>{{ $partner->partner_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small fw-semibold">Leader</label>
                <select name="leader_id" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach($leaders as $leader)
                    <option value="{{ $leader->id }}" {{ request('leader_id') == $leader->id ? 'selected' : '' }}>{{ $leader->leader_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small fw-semibold">Bulan</label>
                <select name="month" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small fw-semibold">Tahun</label>
                <select name="year" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach(range(now()->year - 5, now()->year) as $y)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-1">
                <button type="submit" class="btn btn-sm btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>
</div>

<div class="table-container">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Kode Transaksi</th>
                    <th>Tanggal</th>
                    <th>Partner SG</th>
                    <th>Leader</th>
                    <th class="text-end">Saldo</th>
                    <th class="text-end">Total Komisi</th>
                    <th class="text-center">Penerima</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                <tr>
                    <td><span class="badge bg-dark">{{ $transaction->transaction_code }}</span></td>
                    <td>{{ $transaction->date->format('d/m/Y') }}</td>
                    <td>{{ $transaction->singaporePartner->partner_name ?? '-' }}</td>
                    <td>{{ $transaction->leader->leader_name ?? '-' }}</td>
                    <td class="text-end">Rp {{ number_format($transaction->company_balance_amount, 0, ',', '.') }}</td>
                    <td class="text-end fw-semibold">Rp {{ number_format($transaction->total_commission, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <span class="badge bg-info text-white">{{ $transaction->commissionDetails->count() }}</span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-sm btn-outline-info" title="Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                        @if(auth()->user()->hasAnyRole(['owner', 'admin']))
                        <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-sm btn-outline-primary" title="Ubah">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button onclick="confirmAction('Hapus {{ $transaction->transaction_code }}?', () => document.getElementById('delete-{{ $transaction->id }}').submit())" class="btn btn-sm btn-outline-danger" title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                        <form id="delete-{{ $transaction->id }}" action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="d-none">
                            @csrf @method('DELETE')
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="bi bi-receipt"></i>
                            <h5>Belum Ada Transaksi</h5>
                            <p class="text-muted">Mulai dengan membuat transaksi pertama Anda.</p>
                            @if(auth()->user()->hasAnyRole(['owner', 'admin']))
                            <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-1"></i> Buat Transaksi
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end">
        {{ $transactions->appends(request()->query())->links() }}
    </div>
</div>
@endsection

