@extends('layouts.app')

@section('title', 'Singapore Partner Details')
@section('header', 'Singapore Partner Details')
@section('header-actions')
<a href="{{ route('singapore-partners.edit', $singaporePartner) }}" class="btn btn-primary">
    <i class="bi bi-pencil me-1"></i> Edit
</a>
<a href="{{ route('singapore-partners.index') }}" class="btn btn-secondary">
    <i class="bi bi-arrow-left me-1"></i> Back
</a>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Partner Information</h5>
                <table class="table table-sm">
                    <tr>
                        <td class="text-muted">SG Code</td>
                        <td class="fw-semibold"><span class="badge bg-primary">{{ $singaporePartner->sg_code }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Name</td>
                        <td class="fw-semibold">{{ $singaporePartner->partner_name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">WhatsApp</td>
                        <td>{{ $singaporePartner->whatsapp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Email</td>
                        <td>{{ $singaporePartner->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            @if($singaporePartner->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Notes</td>
                        <td>{{ $singaporePartner->notes ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Created</td>
                        <td>{{ $singaporePartner->created_at->format('d/m/Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Transactions ({{ $singaporePartner->transactions->count() }})</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Transaction Code</th>
                                <th>Date</th>
                                <th>Leader</th>
                                <th class="text-end">Amount</th>
                                <th class="text-end">Commission</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($singaporePartner->transactions as $transaction)
                            <tr>
                                <td><a href="{{ route('transactions.show', $transaction) }}" class="text-decoration-none">{{ $transaction->transaction_code }}</a></td>
                                <td>{{ $transaction->date->format('d/m/Y') }}</td>
                                <td>{{ $transaction->leader->leader_name ?? '-' }}</td>
                                <td class="text-end">Rp {{ number_format($transaction->company_balance_amount, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($transaction->total_commission, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">No transactions found</td>
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

