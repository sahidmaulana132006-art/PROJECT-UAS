@extends('layouts.app')

@section('title', 'Panitia Dashboard - SIEK')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Panitia Dashboard</li>
@endsection

@section('content')
<div class="row">
    <!-- Card 1: Total Event -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-start border-primary border-4 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1" style="font-size: 0.8rem;">Total Event</div>
                        <div class="h3 mb-0 fw-bold text-slate-800">{{ $totalEvent }}</div>
                    </div>
                    <div class="bg-primary-subtle text-primary p-3 rounded-circle">
                        <i class="bi bi-calendar-event-fill fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Total Peserta Terdaftar -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-start border-success border-4 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1" style="font-size: 0.8rem;">Peserta Terdaftar</div>
                        <div class="h3 mb-0 fw-bold text-slate-800">{{ $myRegistrationsCount }}</div>
                    </div>
                    <div class="bg-success-subtle text-success p-3 rounded-circle">
                        <i class="bi bi-person-check-fill fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Total Hadir -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-start border-info border-4 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1" style="font-size: 0.8rem;">Total Kehadiran</div>
                        <div class="h3 mb-0 fw-bold text-slate-800">{{ $totalHadir }}</div>
                    </div>
                    <div class="bg-info-subtle text-info p-3 rounded-circle">
                        <i class="bi bi-qr-code-scan fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-secondary">Event Terbaru</h5>
                <a href="{{ route('events.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Nama Event</th>
                                <th class="py-3">Lokasi</th>
                                <th class="py-3">Tanggal Mulai</th>
                                <th class="py-3">Pendaftar</th>
                                <th class="py-3 text-end px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $event)
                                <tr>
                                    <td class="px-4 py-3 fw-semibold">{{ $event->nama_event }}</td>
                                    <td>{{ $event->lokasi }}</td>
                                    <td>{{ $event->tanggal_mulai->format('d M Y, H:i') }} WIB</td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary fw-bold" style="padding: 0.4rem 0.8rem; border-radius: 50rem;">
                                            {{ $event->registrations_count }} Peserta
                                        </span>
                                    </td>
                                    <td class="text-end px-4">
                                        <a href="{{ route('attendances.index', ['event_id' => $event->id]) }}" class="btn btn-sm btn-outline-primary me-2">
                                            <i class="bi bi-qr-code-scan"></i> Absensi
                                        </a>
                                        <a href="{{ route('registrations.index', ['event_id' => $event->id]) }}" class="btn btn-sm btn-light">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">Belum ada data event.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
