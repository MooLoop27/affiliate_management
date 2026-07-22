@extends('layouts.app')

@section('title', 'Detail Transaksi')
@section('header', 'Transaksi: ' . $transaction->transaction_code)
@section('header-actions')
@if(auth()->user()->hasAnyRole(['owner', 'admin']))
<a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-primary">
    <i class="bi bi-pencil me-1"></i> Ubah
</a>
@endif
<a href="{{ route('transactions.index') }}" class="btn btn-secondary">
    <i class="bi bi-arrow-left me-1"></i> Kembali
</a>
@endsection

@section('content')
<!-- Transaction Information -->
<div class="row g-4 mb-4">
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="fw-bold mb-3"><i class="bi bi-receipt me-2"></i>Informasi Transaksi</h5>
                <table class="table table-sm">
                    <tr>
                        <td class="text-muted">Kode</td>
                        <td class="fw-semibold"><span class="badge bg-dark">{{ $transaction->transaction_code }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tanggal</td>
                        <td>{{ $transaction->date->isoFormat('D MMMM YYYY') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Total Komisi</td>
                        <td class="fw-bold text-primary">Rp {{ number_format($transaction->total_commission, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Catatan</td>
                        <td>{{ $transaction->notes ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="fw-bold mb-3"><i class="bi bi-building me-2"></i>Partner SG</h5>
                <table class="table table-sm">
                    <tr>
                        <td class="text-muted">Kode</td>
                        <td class="fw-semibold"><span class="badge bg-primary">{{ $transaction->singaporePartner->sg_code ?? '-' }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nama</td>
                        <td>{{ $transaction->singaporePartner->partner_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Komisi</td>
                        <td>{{ $transaction->sg_commission_percentage }}%</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Jumlah</td>
                        <td class="fw-bold">Rp {{ number_format($transaction->sg_commission_amount, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="fw-bold mb-3"><i class="bi bi-people-fill me-2"></i>Leader</h5>
                <table class="table table-sm">
                    <tr>
                        <td class="text-muted">Kode</td>
                        <td class="fw-semibold"><span class="badge bg-info text-white">{{ $transaction->leader->leader_code ?? '-' }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nama</td>
                        <td>{{ $transaction->leader->leader_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Komisi</td>
                        <td>{{ $transaction->leader_commission_percentage }}%</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Jumlah</td>
                        <td class="fw-bold">Rp {{ number_format($transaction->leader_commission_amount, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Commission Recipients -->
<div class="table-container">
    <h5 class="fw-bold mb-3">
        <i class="bi bi-person-badge me-2"></i>Penerima Komisi
        <span class="badge bg-info text-white ms-2">{{ $transaction->commissionDetails->count() }}</span>
    </h5>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Penerima</th>
                    <th>Bank</th>
                    <th class="text-end">Persentase</th>
                    <th class="text-end">Jumlah</th>
                    <th>Status</th>
                    <th>Tanggal Transfer</th>
                    <th>Bukti Transfer</th>
                    <th>Catatan</th>
                    @if(auth()->user()->hasAnyRole(['owner', 'admin', 'finance']))
                    <th class="text-end">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($transaction->commissionDetails as $detail)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $detail->recipient->recipient_name ?? '-' }}</div>
                        <small class="text-muted">{{ $detail->recipient->recipient_code ?? '' }}</small>
                    </td>
                    <td>
                        <small>{{ $detail->recipient->bank_name ?? '-' }}</small>
                        <br>
                        <small class="text-muted">{{ $detail->recipient->bank_account_number ?? '' }}</small>
                    </td>
                    <td class="text-end">{{ $detail->commission_percentage }}%</td>
                    <td class="text-end fw-semibold">Rp {{ number_format($detail->commission_amount, 0, ',', '.') }}</td>
                    <td>
                        @if($detail->payment_status === 'paid')
                            <span class="badge bg-success">Dibayar</span>
                        @elseif($detail->payment_status === 'processing')
                            <span class="badge bg-warning text-dark">Diproses</span>
                        @elseif($detail->payment_status === 'cancelled')
                            <span class="badge bg-danger">Dibatalkan</span>
                        @else
                            <span class="badge bg-secondary">Pending</span>
                        @endif
                    </td>
                    <td>{{ $detail->transfer_date ? $detail->transfer_date->format('d/m/Y') : '-' }}</td>
                    <td>
                        @if($detail->transfer_proof)
                            <a href="{{ route('commission-details.download-proof', $detail) }}" class="btn btn-sm btn-outline-success" target="_blank">
                                <i class="bi bi-paperclip"></i> Lihat
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <small>{{ Str::limit($detail->payment_notes, 30) ?? '-' }}</small>
                    </td>
                    @if(auth()->user()->hasAnyRole(['owner', 'admin', 'finance']))
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $detail->id }}">
                            <i class="bi bi-credit-card"></i> Pembayaran
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-info" onclick="showPaymentHistory({{ $detail->id }})">
                            <i class="bi bi-clock-history"></i>
                        </button>
                    </td>
                    @endif
                </tr>

                <!-- Payment Modal -->
                <div class="modal fade" id="paymentModal{{ $detail->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('commission-details.update-payment', $detail) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Perbarui Status Pembayaran</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Penerima</label>
                                        <input type="text" class="form-control" value="{{ $detail->recipient->recipient_name }}" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Jumlah</label>
                                        <input type="text" class="form-control" value="Rp {{ number_format($detail->commission_amount, 0, ',', '.') }}" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Status Pembayaran <span class="text-danger">*</span></label>
                                        <select name="payment_status" class="form-select" required>
                                            <option value="pending" {{ $detail->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing" {{ $detail->payment_status === 'processing' ? 'selected' : '' }}>Diproses</option>
                                            <option value="paid" {{ $detail->payment_status === 'paid' ? 'selected' : '' }}>Dibayar</option>
                                            <option value="cancelled" {{ $detail->payment_status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Transfer</label>
                                        <input type="date" name="transfer_date" class="form-control" value="{{ $detail->transfer_date ? $detail->transfer_date->format('Y-m-d') : '' }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Bukti Transfer</label>
                                        <input type="file" name="transfer_proof" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                        <small class="text-muted">Diizinkan: JPG, PNG, PDF (max 2MB)</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Catatan</label>
                                        <textarea name="payment_notes" class="form-control" rows="2">{{ $detail->payment_notes }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-lg me-1"></i> Perbarui Pembayaran
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <i class="bi bi-person-badge"></i>
                            <h5>Tidak Ada Penerima</h5>
                            <p class="text-muted">Tidak ada penerima komisi dalam transaksi ini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="fw-bold">
                    <td colspan="3" class="text-end">Total Komisi Penerima</td>
                    <td class="text-end">Rp {{ number_format($transaction->recipient_total_commission, 0, ',', '.') }}</td>
                    <td colspan="{{ auth()->user()->hasAnyRole(['owner', 'admin', 'finance']) ? 5 : 4 }}"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Company Balance Summary -->
<div class="row g-4 mt-2">
    <div class="col-12 col-md-3">
        <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
            <div class="card-body text-center">
                <div class="small text-muted">Saldo Perusahaan</div>
                <div class="fw-bold fs-5">Rp {{ number_format($transaction->company_balance_amount, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="card border-0 shadow-sm bg-info bg-opacity-10">
            <div class="card-body text-center">
                <div class="small text-muted">Komisi SG ({{ $transaction->sg_commission_percentage }}%)</div>
                <div class="fw-bold fs-5">Rp {{ number_format($transaction->sg_commission_amount, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="card border-0 shadow-sm bg-warning bg-opacity-10">
            <div class="card-body text-center">
                <div class="small text-muted">Komisi Leader ({{ $transaction->leader_commission_percentage }}%)</div>
                <div class="fw-bold fs-5">Rp {{ number_format($transaction->leader_commission_amount, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="card border-0 shadow-sm bg-success bg-opacity-10">
            <div class="card-body text-center">
                <div class="small text-muted">Total Komisi</div>
                <div class="fw-bold fs-5">Rp {{ number_format($transaction->total_commission, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showPaymentHistory(detailId) {
    fetch(`/commission-details/${detailId}/history`)
        .then(res => res.json())
        .then(data => {
            let html = '<div class="table-responsive"><table class="table table-sm"><thead><tr><th>Tanggal</th><th>Waktu</th><th>Pengguna</th><th>Status</th><th>Catatan</th></tr></thead><tbody>';
            data.payment_histories.forEach(h => {
                html += `<tr>
                    <td>${h.date}</td>
                    <td>${h.time}</td>
                    <td>${h.user?.name || '-'}</td>
                    <td><span class="badge bg-${h.status === 'paid' ? 'success' : h.status === 'processing' ? 'warning' : h.status === 'cancelled' ? 'danger' : 'secondary'}">${h.status === 'paid' ? 'Dibayar' : h.status === 'processing' ? 'Diproses' : h.status === 'cancelled' ? 'Dibatalkan' : 'Pending'}</span></td>
                    <td>${h.notes || '-'}</td>
                </tr>`;
            });
            html += '</tbody></table></div>';

            Swal.fire({
                title: 'Riwayat Pembayaran - ' + data.recipient?.recipient_name,
                html: html,
                width: '800px',
                confirmButtonText: 'Tutup'
            });
        });
}
</script>
@endpush

