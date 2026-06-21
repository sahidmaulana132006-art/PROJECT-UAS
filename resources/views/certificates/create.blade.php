@extends('layouts.app')

@section('title', 'Terbitkan Sertifikat - SIEK')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('certificates.index') }}">Sertifikat</a></li>
    <li class="breadcrumb-item active" aria-current="page">Terbitkan</li>
@endsection

@section('content')
<div class="row">
    <!-- Step 1: Select Event -->
    <div class="col-md-5 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-transparent">
                <h5 class="mb-0 fw-bold text-secondary">Langkah 1: Pilih Event</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('certificates.create') }}" method="GET" id="event-select-form">
                    <div class="mb-3">
                        <label for="event_select_id" class="form-label fw-semibold">Pilih Event Kampus</label>
                        <select class="form-select" id="event_select_id" name="event_id" onchange="document.getElementById('event-select-form').submit()">
                            <option value="">-- Pilih Event --</option>
                            @foreach($events as $evt)
                                <option value="{{ $evt->id }}" {{ $selectedEventId == $evt->id ? 'selected' : '' }}>{{ $evt->nama_event }}</option>
                            @endforeach
                        </select>
                        <div class="form-text text-muted">Daftar peserta yang hadir akan disaring berdasarkan event yang dipilih.</div>
                    </div>
                </form>

                @if($selectedEventId)
                    <div class="alert alert-info mb-0" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Ditemukan <strong>{{ count($eligibleRegistrations) }}</strong> peserta yang berhak mendapatkan sertifikat (Hadir dan Belum memiliki sertifikat).
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Step 2: Issue Certificate Form -->
    <div class="col-md-7 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-transparent">
                <h5 class="mb-0 fw-bold text-secondary">Langkah 2: Isi Data Sertifikat</h5>
            </div>
            <div class="card-body">
                @if(!$selectedEventId)
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-arrow-left-circle fs-1 mb-3"></i>
                        <p>Silakan pilih event di sebelah kiri terlebih dahulu.</p>
                    </div>
                @elseif(count($eligibleRegistrations) == 0)
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-person-x fs-1 mb-3"></i>
                        <p>Tidak ada peserta yang berhak mendapatkan sertifikat pada event ini.<br>Pastikan absensi peserta sudah diset 'Hadir'.</p>
                    </div>
                @else
                    <form action="{{ route('certificates.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Participant Dropdown -->
                        <div class="mb-3">
                            <label for="registration_id" class="form-label fw-semibold">Pilih Peserta Event</label>
                            <select class="form-select @error('registration_id') is-invalid @enderror" id="registration_id" name="registration_id" required>
                                <option value="">-- Pilih Peserta --</option>
                                @foreach($eligibleRegistrations as $reg)
                                    <option value="{{ $reg->id }}" {{ old('registration_id') == $reg->id ? 'selected' : '' }}>{{ $reg->user->name }} ({{ $reg->user->email }})</option>
                                @endforeach
                            </select>
                            @error('registration_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Certificate Number -->
                        <div class="mb-3">
                            <label for="nomor_sertifikat" class="form-label fw-semibold">Nomor Sertifikat</label>
                            @php
                                $suggestedNum = 'SIEK/CERT/' . date('Y') . '/' . mt_rand(1000, 9999);
                            @endphp
                            <input type="text" class="form-control @error('nomor_sertifikat') is-invalid @enderror" id="nomor_sertifikat" name="nomor_sertifikat" value="{{ old('nomor_sertifikat', $suggestedNum) }}" required placeholder="Contoh: SIEK/CERT/2026/0001">
                            <div class="form-text text-muted">Nomor sertifikat harus unik. Sistem telah menyarankan nomor acak di atas.</div>
                            @error('nomor_sertifikat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- PDF Upload -->
                        <div class="mb-4">
                            <label for="file_sertifikat" class="form-label fw-semibold">Upload File Sertifikat (PDF)</label>
                            <input type="file" class="form-control @error('file_sertifikat') is-invalid @enderror" id="file_sertifikat" name="file_sertifikat" accept="application/pdf" required>
                            <div class="form-text text-muted">Format yang didukung: PDF. Ukuran Maksimal: 5MB.</div>
                            @error('file_sertifikat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('certificates.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Terbitkan Sertifikat</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
