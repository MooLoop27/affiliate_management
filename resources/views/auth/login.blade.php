<x-guest-layout>
    @if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Enter your email">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password" placeholder="Enter your password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="remember" id="remember_me" class="form-check-input" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember_me">Remember me</label>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-0">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-decoration-none small">
                    Forgot your password?
                </a>
            @endif
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-box-arrow-in-right me-1"></i> Log in
            </button>
        </div>
    </form>

    <div class="text-center mt-3 pt-3 border-top">
        <p class="small text-muted mb-1">Demo Accounts:</p>
        <div class="d-flex gap-2 justify-content-center flex-wrap">
            <span class="badge bg-danger">owner@example.com</span>
            <span class="badge bg-primary">admin@example.com</span>
            <span class="badge bg-info text-white">finance@example.com</span>
        </div>
        <p class="small text-muted mt-1">Password: <code>password</code></p>
    </div>
</x-guest-layout>

