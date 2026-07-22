@extends('layouts.app')

@section('title', 'Transaction Details')
@section('header', 'Transaction: ' . $transaction->transaction_code)
@section('header-actions')
@if(auth()->user()->hasAnyRole(['owner', 'admin']))
<a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-primary">
    <i class="bi bi-pencil me-1"></i> Edit
</a>
@endif
<a href="{{ route('transactions.index') }}" class="btn btn-secondary">
    <i class="bi bi-arrow-left me-1"></i> Back
</a>
@endsection

@section('content')
<!-- Transaction Information -->
<div class="row g-4 mb-4">
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="fw-bold mb-3"><i class="bi bi-receipt me-2"></i>Transaction Information</h5>
                <table class="table table-sm">
                    <tr>
                        <td class="text-muted">Code</td>
                        <td class="fw-semibold"><span class="badge bg-dark">{{ $transaction->transaction_code }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Date</td>
                        <td>{{ $transaction->date->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Total Commission</td>
                        <td class="fw-bold text-primary">Rp {{ number_format($transaction->total_commission, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Notes</td>
                        <td>{{ $transaction->notes ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5 class="fw-bold mb-3"><i class="bi bi-building me-2"></i>SG Partner</h5>
                <table class="table table-sm">
                    <tr>
                        <td class="text-muted">Code</td>
                        <td class="fw-semibold"><span class="badge bg-primary">{{ $transaction->singaporePartner->sg_code ?? '-' }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Name</td>
                        <td>{{ $transaction->singaporePartner->partner_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Commission</td>
                        <td>{{ $transaction->sg_commission_percentage }}%</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Amount</td>
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
                        <td class="text-muted">Code</td>
                        <td class="fw-semibold"><span class="badge bg-info text-white">{{ $transaction->leader->leader_code ?? '-' }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Name</td>
                        <td>{{ $transaction->leader->leader_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Commission</td>
                        <td>{{ $transaction->leader_commission_percentage }}%</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Amount</td>
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
        <i class="bi bi-person-badge me-2"></i>Commission Recipients
        <span class="badge bg-info text-white ms-2">{{ $transaction->commissionDetails->count() }}</span>
    </h5>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Recipient</th>
                    <th>Bank</th>
                    <th class="text-end">Percentage</th>
                    <th class="text-end">Amount</th>
                    <th>Status</th>
                    <th>Transfer Date</th>
                    <th>Transfer Proof</th>
                    <th>Notes</th>
                    @if(auth()->user()->hasAnyRole(['owner', 'admin', 'finance']))
                    <th class="text-end">Actions</th>
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
                            <span class="badge bg-success">Paid</span>
                        @elseif($detail->payment_status === 'processing')
                            <span class="badge bg-warning text-dark">Processing</span>
                        @elseif($detail->payment_status === 'cancelled')
                            <span class="badge bg-danger">Cancelled</span>
                        @else
                            <span class="badge bg-secondary">Pending</span>
                        @endif
                    </td>
                    <td>{{ $detail->transfer_date ? $detail->transfer_date->format('d/m/Y') : '-' }}</td>
                    <td>
                        @if($detail->transfer_proof)
                            <a href="{{ route('commission-details.download-proof', $detail) }}" class="btn btn-sm btn-outline-success" target="_blank">
                                <i class="bi bi-paperclip"></i> View
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
                            <i class="bi bi-credit-card"></i> Payment
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
                                    <h5 class="modal-title">Update Payment Status</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Recipient</label>
                                        <input type="text" class="form-control" value="{{ $detail->recipient->recipient_name }}" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Amount</label>
                                        <input type="text" class="form-control" value="Rp {{ number_format($detail->commission_amount, 0, ',', '.') }}" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Payment Status <span class="text-danger">*</span></label>
                                        <select name="payment_status" class="form-select" required>
                                            <option value="pending" {{ $detail->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing" {{ $detail->payment_status === 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="paid" {{ $detail->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="cancelled" {{ $detail->payment_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Transfer Date</label>
                                        <input type="date" name="transfer_date" class="form-control" value="{{ $detail->transfer_date ? $detail->transfer_date->format('Y-m-d') : '' }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Transfer Proof</label>
                                        <input type="file" name="transfer_proof" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                        <small class="text-muted">Allowed: JPG, PNG, PDF (max 2MB)</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Notes</label>
                                        <textarea name="payment_notes" class="form-control" rows="2">{{ $detail->payment_notes }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-lg me-1"></i> Update Payment
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
                            <h5>No Recipients</h5>
                            <p class="text-muted">No commission recipients in this transaction.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="fw-bold">
                    <td colspan="3" class="text-end">Total Recipient Commission</td>
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
                <div class="small text-muted">Company Balance</div>
                <div class="fw-bold fs-5">Rp {{ number_format($transaction->company_balance_amount, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="card border-0 shadow-sm bg-info bg-opacity-10">
            <div class="card-body text-center">
                <div class="small text-muted">SG Commission ({{ $transaction->sg_commission_percentage }}%)</div>
                <div class="fw-bold fs-5">Rp {{ number_format($transaction->sg_commission_amount, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="card border-0 shadow-sm bg-warning bg-opacity-10">
            <div class="card-body text-center">
                <div class="small text-muted">Leader Commission ({{ $transaction->leader_commission_percentage }}%)</div>
                <div class="fw-bold fs-5">Rp {{ number_format($transaction->leader_commission_amount, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="card border-0 shadow-sm bg-success bg-opacity-10">
            <div class="card-body text-center">
                <div class="small text-muted">Total Commission</div>
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
            let html = '<div class="table-responsive"><table class="table table-sm"><thead><tr><th>Date</th><th>Time</th><th>User</th><th>Status</th><th>Notes</th></tr></thead><tbody>';
            data.payment_histories.forEach(h => {
                html += `<tr>
                    <td>${h.date}</td>
                    <td>${h.time}</td>
                    <td>${h.user?.name || '-'}</td>
                    <td><span class="badge bg-${h.status === 'paid' ? 'success' : h.status === 'processing' ? 'warning' : h.status === 'cancelled' ? 'danger' : 'secondary'}">${h.status}</span></td>
                    <td>${h.notes || '-'}</td>
                </tr>`;
            });
            html += '</tbody></table></div>';

            Swal.fire({
                title: 'Payment History - ' + data.recipient?.recipient_name,
                html: html,
                width: '800px',
                confirmButtonText: 'Close'
            });
        });
}
</script>
@endpush

