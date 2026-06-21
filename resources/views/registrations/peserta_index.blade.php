@extends('layouts.app')

@section('title', 'Pendaftaran Saya - SIEK')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Pendaftaran Saya</li>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-transparent">
        <h5 class="mb-0 fw-bold text-secondary">Riwayat Pendaftaran Event Saya</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Event</th>
                        <th class="py-3">Tanggal Daftar</th>
                        <th class="py-3">Status Pendaftaran</th>
                        <th class="py-3">Pembayaran</th>
                        <th class="py-3">Kehadiran</th>
                        <th class="py-3 text-end px-4">Aksi / Dokumen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $reg)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="fw-bold text-slate-800">{{ $reg->event->nama_event ?? '-' }}</div>
                                <small class="text-muted"><i class="bi bi-geo-alt-fill"></i> {{ $reg->event->lokasi ?? '-' }}</small>
                            </td>
                            <td>{{ $reg->tanggal_daftar->format('d M Y, H:i') }} WIB</td>
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
                                    <span class="badge bg-light text-secondary">Gratis</span>
                                @elseif($reg->payment)
                                    @if($reg->payment->status_verifikasi === 'Verified')
                                        <span class="badge bg-success-subtle text-success">Verified (Lunas)</span>
                                    @elseif($reg->payment->status_verifikasi === 'Pending')
                                        <span class="badge bg-warning-subtle text-warning">Menunggu Verifikasi</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger">Ditolak</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">Belum Bayar</span>
                                @endif
                            </td>
                            <td>
                                @if($reg->attendance)
                                    @if($reg->attendance->status_kehadiran === 'Hadir')
                                        <span class="badge bg-success text-white">Hadir</span>
                                    @else
                                        <span class="badge bg-secondary text-white">Tidak Hadir</span>
                                    @endif
                                @else
                                    <span class="badge bg-light text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end px-4">
                                <!-- Upload Payment Button -->
                                @if($reg->event->harga > 0 && (!$reg->payment || $reg->payment->status_verifikasi === 'Rejected'))
                                    <a href="{{ route('payments.upload_form', $reg->id) }}" class="btn btn-sm btn-primary me-1">
                                        <i class="bi bi-credit-card"></i> Bayar
                                    </a>
                                @endif

                                <!-- Download Certificate Button -->
                                @if($reg->status_pendaftaran === 'Diterima' && $reg->attendance && $reg->attendance->status_kehadiran === 'Hadir')
                                    @if($reg->certificate)
                                        <a href="{{ route('certificates.download', $reg->certificate->id) }}" class="btn btn-sm btn-success">
                                            <i class="bi bi-download"></i> Sertifikat
                                        </a>
                                    @else
                                        <span class="small text-muted" title="Sertifikat sedang disiapkan oleh panitia">Sertifikat diproses</span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-3"></i>
                                Anda belum terdaftar pada event apapun.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($registrations->hasPages())
        <div class="card-footer bg-transparent py-3">
            {{ $registrations->links() }}
        </div>
    @endif
</div>
@endsection
