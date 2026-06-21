@extends('layouts.app')

@section('title', 'Kelola Pendaftaran - SIEK')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Kelola Pendaftaran</li>
@endsection

@section('content')
<!-- Search & Exports -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
            <form action="{{ route('registrations.index') }}" method="GET" class="row g-3 flex-grow-1">
                <div class="col-md-4">
                    <label for="search" class="form-label small fw-semibold">Cari Peserta/Event</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control border-start-0" id="search" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, event...">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <label for="event_id" class="form-label small fw-semibold">Event</label>
                    <select class="form-select" id="event_id" name="event_id">
                        <option value="">Semua Event</option>
                        @foreach($events as $evt)
                            <option value="{{ $evt->id }}" {{ request('event_id') == $evt->id ? 'selected' : '' }}>{{ $evt->nama_event }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="status_pendaftaran" class="form-label small fw-semibold">Status</label>
                    <select class="form-select" id="status_pendaftaran" name="status_pendaftaran">
                        <option value="">Semua Status</option>
                        <option value="Pending" {{ request('status_pendaftaran') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Diterima" {{ request('status_pendaftaran') == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="Ditolak" {{ request('status_pendaftaran') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <div class="w-100 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel-fill"></i></button>
                        <a href="{{ route('registrations.index') }}" class="btn btn-light"><i class="bi bi-arrow-counterclockwise"></i></a>
                    </div>
                </div>
            </form>

            @if(auth()->user()->role === 'admin')
                <div class="d-flex gap-2">
                    <a href="{{ route('reports.registrations.pdf', request()->query()) }}" class="btn btn-outline-danger d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                    </a>
                    <a href="{{ route('reports.registrations.excel', request()->query()) }}" class="btn btn-outline-success d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-excel-fill"></i> Excel
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-transparent">
        <h5 class="mb-0 fw-bold text-secondary">Data Pendaftaran Event</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Peserta</th>
                        <th class="py-3">Event</th>
                        <th class="py-3">Tanggal Daftar</th>
                        <th class="py-3">Pembayaran</th>
                        <th class="py-3">Status</th>
                        @if(auth()->user()->role === 'admin')
                            <th class="py-3 text-end px-4">Verifikasi / Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $reg)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="fw-bold text-slate-800">{{ $reg->user->name ?? '-' }}</div>
                                <small class="text-muted">{{ $reg->user->email ?? '-' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold text-slate-700">{{ $reg->event->nama_event ?? '-' }}</div>
                                <small class="text-muted">Biaya: {{ $reg->event->harga == 0 ? 'Gratis' : 'Rp ' . number_format($reg->event->harga, 0, ',', '.') }}</small>
                            </td>
                            <td>{{ $reg->tanggal_daftar->format('d M Y, H:i') }} WIB</td>
                            <td>
                                @if($reg->event->harga == 0)
                                    <span class="badge bg-light text-secondary">Gratis</span>
                                @elseif($reg->payment)
                                    @if($reg->payment->status_verifikasi === 'Verified')
                                        <span class="badge bg-success-subtle text-success">Lunas (Verified)</span>
                                    @elseif($reg->payment->status_verifikasi === 'Pending')
                                        <span class="badge bg-warning-subtle text-warning">Pending Review</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger">Ditolak</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">Belum Bayar</span>
                                @endif
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
                            @if(auth()->user()->role === 'admin')
                                <td class="text-end px-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <!-- Verify Status Buttons -->
                                        @if($reg->status_pendaftaran !== 'Diterima')
                                            <form action="{{ route('registrations.update_status', $reg->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status_pendaftaran" value="Diterima">
                                                <button type="submit" class="btn btn-sm btn-success" title="Terima">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <!-- <td>
                                            {{ $reg->id }}
                                        </td> -->
                                        
                                        @if($reg->status_pendaftaran !== 'Ditolak')
                                            <form action="{{ route('registrations.update_status', $reg->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status_pendaftaran" value="Ditolak">
                                                <button type="submit" class="btn btn-sm btn-danger" title="Tolak">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('registrations.destroy', $reg->id) }}" method="POST" class="d-inline delete-form ms-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada data pendaftaran event.</td>
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

@section('scripts')
<script>
    // Delete Confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data pendaftaran akan dihapus secara permanen!",
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
