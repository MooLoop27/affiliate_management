@extends('layouts.app')

@section('title', 'Log Aktivitas')
@section('header', 'Log Aktivitas')

@section('content')
<div class="search-filter-bar">
    <form method="GET" action="{{ route('activity-logs.index') }}">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-4">
                <label class="form-label small fw-semibold">Cari</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari aktivitas..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small fw-semibold">Pengguna</label>
                <select name="user_id" class="form-select form-select-sm">
                    <option value="">Semua Pengguna</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small fw-semibold">Tanggal Dari</label>
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small fw-semibold">Tanggal Sampai</label>
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
            </div>
            <div class="col-6 col-md-2">
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
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Pengguna</th>
                    <th>Aktivitas</th>
                    <th>Alamat IP</th>
                    <th>Browser</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>{{ $log->date->format('d/m/Y') }}</td>
                    <td>{{ $log->time }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 0.7rem; font-weight: 600;">
                                {{ substr($log->user?->name ?? '?', 0, 1) }}
                            </div>
                            <span class="fw-semibold small">{{ $log->user?->name ?? 'Tidak Diketahui' }}</span>
                        </div>
                    </td>
                    <td>{{ $log->activity }}</td>
                    <td><code>{{ $log->ip_address ?? '-' }}</code></td>
                    <td>
                        <small class="text-muted">{{ Str::limit($log->browser, 50) }}</small>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="bi bi-clock-history"></i>
                            <h5>Belum Ada Log Aktivitas</h5>
                            <p class="text-muted">Belum ada aktivitas yang tercatat.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end">
        {{ $logs->appends(request()->query())->links() }}
    </div>
</div>
@endsection

