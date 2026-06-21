@extends('layouts.app')

@section('title', 'SIEK')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Profil Saya</li>
@endsection

@section('content')
<div class="row">
    <!-- Update Profile Info -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-transparent">
                <h5 class="mb-0 fw-bold text-secondary">Informasi Profil</h5>
                <small class="text-muted">Perbarui nama lengkap dan alamat email akun Anda.</small>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <div class="mb-3">
                        <label for="profile_name" class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="profile_name" name="name" value="{{ old('name', auth()->user()->name) }}" required autocomplete="name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="profile_email" class="form-label fw-semibold">Alamat Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="profile_email" name="email" value="{{ old('email', auth()->user()->email) }}" required autocomplete="email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Update Password -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-transparent">
                <h5 class="mb-0 fw-bold text-secondary">Ubah Password</h5>
                <small class="text-muted">Pastikan akun Anda menggunakan password acak yang aman.</small>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-semibold">Password Saat Ini</label>
                        <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" id="current_password" name="current_password" autocomplete="current-password">
                        @error('current_password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label fw-semibold">Password Baru</label>
                        <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" id="new_password" name="password" autocomplete="new-password">
                        @error('password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                        @error('password_confirmation', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Perbarui Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
