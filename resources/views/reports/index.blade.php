@extends('layouts.app')

@section('title', 'Laporan')
@section('header', 'Laporan & Analitik')

@section('content')
<!-- Filter Bar -->
<div class="search-filter-bar">
    <form method="GET" action="{{ route('reports.index') }}">
        <div class="row g-2 align-items-end">
            <div class="col-6 col-md-2">
                <label class="form-label small fw-semibold">Tanggal Dari</label>
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small fw-semibold">Tanggal Sampai</label>
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
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
            <div class="col-12 col-md-2">
                <button type="submit" class="btn btn-sm btn-primary w-100">Generate</button>
            </div>
            <div class="col-12 col-md-2">
                <a href="{{ route('reports.index') }}" class="btn btn-sm btn-secondary w-100">Reset</a>
            </div>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label mb-1">Total Transaksi</div>
                        <div class="stat-value">{{ number_format($totalTransactions) }}</div>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label mb-1">Saldo Perusahaan</div>
                        <div class="stat-value">Rp {{ number_format($totalCompanyBalance, 0, ',', '.') }}</div>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label mb-1">Komisi SG</div>
                        <div class="stat-value">Rp {{ number_format($totalSgCommission, 0, ',', '.') }}</div>
                    </div>
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-flag-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label mb-1">Komisi Leader</div>
                        <div class="stat-value">Rp {{ number_format($totalLeaderCommission, 0, ',', '.') }}</div>
                    </div>
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-person-badge"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label mb-1">Komisi Penerima</div>
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
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label mb-1">Pembayaran Pending</div>
                        <div class="stat-value">Rp {{ number_format($pendingPayments, 0, ',', '.') }}</div>
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
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label mb-1">Pembayaran Selesai</div>
                        <div class="stat-value">Rp {{ number_format($paidPayments, 0, ',', '.') }}</div>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Buttons -->
<div class="d-flex gap-2 mb-4 no-print">
    <a href="{{ route('reports.export-excel', request()->query()) }}" class="btn btn-success">
        <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
    </a>
    <a href="{{ route('reports.export-pdf', request()->query()) }}" class="btn btn-danger">
        <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
    </a>
    <button onclick="window.print()" class="btn btn-secondary">
        <i class="bi bi-printer me-1"></i> Cetak Laporan
    </button>
</div>

<!-- Transactions Table -->
<div class="table-container">
    <h5 class="fw-bold mb-3">Detail Transaksi</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode Transaksi</th>
                    <th>Partner SG</th>
                    <th>Leader</th>
                    <th class="text-end">Saldo</th>
                    <th class="text-end">Kom. SG</th>
                    <th class="text-end">Kom. Leader</th>
                    <th class="text-end">Kom. Penerima</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->date->format('d/m/Y') }}</td>
                    <td><span class="badge bg-dark">{{ $transaction->transaction_code }}</span></td>
                    <td>{{ $transaction->singaporePartner->partner_name ?? '-' }}</td>
                    <td>{{ $transaction->leader->leader_name ?? '-' }}</td>
                    <td class="text-end">Rp {{ number_format($transaction->company_balance_amount, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($transaction->sg_commission_amount, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($transaction->leader_commission_amount, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($transaction->recipient_total_commission, 0, ',', '.') }}</td>
                    <td class="text-end fw-semibold">Rp {{ number_format($transaction->total_commission, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <i class="bi bi-file-earmark-bar-graph"></i>
                            <h5>Tidak Ada Data</h5>
                            <p class="text-muted">Tidak ada transaksi yang cocok dengan filter yang dipilih.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="fw-bold table-secondary">
                    <td colspan="4" class="text-end">Total</td>
                    <td class="text-end">Rp {{ number_format($totalCompanyBalance, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($totalSgCommission, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($totalLeaderCommission, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($totalRecipientCommission, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($totalSgCommission + $totalLeaderCommission + $totalRecipientCommission, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection

