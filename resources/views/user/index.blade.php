@extends('layouts.app')

@section('title', 'Data User')
@section('page-title', 'Manajemen Data User')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h3>Data User</h3>
        <p>Kelola semua data user dalam sistem</p>
    </div>
    <a href="{{ route('user.create') }}" class="btn btn-primary">
        <i class='bx bx-plus'></i> Tambah User
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class='bx bx-list-ul'></i> Daftar User</span>
        <div class="d-flex gap-2">
            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari user..." style="width: 250px;">
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th style="width: 100px;">ID User</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th style="width: 150px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($users as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="badge bg-secondary">{{ $item->iduser }}</span></td>
                        <td class="fw-semibold">{{ $item->username }}</td>
                        <td><span class="badge bg-info text-dark">{{ $item->nama_role ?? '-' }}</span></td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('user.edit', $item->iduser) }}" class="btn btn-outline-warning" title="Edit">
                                    <i class='bx bx-edit'></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" 
                                    onclick="confirmDelete('{{ $item->iduser }}', '{{ $item->username }}')">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class='bx bx-user' style="font-size: 48px;"></i>
                            <p class="mt-2">Belum ada data user</p>
                            <a href="{{ route('user.create') }}" class="btn btn-sm btn-primary">
                                <i class='bx bx-plus'></i> Tambah User Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(count($users) > 0)
    <div class="card-footer d-flex justify-content-between align-items-center">
        <span id="countText" class="text-muted"></span>
    </div>
    @endif
</div>

<form id="deleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    // Fungsi untuk update teks "Menampilkan ... dari ..."
    function updateCount() {
        let rows = document.querySelectorAll('#tableBody tr');
        let visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
        document.getElementById('countText').textContent =
            `Menampilkan ${visibleRows.length} dari ${rows.length} data`;
    }

    // Fungsi pencarian user
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelectorAll('#tableBody tr');
        rows.forEach(row => {
            let text = row.textContent || row.innerText;
            row.style.display = text.toUpperCase().indexOf(filter) > -1 ? '' : 'none';
        });
        updateCount();
    });

    // Panggil saat pertama kali load halaman
    updateCount();

    // Konfirmasi hapus user
    function confirmDelete(id, username) {
        Swal.fire({
            title: 'Hapus User?',
            text: `Yakin ingin menghapus user "${username}"? Data tidak dapat dikembalikan!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74a3b',
            cancelButtonColor: '#858796',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.getElementById('deleteForm');
                form.action = `/user/${id}`;
                form.submit();
            }
        });
    }
</script>
@endpush
@endsection
