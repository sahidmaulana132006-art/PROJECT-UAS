@extends('layouts.app')

@section('title', 'Edit Pengguna - SIEK')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index', ['role' => $roleFilter]) }}">Kelola Pengguna</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
<div class="card shadow-sm max-w-2xl">
    <div class="card-header bg-transparent">
        <h5 class="mb-0 fw-bold text-secondary">Edit Pengguna (Role: {{ ucfirst($roleFilter) }})</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required placeholder="Contoh: Budi Santoso">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Alamat Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required placeholder="Contoh: budi@gmail.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="role" class="form-label fw-semibold">Role Pengguna</label>
                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                    <option value="peserta" {{ old('role', $user->role) === 'peserta' ? 'selected' : '' }}>Peserta</option>
                    <option value="panitia" {{ old('role', $user->role) === 'panitia' ? 'selected' : '' }}>Panitia</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="p-3 bg-light rounded-3 mb-4">
                <h6 class="fw-bold text-secondary mb-2"><i class="bi bi-shield-lock-fill text-warning me-2"></i>Ubah Password (Opsional)</h6>
                <p class="text-muted small mb-3">Kosongkan kolom di bawah ini jika Anda tidak ingin memperbarui password user ini.</p>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label fw-semibold">Password Baru</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Min. 8 karakter">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru">
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('users.index', ['role' => $roleFilter]) }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection
