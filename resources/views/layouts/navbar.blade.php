<nav class="top-navbar">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-sm btn-outline-secondary d-md-none" onclick="toggleSidebar()">
            <i class="bi bi-list fs-5"></i>
        </button>
        <span class="text-muted small">
            <i class="bi bi-calendar3 me-1"></i>
            {{ now()->isoFormat('dddd, D MMMM YYYY') }}
        </span>
    </div>

    <div class="d-flex align-items-center gap-2">
        <!-- Theme Toggle -->
        <button class="btn btn-sm btn-outline-secondary" id="theme-toggle" onclick="toggleTheme()" title="Ganti Tema">
            <i class="bi bi-moon-fill"></i>
        </button>

        <!-- Notifications -->
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary position-relative" data-bs-toggle="dropdown">
                <i class="bi bi-bell-fill"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                    0
                </span>
            </button>
        </div>

        <!-- Profile Dropdown -->
        <div class="dropdown profile-dropdown">
            <button class="btn btn-sm dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem; font-weight: 600;">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="text-start d-none d-md-block">
                    <div class="fw-semibold small">{{ auth()->user()->name }}</div>
                    <div class="text-muted" style="font-size: 0.65rem; text-transform: uppercase;">
                        {{ auth()->user()->role }}
                    </div>
                </div>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li>
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="bi bi-person-circle me-2"></i> Profil
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-box-arrow-right me-2"></i> Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

