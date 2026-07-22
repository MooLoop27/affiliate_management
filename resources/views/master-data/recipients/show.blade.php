@extends('layouts.app')

@section('title', 'Detail Penerima Komisi')
@section('header', 'Detail Penerima Komisi')
@section('header-actions')
<a href="{{ route('recipients.edit', $recipient) }}" class="btn btn-primary">
    <i class="bi bi-pencil me-1"></i> Ubah
</a>
<a href="{{ route('recipients.index') }}" class="btn btn-secondary">
    <i class="bi bi-arrow-left me-1"></i> Kembali
</a>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Informasi Penerima</h5>
                <table class="table table-sm">
                    <tr>
                        <td class="text-muted">Kode</td>
                        <td class="fw-semibold"><span class="badge bg-success">{{ $recipient->recipient_code }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nama</td>
                        <td class="fw-semibold">{{ $recipient->recipient_name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">WhatsApp</td>
                        <td>{{ $recipient->whatsapp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Bank</td>
                        <td>{{ $recipient->bank_name ?? '-' }} {{ $recipient->bank_account_number ? '('.$recipient->bank_account_number.')' : '' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            @if($recipient->status === 'active')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Catatan</td>
                        <td>{{ $recipient->notes ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Dibuat</td>
                        <td>{{ $recipient->created_at->format('d/m/Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Riwayat Komisi ({{ $recipient->commissionDetails->count() }})</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Transaksi</th>
                                <th>Tanggal</th>
                                <th class="text-end">Persentase</th>
                                <th class="text-end">Jumlah</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recipient->commissionDetails as $detail)
                            <tr>
                                <td><a href="{{ route('transactions.show', $detail->transaction) }}" class="text-decoration-none">{{ $detail->transaction->transaction_code }}</a></td>
                                <td>{{ $detail->transaction->date->format('d/m/Y') }}</td>
                                <td class="text-end">{{ $detail->commission_percentage }}%</td>
                                <td class="text-end">Rp {{ number_format($detail->commission_amount, 0, ',', '.') }}</td>
                                <td>
                                    @if($detail->payment_status === 'paid')
                                        <span class="badge bg-success">Dibayar</span>
                                    @elseif($detail->payment_status === 'processing')
                                        <span class="badge bg-info text-white">Diproses</span>
                                    @elseif($detail->payment_status === 'cancelled')
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">Belum ada komisi</td>
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

