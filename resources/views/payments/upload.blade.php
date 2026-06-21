@extends('layouts.app')

@section('title', 'Upload Bukti Pembayaran - SIEK')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('registrations.index') }}">Pendaftaran Saya</a></li>
    <li class="breadcrumb-item active" aria-current="page">Upload Pembayaran</li>
@endsection

@section('content')
<div class="row">
    <!-- Left Column: Instructions -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-transparent">
                <h5 class="mb-0 fw-bold text-secondary">Instruksi Pembayaran</h5>
            </div>
            <div class="card-body">
                <p class="text-slate-600">Untuk menyelesaikan pendaftaran event <strong>{{ $registration->event->nama_event }}</strong>, harap lakukan transfer pembayaran sesuai rincian berikut:</p>
                
                <div class="p-3 bg-light rounded-3 mb-4">
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Nama Event:</div>
                        <div class="col-7 fw-bold text-slate-800">{{ $registration->event->nama_event }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted">Biaya Pendaftaran:</div>
                        <div class="col-7 fw-bold text-primary fs-5">Rp {{ number_format($registration->event->harga, 0, ',', '.') }}</div>
                    </div>
                </div>

                <h6 class="fw-bold text-slate-700 mb-3"><i class="bi bi-bank text-primary me-2"></i>Rekening Bank Kampus</h6>
                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item px-0 bg-transparent">
                        <div class="fw-semibold text-slate-800">Bank Mandiri</div>
                        <div class="text-muted">No. Rekening: <span class="fw-bold text-slate-700">123-456-789-0</span></div>
                        <div class="text-muted">Atas Nama: <span class="fw-semibold text-slate-700">SIEK Universitas Utama</span></div>
                    </li>
                    <li class="list-group-item px-0 bg-transparent">
                        <div class="fw-semibold text-slate-800">Bank BCA</div>
                        <div class="text-muted">No. Rekening: <span class="fw-bold text-slate-700">987-654-321-0</span></div>
                        <div class="text-muted">Atas Nama: <span class="fw-semibold text-slate-700">SIEK Universitas Utama</span></div>
                    </li>
                </ul>

                <div class="alert alert-warning mb-0" role="alert" style="font-size: 0.9rem;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Pastikan nominal transfer sesuai dengan harga tiket event. Proses verifikasi pembayaran memerlukan waktu maksimal 1x24 jam.
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Upload Form -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-transparent">
                <h5 class="mb-0 fw-bold text-secondary">Upload Bukti Transfer</h5>
            </div>
            <div class="card-body">
                @if($payment && $payment->status_verifikasi === 'Rejected')
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-x-circle-fill me-2"></i>
                        <strong>Pembayaran Anda Sebelumnya Ditolak.</strong> Harap periksa bukti transfer Anda dan upload kembali bukti yang valid.
                    </div>
                @endif

                <form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="registration_id" value="{{ $registration->id }}">
                    <input type="hidden" name="nominal" value="{{ $registration->event->harga }}">

                    <div class="mb-4">
                        <label for="bukti_pembayaran" class="form-label fw-semibold">File Bukti Pembayaran (Gambar)</label>
                        <input type="file" class="form-control @error('bukti_pembayaran') is-invalid @enderror" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*" required>
                        <div class="form-text text-muted">Format: JPG, JPEG, PNG, WEBP. Ukuran Maksimal: 2MB.</div>
                        @error('bukti_pembayaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($payment && $payment->bukti_pembayaran)
                        <div class="mb-4">
                            <span class="small d-block mb-1 text-muted">Bukti yang telah diupload sebelumnya:</span>
                            <img src="{{ asset('storage/' . $payment->bukti_pembayaran) }}" alt="Bukti Transfer" class="img-thumbnail" style="max-height: 200px;">
                        </div>
                    @endif

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Kirim Bukti Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
