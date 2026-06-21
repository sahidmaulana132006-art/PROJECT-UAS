@extends('layouts.app')

@section('title', 'Event Kampus')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Daftar Event</li>
@endsection

@section('content')
<!-- Search & Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('events.index') }}" method="GET" class="row g-3">
            <div class="col-md-5">
                <label for="search" class="form-label small fw-semibold">Cari Event</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-start-0" id="search" name="search" value="{{ request('search') }}" placeholder="Cari nama, lokasi, deskripsi...">
                </div>
            </div>
            
            <div class="col-md-4">
                <label for="category_id" class="form-label small fw-semibold">Kategori</label>
                <select class="form-select" id="category_id" name="category_id">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 d-flex align-items-end">
                <div class="w-100 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel-fill"></i> Filter</button>
                    <a href="{{ route('events.index') }}" class="btn btn-light" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Event Grid -->
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-4">
    @forelse($events as $event)
        <div class="col">
            <div class="card h-100 shadow-sm border-0 position-relative" style="transition: transform 0.2s; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                
                <!-- Category Badge -->
                <span class="position-absolute top-0 start-0 m-3 badge bg-dark text-white py-2 px-3 fw-semibold" style="z-index: 10; border-radius: 50rem;">
                    {{ $event->category->nama_kategori }}
                </span>

                <!-- Event Poster -->
                @if($event->poster)
                    <img src="{{ asset('storage/' . $event->poster) }}" class="card-img-top" alt="poster" style="height: 200px; object-fit: cover; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                @else
                    <div class="card-img-top bg-primary-subtle text-primary d-flex flex-column align-items-center justify-content-center" style="height: 200px; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                        <i class="bi bi-calendar-event fs-1 mb-2"></i>
                        <span class="small fw-semibold text-uppercase">No Poster</span>
                    </div>
                @endif

                <div class="card-body d-flex flex-column p-4">
                    <h5 class="card-title fw-bold text-slate-800 mb-2">{{ $event->nama_event }}</h5>
                    <p class="card-text text-muted mb-4 flex-grow-1" style="font-size: 0.9rem; line-height: 1.5;">
                        {{ Str::limit($event->deskripsi, 120, '...') }}
                    </p>
                    
                    <hr class="my-3 text-slate-100">
                    
                    <!-- Metadata Info -->
                    <div class="d-flex flex-column gap-2 mb-3" style="font-size: 0.85rem;">
                        <div class="d-flex align-items-center text-muted">
                            <i class="bi bi-geo-alt-fill text-danger me-2"></i>
                            <span>{{ $event->lokasi }}</span>
                        </div>
                        <div class="d-flex align-items-center text-muted">
                            <i class="bi bi-calendar3 text-primary me-2"></i>
                            <span>{{ $event->tanggal_mulai->format('d M Y, H:i') }} WIB</span>
                        </div>
                        <div class="d-flex align-items-center text-muted">
                            <i class="bi bi-people-fill text-success me-2"></i>
                            <span>Sisa Kuota: {{ $event->kuota - $event->registrations()->where('status_pendaftaran', 'Diterima')->count() }} dari {{ $event->kuota }}</span>
                        </div>
                    </div>

                    <!-- Footer Pricing & Action -->
                    <div class="d-flex align-items-center justify-content-between mt-auto">
                        <span class="fs-5 fw-extrabold text-primary">
                            {{ $event->harga == 0 ? 'Gratis' : 'Rp ' . number_format($event->harga, 0, ',', '.') }}
                        </span>
                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-sm btn-primary py-2 px-3">
                            Detail <i class="bi bi-arrow-right-short ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5 text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
            Tidak ada event yang ditemukan.
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($events->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $events->links() }}
</div>
@endif
@endsection
