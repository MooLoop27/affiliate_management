@extends('layouts.app')

@section('title', 'Leader Details')
@section('header', 'Leader Details')
@section('header-actions')
<a href="{{ route('leaders.edit', $leader) }}" class="btn btn-primary">
    <i class="bi bi-pencil me-1"></i> Edit
</a>
<a href="{{ route('leaders.index') }}" class="btn btn-secondary">
    <i class="bi bi-arrow-left me-1"></i> Back
</a>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Leader Information</h5>
                <table class="table table-sm">
                    <tr>
                        <td class="text-muted">Code</td>
                        <td class="fw-semibold"><span class="badge bg-info text-white">{{ $leader->leader_code }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Name</td>
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
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Notes</td>
                        <td>{{ $leader->notes ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Recipients ({{ $leader->recipients->count() }})</h5>
                <div class="list-group list-group-flush">
                    @forelse($leader->recipients as $recipient)
                    <div class="list-group-item px-0 border-0">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="fw-semibold small">{{ $recipient->recipient_name }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">{{ $recipient->recipient_code }}</div>
                            </div>
                            <span class="badge bg-{{ $recipient->status === 'active' ? 'success' : 'secondary' }} align-self-center">
                                {{ ucfirst($recipient->status) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-3 small">No recipients assigned</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Transactions ({{ $leader->transactions->count() }})</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Transaction Code</th>
                                <th>Date</th>
                                <th>SG Partner</th>
                                <th class="text-end">Amount</th>
                                <th class="text-end">Commission</th>
                                <th class="text-end">Actions</th>
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
                                <td class="text-end">
                                    <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">No transactions found</td>
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

