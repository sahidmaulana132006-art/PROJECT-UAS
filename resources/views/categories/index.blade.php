@extends('layouts.app')

@section('title', 'Kategori Event - SIEK')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Kategori Event</li>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-secondary">Daftar Kategori Event</h5>
        <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3" style="width: 80px;">No</th>
                        <th class="py-3" style="width: 250px;">Nama Kategori</th>
                        <th class="py-3">Deskripsi</th>
                        <th class="py-3" style="width: 150px;">Jumlah Event</th>
                        <th class="py-3 text-end px-4" style="width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $index => $category)
                        <tr>
                            <td class="px-4 py-3">{{ $categories->firstItem() + $index }}</td>
                            <td class="fw-semibold">{{ $category->nama_kategori }}</td>
                            <td>{{ $category->deskripsi ?? '-' }}</td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">
                                    {{ $category->events_count }} Event
                                </span>
                            </td>
                            <td class="text-end px-4">
                                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-outline-warning me-2">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Belum ada data kategori event.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($categories->hasPages())
        <div class="card-footer bg-transparent py-3">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    // SweetAlert confirmation for deleting category
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Kategori event akan dihapus secara permanen!",
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
