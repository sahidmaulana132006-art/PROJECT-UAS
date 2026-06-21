<x-guest-layout>
    <div class="mb-4 text-sm text-white-50">
        Lupa password Anda? Tidak masalah. Beritahu kami alamat email Anda, dan kami akan mengirimkan link reset password melalui email untuk memilih password yang baru.
    </div>

    <!-- Session Status -->
    @if(session('status'))
        <div class="alert alert-success border-0 text-white bg-success mb-4" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="form-label text-white-50">Email</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Masukkan email Anda">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg">Kirim Link Reset Password</button>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="small">Kembali ke halaman masuk</a>
        </div>
    </form>
</x-guest-layout>
