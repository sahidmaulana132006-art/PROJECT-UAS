<x-guest-layout>
    <!-- Session Status -->
    @if(session('status'))
        <div class="alert alert-success border-0 text-white bg-success mb-4" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label text-white-50">Email</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Masukkan email Anda">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label text-white-50">Password</label>
            <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password Anda">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-4 form-check d-flex justify-content-between align-items-center">
            <div>
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember" style="background-color: rgba(15,23,42,0.6); border: 1px solid rgba(255,255,255,0.2);">
                <label for="remember_me" class="form-check-label text-muted" style="font-size: 0.9rem;">Ingat Saya</label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="font-size: 0.9rem;">Lupa password?</a>
            @endif
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg">Masuk</button>
        </div>

        <div class="text-center mt-4">
            <span class="text-muted small">Belum punya akun? <a href="{{ route('register') }}">Daftar Peserta</a></span>
        </div>
    </form>
</x-guest-layout>
