@extends('layouts.app')

@section('title', 'Absensi Peserta - SIEK')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Absensi Peserta</li>
@endsection

@section('content')
<!-- Filter Event -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('attendances.index') }}" method="GET" class="row g-3">
            <div class="col-md-6">
                <label for="event_id" class="form-label small fw-semibold">Pilih Event Kampus</label>
                <select class="form-select" id="event_id" name="event_id" onchange="this.form.submit()">
                    <option value="">-- Pilih Event --</option>
                    @foreach($events as $evt)
                        <option value="{{ $evt->id }}" {{ $selectedEventId == $evt->id ? 'selected' : '' }}>{{ $evt->nama_event }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-transparent">
        <h5 class="mb-0 fw-bold text-secondary">Data Absensi Kehadiran</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3" style="width: 80px;">No</th>
                        <th class="py-3">Nama Peserta</th>
                        <th class="py-3">Email</th>
                        <th class="py-3">Waktu Absen</th>
                        <th class="py-3">Status Kehadiran</th>
                        <th class="py-3 text-end px-4">Tandai Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $index => $att)
                        <tr>
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="fw-bold text-slate-800">{{ $att->registration->user->name ?? '-' }}</td>
                            <td>{{ $att->registration->user->email ?? '-' }}</td>
                            <td>
                                @if($att->waktu_absen)
                                    <span class="text-slate-700 fw-medium"><i class="bi bi-clock me-1 text-primary"></i> {{ $att->waktu_absen->format('d-m-Y H:i') }} WIB</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($att->status_kehadiran === 'Hadir')
                                    <span class="badge bg-success px-3 py-2 rounded-pill fw-semibold"><i class="bi bi-check-circle-fill me-1"></i> Hadir</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-2 rounded-pill fw-semibold"><i class="bi bi-x-circle-fill me-1"></i> Tidak Hadir</span>
                                @endif
                            </td>
                            <td class="text-end px-4">
                                @if($att->status_kehadiran !== 'Hadir')
                                    <form action="{{ route('attendances.record') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="registration_id" value="{{ $att->registration_id }}">
                                        <input type="hidden" name="status_kehadiran" value="Hadir">
                                        <button type="submit" class="btn btn-sm btn-success px-3">
                                            <i class="bi bi-check-lg me-1"></i> Set Hadir
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('attendances.record') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="registration_id" value="{{ $att->registration_id }}">
                                        <input type="hidden" name="status_kehadiran" value="Tidak Hadir">
                                        <button type="submit" class="btn btn-sm btn-outline-danger px-3">
                                            <i class="bi bi-x-lg me-1"></i> Batalkan Hadir
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                @if($selectedEventId)
                                    Belum ada peserta yang pendaftarannya berstatus 'Diterima' untuk event ini.
                                @else
                                    Pilih event terlebih dahulu untuk menampilkan daftar absensi.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
