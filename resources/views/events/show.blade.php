@extends('layouts.app')

@section('title', $event->nama_event . ' - SIEK')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('events.index') }}">Event Kampus</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
@endsection

@section('content')
<div class="row">
    <!-- Left Column: Poster & Details -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm overflow-hidden">
            @if($event->poster)
                <img src="{{ asset('storage/' . $event->poster) }}" class="img-fluid w-100" alt="poster" style="max-height: 400px; object-fit: cover;">
            @else
                <div class="bg-primary-subtle text-primary d-flex flex-column align-items-center justify-content-center py-5" style="min-height: 250px;">
                    <i class="bi bi-image fs-1 mb-2"></i>
                    <span class="small fw-semibold text-uppercase">No Poster Available</span>
                </div>
            @endif

            <div class="card-body p-4 p-md-5">
                <div class="d-flex align-items-center mb-3">
                    <span class="badge bg-primary py-2 px-3 fw-semibold rounded-pill me-2">
                        {{ $event->category->nama_kategori }}
                    </span>
                    <span class="badge bg-light text-secondary border py-2 px-3 fw-semibold rounded-pill">
                        Status: {{ $event->status }}
                    </span>
                </div>

                <h1 class="fw-bold text-slate-800 mb-4">{{ $event->nama_event }}</h1>
                
                <h5 class="fw-bold text-slate-700 mb-3">Deskripsi Event</h5>
                <div class="text-slate-600 mb-4" style="line-height: 1.7; white-space: pre-line;">
                    {{ $event->deskripsi ?? 'Tidak ada deskripsi untuk event ini.' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Registration Card & Sidebar info -->
    <div class="col-lg-4 mb-4">
        <!-- Ticket Registration Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-transparent text-center py-4">
                <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.8rem; letter-spacing: 0.5px;">Harga Tiket</h6>
                <h2 class="fw-extrabold text-primary mb-0">
                    {{ $event->harga == 0 ? 'Gratis' : 'Rp ' . number_format($event->harga, 0, ',', '.') }}
                </h2>
            </div>
            <div class="card-body p-4">
                <div class="d-flex flex-column gap-3 mb-4">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-geo-alt-fill text-danger fs-5 me-3 mt-1"></i>
                        <div>
                            <div class="fw-bold text-slate-800">Lokasi</div>
                            <div class="text-muted small">{{ $event->lokasi }}</div>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start">
                        <i class="bi bi-clock-fill text-primary fs-5 me-3 mt-1"></i>
                        <div>
                            <div class="fw-bold text-slate-800">Waktu Mulai</div>
                            <div class="text-muted small">{{ $event->tanggal_mulai->format('d M Y, H:i') }} WIB</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-start">
                        <i class="bi bi-calendar2-x-fill text-warning fs-5 me-3 mt-1"></i>
                        <div>
                            <div class="fw-bold text-slate-800">Waktu Selesai</div>
                            <div class="text-muted small">{{ $event->tanggal_selesai->format('d M Y, H:i') }} WIB</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-start">
                        <i class="bi bi-people-fill text-success fs-5 me-3 mt-1"></i>
                        <div>
                            <div class="fw-bold text-slate-800">Kuota Terisi</div>
                            <div class="text-muted small">
                                {{ $event->registrations()->where('status_pendaftaran', 'Diterima')->count() }} dari {{ $event->kuota }} Kursi
                            </div>
                        </div>
                    </div>
                </div>

                @guest
                    <div class="d-grid">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Masuk untuk Mendaftar</a>
                    </div>
                @else
                    @if(auth()->user()->role === 'peserta')
                        @if($hasRegistered)
                            <div class="alert alert-info text-center mb-0" role="alert">
                                <strong>Anda sudah terdaftar!</strong><br>
                                Status Pendaftaran: 
                                <span class="fw-bold text-uppercase">
                                    {{ $registrationStatus }}
                                </span>
                                <div class="mt-2">
                                    <a href="{{ route('registrations.index') }}" class="btn btn-sm btn-outline-primary w-100">
                                        Lihat Status Pendaftaran
                                    </a>
                                </div>
                            </div>
                        @else
                            @if($event->status !== 'Active')
                                <button class="btn btn-secondary btn-lg w-100" disabled>Pendaftaran Ditutup</button>
                            @elseif($event->registrations()->where('status_pendaftaran', '!=', 'Ditolak')->count() >= $event->kuota)
                                <button class="btn btn-danger btn-lg w-100" disabled>Kuota Penuh</button>
                            @else
                                <form action="{{ route('registrations.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                                    <button type="submit" class="btn btn-primary btn-lg w-100">Daftar Event</button>
                                </form>
                            @endif
                        @endif
                    @else
                        <div class="alert alert-secondary text-center mb-0" role="alert">
                            <i class="bi bi-info-circle me-1"></i> Anda login sebagai <strong>{{ auth()->user()->role }}</strong>.
                        </div>
                    @endif
                @endguest
            </div>
        </div>
    </div>
</div>
@endsection
