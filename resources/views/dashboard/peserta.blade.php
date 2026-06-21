@extends('layouts.app')

@section('title', 'Dashboard - SIEK')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Peserta Dashboard</li>
@endsection

@section('content')
<div class="row">
    <!-- Card 1: Terdaftar -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-start border-primary border-4 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1" style="font-size: 0.8rem;">Total Terdaftar</div>
                        <div class="h3 mb-0 fw-bold text-slate-800">{{ $totalRegistered }}</div>
                    </div>
                    <div class="bg-primary-subtle text-primary p-3 rounded-circle">
                        <i class="bi bi-calendar2-check-fill fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Diterima -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-start border-success border-4 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1" style="font-size: 0.8rem;">Pendaftaran Diterima</div>
                        <div class="h3 mb-0 fw-bold text-slate-800">{{ $totalAccepted }}</div>
                    </div>
                    <div class="bg-success-subtle text-success p-3 rounded-circle">
                        <i class="bi bi-patch-check-fill fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Pending -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-start border-warning border-4 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1" style="font-size: 0.8rem;">Menunggu Verifikasi</div>
                        <div class="h3 mb-0 fw-bold text-slate-800">{{ $totalPending }}</div>
                    </div>
                    <div class="bg-warning-subtle text-warning p-3 rounded-circle">
                        <i class="bi bi-clock-history fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Pendaftaran Saya -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-transparent">
                <h5 class="mb-0 fw-bold text-secondary">Status Pendaftaran Saya</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Nama Event</th>
                                <th class="py-3">Status Pendaftaran</th>
                                <th class="py-3">Status Pembayaran</th>
                                <th class="py-3 text-end px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($myRegistrations as $reg)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="fw-semibold text-slate-800">{{ $reg->event->nama_event }}</div>
                                        <small class="text-muted"><i class="bi bi-geo-alt-fill me-1"></i> {{ $reg->event->lokasi }}</small>
                                    </td>
                                    <td>
                                        @if($reg->status_pendaftaran === 'Diterima')
                                            <span class="badge-success">Diterima</span>
                                        @elseif($reg->status_pendaftaran === 'Pending')
                                            <span class="badge-pending">Pending</span>
                                        @else
                                            <span class="badge-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($reg->event->harga == 0)
                                            <span class="badge bg-light text-secondary fw-semibold">Gratis</span>
                                        @elseif($reg->payment)
                                            @if($reg->payment->status_verifikasi === 'Verified')
                                                <span class="badge bg-success-subtle text-success fw-semibold">Lunas (Verified)</span>
                                            @elseif($reg->payment->status_verifikasi === 'Pending')
                                                <span class="badge bg-warning-subtle text-warning fw-semibold">Menunggu Verifikasi</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger fw-semibold">Ditolak</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary fw-semibold">Belum Bayar</span>
                                        @endif
                                    </td>
                                    <td class="text-end px-4">
                                        @if($reg->event->harga > 0 && (!$reg->payment || $reg->payment->status_verifikasi === 'Rejected'))
                                            <a href="{{ route('payments.upload_form', $reg->id) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-upload"></i> Bayar
                                            </a>
                                        @endif
                                        
                                        @if($reg->status_pendaftaran === 'Diterima' && $reg->attendance && $reg->attendance->status_kehadiran === 'Hadir' && $reg->certificate)
                                            <a href="{{ route('certificates.download', $reg->certificate->id) }}" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-download"></i> Sertifikat
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-3"></i>
                                        Anda belum mendaftar di event manapun.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Mendatang -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-secondary">Event Rekomendasi</h5>
                <a href="{{ route('events.index') }}" class="btn btn-sm btn-light">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column gap-3">
                    @forelse($upcomingEvents as $evt)
                        <div class="p-3 bg-light rounded-3 d-flex flex-column gap-2">
                            <h6 class="mb-0 fw-bold text-slate-800 text-truncate">{{ $evt->nama_event }}</h6>
                            <div class="d-flex justify-content-between text-muted" style="font-size: 0.85rem;">
                                <span><i class="bi bi-clock-fill me-1"></i> {{ $evt->tanggal_mulai->format('d M Y') }}</span>
                                <span class="fw-semibold text-primary">
                                    {{ $evt->harga == 0 ? 'Gratis' : 'Rp ' . number_format($evt->harga, 0, ',', '.') }}
                                </span>
                            </div>
                            <form action="{{ route('registrations.store') }}" method="POST" class="mt-1">
                                @csrf
                                <input type="hidden" name="event_id" value="{{ $evt->id }}">
                                <button type="submit" class="btn btn-sm btn-outline-primary w-100">Daftar Sekarang</button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">Belum ada event baru.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
