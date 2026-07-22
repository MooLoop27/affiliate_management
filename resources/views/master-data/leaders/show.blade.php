@extends('layouts.app')

@section('title', 'Detail Leader')
@section('header', 'Detail Leader')
@section('header-actions')
<a href="{{ route('leaders.edit', $leader) }}" class="btn btn-primary">
    <i class="bi bi-pencil me-1"></i> Ubah
</a>
<a href="{{ route('leaders.index') }}" class="btn btn-secondary">
    <i class="bi bi-arrow-left me-1"></i> Kembali
</a>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Informasi Leader</h5>
                <table class="table table-sm">
                    <tr>
                        <td class="text-muted">Kode</td>
                        <td class="fw-semibold"><span class="badge bg-info text-white">{{ $leader->leader_code }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nama</td>
                        <td class="fw-semibold">{{ $leader->leader_name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">WhatsApp</td>
                        <td>{{ $leader->whatsapp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            @if($leader->status === 'active')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Catatan</td>
                        <td>{{ $leader->notes ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Dibuat</td>
                        <td>{{ $leader->created_at->format('d/m/Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Ringkasan</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Transaksi</span>
                    <span class="fw-bold">{{ number_format($leader->transactions->count()) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Penerima</span>
                    <span class="fw-bold">{{ number_format($leader->recipients->count()) }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Total Komisi</span>
                    <span class="fw-bold">Rp {{ number_format($leader->transactions->sum('total_commission'), 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Penerima Komisi ({{ $leader->recipients->count() }})</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Bank</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leader->recipients as $recipient)
                            <tr>
                                <td><span class="badge bg-success">{{ $recipient->recipient_code }}</span></td>
                                <td>{{ $recipient->recipient_name }}</td>
                                <td>{{ $recipient->bank_name ?? '-' }} {{ $recipient->bank_account_number ? '('.$recipient->bank_account_number.')' : '' }}</td>
                                <td>
                                    @if($recipient->status === 'active')
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">Tidak ada penerima</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Transaksi ({{ $leader->transactions->count() }})</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Tanggal</th>
                                <th>Partner SG</th>
                                <th class="text-end">Saldo</th>
                                <th class="text-end">Komisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leader->transactions as $transaction)
                            <tr>
                                <td><a href="{{ route('transactions.show', $transaction) }}" class="text-decoration-none">{{ $transaction->transaction_code }}</a></td>
                                <td>{{ $transaction->date->format('d/m/Y') }}</td>
                                <td>{{ $transaction->singaporePartner->partner_name ?? '-' }}</td>
                                <td class="text-end">Rp {{ number_format($transaction->company_balance_amount, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($transaction->total_commission, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">Tidak ada transaksi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

