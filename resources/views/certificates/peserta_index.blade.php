@extends('layouts.app')

@section('title', 'Sertifikat Saya - SIEK')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Sertifikat Saya</li>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-transparent">
        <h5 class="mb-0 fw-bold text-secondary">Daftar Sertifikat Kelulusan Event Saya</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">No Sertifikat</th>
                        <th class="py-3">Event Kampus</th>
                        <th class="py-3">Tanggal Terbit</th>
                        <th class="py-3 text-end px-4">Download Dokumen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($certificates as $cert)
                        <tr>
                            <td class="px-4 py-3 fw-bold text-primary">{{ $cert->nomor_sertifikat }}</td>
                            <td class="fw-semibold text-slate-800">{{ $cert->registration->event->nama_event ?? '-' }}</td>
                            <td>{{ $cert->tanggal_terbit->format('d M Y') }}</td>
                            <td class="text-end px-4">
                                <a href="{{ route('certificates.download', $cert->id) }}" class="btn btn-sm btn-success">
                                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> Download PDF
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-award fs-2 d-block mb-3 text-muted"></i>
                                Anda belum memiliki sertifikat. Sertifikat diterbitkan setelah Anda menghadiri event yang diikuti.
                            </td>
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
