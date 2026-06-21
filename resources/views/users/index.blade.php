@extends('layouts.app')

@section('title', 'Kelola Pengguna - SIEK')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Kelola Pengguna ({{ ucfirst($roleFilter) }})</li>
@endsection

@section('content')
<!-- Role Selector Tabs -->
<div class="row mb-4">
    <div class="col-12">
        <div class="btn-group" role="group" aria-label="Role selector">
            <a href="{{ route('users.index', ['role' => 'peserta']) }}" class="btn px-4 py-2 {{ $roleFilter === 'peserta' ? 'btn-primary' : 'btn-outline-primary bg-white text-primary' }}">
                <i class="bi bi-person-fill me-1"></i> Data Peserta
            </a>
            <a href="{{ route('users.index', ['role' => 'panitia']) }}" class="btn px-4 py-2 {{ $roleFilter === 'panitia' ? 'btn-primary' : 'btn-outline-primary bg-white text-primary' }}">
                <i class="bi bi-people-fill me-1"></i> Data Panitia
            </a>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-secondary">Daftar Pengguna (Role: {{ ucfirst($roleFilter) }})</h5>
        <a href="{{ route('users.create', ['role' => $roleFilter]) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah {{ ucfirst($roleFilter) }}
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3" style="width: 80px;">No</th>
                        <th class="py-3">Nama</th>
                        <th class="py-3">Email</th>
                        <th class="py-3">Tanggal Bergabung</th>
                        <th class="py-3 text-end px-4" style="width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr>
                            <td class="px-4 py-3">{{ $users->firstItem() + $index }}</td>
                            <td class="fw-semibold text-slate-800">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at->format('d M Y, H:i') }} WIB</td>
                            <td class="text-end px-4">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-warning me-2">
                                    <i class="bi bi-pencil-square"></i> 
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> 
                                        </button>
                                    </form>
                                @else
                                    <span class="small text-muted italic">Akun Anda</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Belum ada data user dengan role {{ $roleFilter }}.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
        <div class="card-footer bg-transparent py-3">
            {{ $users->links() }}
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
                text: "User ini akan dihapus secara permanen beserta data riwayatnya!",
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
