@extends('layouts.app')

@section('title', 'Kelola Event - SIEK')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Kelola Event</li>
@endsection

@section('content')
<!-- Search & Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('events.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label small fw-semibold">Cari Event</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-start-0" id="search" name="search" value="{{ request('search') }}" placeholder="Cari nama, lokasi, deskripsi...">
                </div>
            </div>
            
            <div class="col-md-3">
                <label for="category_id" class="form-label small fw-semibold">Kategori</label>
                <select class="form-select" id="category_id" name="category_id">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="status" class="form-label small fw-semibold">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Semua Status</option>
                    <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <div class="w-100 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel-fill"></i> Filter</button>
                    <a href="{{ route('events.index') }}" class="btn btn-light" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-secondary">Daftar Event Kampus</h5>
        <a href="{{ route('events.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Event
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Event</th>
                        <th class="py-3">Kategori</th>
                        <th class="py-3">Waktu Pelaksanaan</th>
                        <th class="py-3">Kuota</th>
                        <th class="py-3">Harga</th>
                        <th class="py-3">Status</th>
                        <th class="py-3 text-end px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    @if($event->poster)
                                        <img src="{{ asset($event->poster) }}" alt="poster" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="rounded bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                            <i class="bi bi-image fs-4"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold text-slate-800">{{ $event->nama_event }}</div>
                                        <small class="text-muted"><i class="bi bi-geo-alt-fill"></i> {{ $event->lokasi }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $event->category->nama_kategori }}</td>
                            <td>
                                <small class="d-block fw-semibold text-slate-700">Mulai: {{ $event->tanggal_mulai->format('d M Y, H:i') }}</small>
                                <small class="d-block text-muted">Selesai: {{ $event->tanggal_selesai->format('d M Y, H:i') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill">
                                    {{ $event->registrations()->where('status_pendaftaran', 'Diterima')->count() }} / {{ $event->kuota }}
                                </span>
                            </td>
                            <td class="fw-bold text-slate-700">
                                {{ $event->harga == 0 ? 'Gratis' : 'Rp ' . number_format($event->harga, 0, ',', '.') }}
                            </td>
                            <td>
                                @if($event->status === 'Active')
                                    <span class="badge-success">Active</span>
                                @elseif($event->status === 'Inactive')
                                    <span class="badge-pending">Inactive</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary py-1 px-3 rounded-pill fw-semibold" style="font-size: 0.8rem;">Completed</span>
                                @endif
                            </td>
                            <td class="text-end px-4">
                                <a href="{{ route('events.show', $event->id) }}" class="btn btn-sm btn-outline-info me-1" title="Lihat">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('events.edit', $event->id) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Belum ada data event.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($events->hasPages())
        <div class="card-footer bg-transparent py-3">
            {{ $events->links() }}
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    // Delete Confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Event akan dihapus secara permanen beserta datanya!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#0F172A',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
