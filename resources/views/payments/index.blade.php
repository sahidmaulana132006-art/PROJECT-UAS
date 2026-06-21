@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran - SIEK')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Verifikasi Pembayaran</li>
@endsection

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('payments.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="status_verifikasi" class="form-label small fw-semibold">Filter Status Verifikasi</label>
                <select class="form-select" id="status_verifikasi" name="status_verifikasi" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="Pending" {{ request('status_verifikasi') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Verified" {{ request('status_verifikasi') == 'Verified' ? 'selected' : '' }}>Verified (Lunas)</option>
                    <option value="Rejected" {{ request('status_verifikasi') == 'Rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                </select>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-transparent">
        <h5 class="mb-0 fw-bold text-secondary">Data Verifikasi Pembayaran</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Peserta</th>
                        <th class="py-3">Event</th>
                        <th class="py-3">Nominal Transfer</th>
                        <th class="py-3">Bukti</th>
                        <th class="py-3">Waktu Bayar</th>
                        <th class="py-3">Status</th>
                        <th class="py-3 text-end px-4">Verifikasi / Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $pay)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="fw-bold text-slate-800">{{ $pay->registration->user->name ?? '-' }}</div>
                                <small class="text-muted">{{ $pay->registration->user->email ?? '-' }}</small>
                            </td>
                            <td>{{ $pay->registration->event->nama_event ?? '-' }}</td>
                            <td class="fw-bold text-slate-700">Rp {{ number_format($pay->nominal, 0, ',', '.') }}</td>
                            <td>
                                @if($pay->bukti_pembayaran)
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#receiptModal{{ $pay->id }}">
                                        <i class="bi bi-image"></i> Lihat Bukti
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="receiptModal{{ $pay->id }}" tabindex="-1" aria-labelledby="receiptModalLabel{{ $pay->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow">
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold" id="receiptModalLabel{{ $pay->id }}">Bukti Transfer - {{ $pay->registration->user->name ?? '-' }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center bg-light">
                                                    <img src="{{ asset('storage/' . $pay->bukti_pembayaran) }}" class="img-fluid rounded" alt="Bukti Transfer" style="max-height: 500px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $pay->tanggal_bayar ? $pay->tanggal_bayar->format('d M Y, H:i') : '-' }} WIB</td>
                            <td>
                                @if($pay->status_verifikasi === 'Verified')
                                    <span class="badge-success">Verified</span>
                                @elseif($pay->status_verifikasi === 'Pending')
                                    <span class="badge-pending">Pending</span>
                                @else
                                    <span class="badge-danger">Rejected</span>
                                @endif
                            </td>
                            <td class="text-end px-4">
                                <div class="d-flex justify-content-end gap-1">
                                    @if($pay->status_verifikasi !== 'Verified')
                                        <form action="{{ route('payments.verify', $pay->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status_verifikasi" value="Verified">
                                            <button type="submit" class="btn btn-sm btn-success" title="Setujui Pembayaran">
                                                <i class="bi bi-check-lg"></i> 
                                            </button>
                                        </form>
                                    @endif

                                    @if($pay->status_verifikasi !== 'Rejected')
                                        <form action="{{ route('payments.verify', $pay->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status_verifikasi" value="Rejected">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Tolak Pembayaran">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('payments.destroy', $pay->id) }}" method="POST" class="d-inline delete-form ms-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Belum ada data pembayaran yang diupload.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($payments->hasPages())
        <div class="card-footer bg-transparent py-3">
            {{ $payments->links() }}
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
                text: "Data pembayaran ini akan dihapus secara permanen!",
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
