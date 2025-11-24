@extends('layouts.app')

@section('title', 'Data Role')
@section('page-title', 'Manajemen Data Role')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Data Role</h3>
            <p>Kelola semua data role dalam sistem</p>
        </div>
        <a href="{{ route('role.create') }}" class="btn btn-primary">
            <i class='bx bx-plus'></i> Tambah Role
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class='bx bx-list-ul'></i> Daftar Role</span>
        <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari role..." style="width: 250px;">
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th style="width: 100px;">ID Role</th>
                        <th>Nama Role</th>
                        <th style="width: 150px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($role as $index => $r)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="badge bg-secondary">{{ $r->idrole }}</span></td>
                        <td class="fw-semibold">{{ $r->nama_role }}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('role.edit', $r->idrole) }}" class="btn btn-outline-warning" title="Edit">
                                    <i class='bx bx-edit'></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" onclick="confirmDelete('{{ $r->idrole }}', '{{ $r->nama_role }}')" title="Hapus">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class='bx bx-user-x' style="font-size: 48px;"></i>
                            <p class="mt-2">Belum ada data role</p>
                            <a href="{{ route('role.create') }}" class="btn btn-sm btn-primary">
                                <i class='bx bx-plus'></i> Tambah Role Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(count($role) > 0)
    <div class="card-footer text-muted">
        Menampilkan {{ count($role) }} data role
    </div>
    @endif
</div>

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    let filter = this.value.toUpperCase();
    let rows = document.querySelectorAll('#tableBody tr');

    rows.forEach(row => {
        let text = row.textContent || row.innerText;
        row.style.display = text.toUpperCase().indexOf(filter) > -1 ? '' : 'none';
    });
});

function confirmDelete(id, nama) {
    Swal.fire({
        title: 'Hapus Role?',
        text: `Yakin ingin menghapus "${nama}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74a3b',
        cancelButtonColor: '#858796',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.getElementById('deleteForm');
            form.action = `/role/${id}`;
            form.submit();
        }
    });
}
</script>
@endpush
