@extends('layouts.app')

@section('title', 'Tambah Event - SIEK')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('events.index') }}">Event Kampus</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
@endsection

@section('content')
<div class="card shadow-sm max-w-4xl">
    <div class="card-header bg-transparent">
        <h5 class="mb-0 fw-bold text-secondary">Tambah Event Kampus Baru</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <!-- Nama Event -->
                <div class="col-md-6 mb-3">
                    <label for="nama_event" class="form-label fw-semibold">Nama Event</label>
                    <input type="text" class="form-control @error('nama_event') is-invalid @enderror" id="nama_event" name="nama_event" value="{{ old('nama_event') }}" required placeholder="Contoh: Seminar Nasional AI 2026">
                    @error('nama_event')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Kategori Event -->
                <div class="col-md-6 mb-3">
                    <label for="event_category_id" class="form-label fw-semibold">Kategori Event</label>
                    <select class="form-select @error('event_category_id') is-invalid @enderror" id="event_category_id" name="event_category_id" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('event_category_id') == $category->id ? 'selected' : '' }}>{{ $category->nama_kategori }}</option>
                        @endforeach
                    </select>
                    @error('event_category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="mb-3">
                <label for="deskripsi" class="form-label fw-semibold">Deskripsi Event</label>
                <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="5" placeholder="Tuliskan deskripsi lengkap, pembicara, agenda, dan persyaratan event...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <!-- Lokasi -->
                <div class="col-md-6 mb-3">
                    <label for="lokasi" class="form-label fw-semibold">Lokasi Pelaksanaan</label>
                    <input type="text" class="form-control @error('lokasi') is-invalid @enderror" id="lokasi" name="lokasi" value="{{ old('lokasi') }}" required placeholder="Contoh: Auditorium Utama Kampus / Online (Zoom)">
                    @error('lokasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Poster -->
                <div class="col-md-6 mb-3">
                    <label for="poster" class="form-label fw-semibold">Poster Event (Opsional)</label>
                    <input type="file" class="form-control @error('poster') is-invalid @enderror" id="poster" name="poster" accept="image/*">
                    <div class="form-text text-muted">Format: JPEG, PNG, JPG, WEBP. Maks: 2MB.</div>
                    @error('poster')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <!-- Tanggal Mulai -->
                <div class="col-md-6 mb-3">
                    <label for="tanggal_mulai" class="form-label fw-semibold">Tanggal Mulai</label>
                    <input type="datetime-local" class="form-control @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required>
                    @error('tanggal_mulai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tanggal Selesai -->
                <div class="col-md-6 mb-3">
                    <label for="tanggal_selesai" class="form-label fw-semibold">Tanggal Selesai</label>
                    <input type="datetime-local" class="form-control @error('tanggal_selesai') is-invalid @enderror" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required>
                    @error('tanggal_selesai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <!-- Kuota -->
                <div class="col-md-4 mb-3">
                    <label for="kuota" class="form-label fw-semibold">Kuota Peserta</label>
                    <input type="number" class="form-control @error('kuota') is-invalid @enderror" id="kuota" name="kuota" value="{{ old('kuota') }}" required min="1" placeholder="Contoh: 100">
                    @error('kuota')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Harga -->
                <div class="col-md-4 mb-3">
                    <label for="harga" class="form-label fw-semibold">Harga Tiket (Rupiah)</label>
                    <input type="number" class="form-control @error('harga') is-invalid @enderror" id="harga" name="harga" value="{{ old('harga', 0) }}" required min="0" placeholder="0 jika Gratis">
                    @error('harga')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status -->
                <div class="col-md-4 mb-4">
                    <label for="status" class="form-label fw-semibold">Status Event</label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Event</button>
            </div>
        </form>
    </div>
</div>
@endsection
