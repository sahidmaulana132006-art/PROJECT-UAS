@extends('layouts.app')

@section('title', 'Kelola Sertifikat - SIEK')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Kelola Sertifikat</li>
@endsection

@section('content')
<!-- Filter & Upload Button -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
            <form action="{{ route('certificates.index') }}" method="GET" class="row g-3 flex-grow-1">
                <div class="col-md-6">
                    <label for="event_id" class="form-label small fw-semibold">Filter Berdasarkan Event</label>
                    <select class="form-select" id="event_id" name="event_id" onchange="this.form.submit()">
                        <option value="">Semua Event</option>
                        @foreach($events as $evt)
                            <option value="{{ $evt->id }}" {{ request('event_id') == $evt->id ? 'selected' : '' }}>{{ $evt->nama_event }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            <a href="{{ route('certificates.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-file-earmark-arrow-up"></i> Upload Sertifikat
            </a>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-transparent">
        <h5 class="mb-0 fw-bold text-secondary">Data Sertifikat Terbit</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">No Sertifikat</th>
                        <th class="py-3">Nama Peserta</th>
                        <th class="py-3">Event</th>
                        <th class="py-3">Tanggal Terbit</th>
                        <th class="py-3 text-end px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($certificates as $cert)
                        <tr>
                            <td class="px-4 py-3 fw-bold text-primary">{{ $cert->nomor_sertifikat }}</td>
                            <td>
                                <div class="fw-semibold text-slate-800">{{ $cert->registration->user->name ?? '-' }}</div>
                                <small class="text-muted">{{ $cert->registration->user->email ?? '-' }}</small>
                            </td>
                            <td>{{ $cert->registration->event->nama_event ?? '-' }}</td>
                            <td>{{ $cert->tanggal_terbit->format('d M Y') }}</td>
                            <td class="text-end px-4">
                                <a href="{{ route('certificates.download', $cert->id) }}" class="btn btn-sm btn-outline-success me-2">
                                    <i class="bi bi-download"></i> Unduh PDF
                                </a>
                                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'panitia')
                                    <form action="{{ route('certificates.destroy', $cert->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Belum ada data sertifikat yang diterbitkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($certificates->hasPages())
        <div class="card-footer bg-transparent py-3">
            {{ $certificates->links() }}
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
                text: "Sertifikat ini akan dihapus secara permanen dari server!",
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
