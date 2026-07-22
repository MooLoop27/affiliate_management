@extends('layouts.app')

@section('title', 'Dashboard')

@section('header', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="stat-label mb-1">Total Transactions</div>
                        <div class="stat-value">{{ number_format($totalTransactions) }}</div>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
                <div class="text-muted small">
                    <i class="bi bi-arrow-up text-success me-1"></i>
                    All time transactions
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="stat-label mb-1">Company Balance</div>
                        <div class="stat-value">Rp {{ number_format($totalCompanyBalance, 0, ',', '.') }}</div>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
                <div class="text-muted small">
                    <i class="bi bi-arrow-up text-success me-1"></i>
                    Total balance amount
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="stat-label mb-1">SG Commission</div>
                        <div class="stat-value">Rp {{ number_format($totalSgCommission, 0, ',', '.') }}</div>
                    </div>
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-flag-fill"></i>
                    </div>
                </div>
                <div class="text-muted small">
                    <i class="bi bi-arrow-up text-success me-1"></i>
                    Total SG commission
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="stat-label mb-1">Leader Commission</div>
                        <div class="stat-value">Rp {{ number_format($totalLeaderCommission, 0, ',', '.') }}</div>
                    </div>
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-person-badge"></i>
                    </div>
                </div>
                <div class="text-muted small">
                    <i class="bi bi-arrow-up text-success me-1"></i>
                    Total leader commission
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="stat-label mb-1">Recipient Commission</div>
                        <div class="stat-value">Rp {{ number_format($totalRecipientCommission, 0, ',', '.') }}</div>
                    </div>
                    <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="stat-label mb-1">Pending Payments</div>
                        <div class="stat-value text-warning">{{ number_format($pendingPayments) }}</div>
                    </div>
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="stat-label mb-1">Completed Payments</div>
                        <div class="stat-value text-success">{{ number_format($completedPayments) }}</div>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-xl-8">
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Monthly Transactions ({{ now()->year }})</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th class="text-end">Transactions</th>
                            <th class="text-end">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($monthlyTransactions as $monthData)
                        <tr>
                            <td>{{ Carbon\Carbon::create()->month($monthData->month)->format('F') }}</td>
                            <td class="text-end">{{ number_format($monthData->total) }}</td>
                            <td class="text-end">Rp {{ number_format($monthData->total_amount, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3">
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <h5>No transactions this year</h5>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Recent Transactions</h5>
                <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="list-group list-group-flush">
                @forelse($recentTransactions as $transaction)
                <a href="{{ route('transactions.show', $transaction) }}" class="list-group-item list-group-item-action px-0 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold small">{{ $transaction->transaction_code }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">
                                {{ $transaction->singaporePartner->partner_name ?? '-' }}
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold small">Rp {{ number_format($transaction->total_commission, 0, ',', '.') }}</div>
                            <div class="text-muted" style="font-size: 0.7rem;">{{ $transaction->date->format('d/m/Y') }}</div>
                        </div>
                    </div>
                </a>
                @empty
                <div class="text-center text-muted py-3 small">No recent transactions</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

