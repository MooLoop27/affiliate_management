<div class="sidebar">
    <div class="sidebar-brand d-flex align-items-center gap-2">
        <div>
            <h4><i class="bi bi-currency-exchange me-2"></i>Afiliasi</h4>
            <small>Sistem Manajemen Komisi</small>
        </div>
    </div>

    <div class="nav flex-column">
        <div class="nav-section">Utama</div>

        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i>
            <span>Dashboard</span>
        </a>

        @if(auth()->user()->hasAnyRole(['owner', 'admin']))
        <div class="nav-section">Data Master</div>

        <a href="{{ route('singapore-partners.index') }}" class="nav-link {{ request()->routeIs('singapore-partners.*') ? 'active' : '' }}">
            <i class="bi bi-building"></i>
            <span>Partner Singapore</span>
        </a>

        <a href="{{ route('leaders.index') }}" class="nav-link {{ request()->routeIs('leaders.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i>
            <span>Leader</span>
        </a>

        <a href="{{ route('recipients.index') }}" class="nav-link {{ request()->routeIs('recipients.*') ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i>
            <span>Penerima Komisi</span>
        </a>
        @endif

        <div class="nav-section">Transaksi</div>

        @if(auth()->user()->hasAnyRole(['owner', 'admin', 'finance']))
        <a href="{{ route('transactions.index') }}" class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i>
            <span>Transaksi</span>
        </a>
        @endif

        @if(auth()->user()->hasAnyRole(['owner', 'admin', 'finance']))
        <div class="nav-section">Laporan</div>

        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph"></i>
            <span>Laporan</span>
        </a>
        @endif

        @if(auth()->user()->hasAnyRole(['owner', 'admin']))
        <div class="nav-section">Sistem</div>

        <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <i class="bi bi-gear-fill"></i>
            <span>Pengaturan</span>
        </a>

        <a href="{{ route('activity-logs.index') }}" class="nav-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i>
            <span>Log Aktivitas</span>
        </a>
        @endif

        @if(auth()->user()->isOwner())
        <div class="nav-section">Administrasi</div>

        <a href="{{ route('user-management.index') }}" class="nav-link {{ request()->routeIs('user-management.*') ? 'active' : '' }}">
            <i class="bi bi-shield-lock-fill"></i>
            <span>Kelola Pengguna</span>
        </a>
        @endif
    </div>
</div>

