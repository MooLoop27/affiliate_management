@extends('layouts.app')

@section('title', 'Recipient Details')
@section('header', 'Recipient Details')
@section('header-actions')
<a href="{{ route('recipients.edit', $recipient) }}" class="btn btn-primary">
    <i class="bi bi-pencil me-1"></i> Edit
</a>
<a href="{{ route('recipients.index') }}" class="btn btn-secondary">
    <i class="bi bi-arrow-left me-1"></i> Back
</a>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Recipient Information</h5>
                <table class="table table-sm">
                    <tr>
                        <td class="text-muted">Code</td>
                        <td class="fw-semibold"><span class="badge bg-success">{{ $recipient->recipient_code }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Name</td>
                        <td class="fw-semibold">{{ $recipient->recipient_name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">WhatsApp</td>
                        <td>{{ $recipient->whatsapp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Bank</td>
                        <td>{{ $recipient->bank_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Account Number</td>
                        <td>{{ $recipient->bank_account_number ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            @if($recipient->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Notes</td>
                        <td>{{ $recipient->notes ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Commission History</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Transaction</th>
                                <th class="text-end">Percentage</th>
                                <th class="text-end">Amount</th>
                                <th>Status</th>
                                <th>Transfer Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recipient->commissionDetails as $detail)
                            <tr>
                                <td>
                                    <a href="{{ route('transactions.show', $detail->transaction) }}" class="text-decoration-none">
                                        {{ $detail->transaction->transaction_code ?? '-' }}
                                    </a>
                                </td>
                                <td class="text-end">{{ $detail->commission_percentage }}%</td>
                                <td class="text-end">Rp {{ number_format($detail->commission_amount, 0, ',', '.') }}</td>
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
                                <td class="text-end">
                                    <a href="{{ route('transactions.show', $detail->transaction) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">No commission history</td>
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

