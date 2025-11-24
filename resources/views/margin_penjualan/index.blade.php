@extends('layouts.app')

@section('title', 'Data Margin Penjualan')
@section('page-title', 'Manajemen Margin Penjualan')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h3>Data Margin Penjualan</h3>
        <p>Kelola semua data margin penjualan dalam sistem</p>
    </div>
    <a href="{{ route('margin.create') }}" class="btn btn-primary">
        <i class='bx bx-plus'></i> Tambah Margin
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class='bx bx-list-ul'></i> Daftar Margin Penjualan</span>
        <div class="d-flex gap-2">
            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari username atau persen..." style="width: 250px;">
            <select id="filterStatus" class="form-select form-select-sm" style="width: 150px;">
                <option value="">Semua Status</option>
                <option value="Aktif" selected>Aktif</option>
                <option value="Tidak Aktif">Tidak Aktif</option>
            </select>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th style="width: 120px;">ID Margin</th>
                        <th style="width: 100px;">Persen</th>
                        <th>Username</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 150px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($margins as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="badge bg-secondary">{{ $item->idmargin_penjualan }}</span></td>
                        <td>{{ number_format($item->persen, 2) }}%</td>
                        <td class="fw-semibold">{{ $item->username }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->updated_at)->format('d-m-Y H:i') }}</td>
                        <td>
                            @if($item->status_text === 'Aktif')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('margin.edit', $item->idmargin_penjualan) }}" class="btn btn-outline-warning">
                                    <i class='bx bx-edit'></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger"
                                    onclick="confirmDelete('{{ $item->idmargin_penjualan }}', '{{ $item->username }}')">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class='bx bx-error-circle' style="font-size: 48px;"></i>
                            <p class="mt-2">Belum ada data margin penjualan</p>
                            <a href="{{ route('margin.create') }}" class="btn btn-sm btn-primary">
                                <i class='bx bx-plus'></i> Tambah Data Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(count($margins) > 0)
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
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterStatus = document.getElementById('filterStatus');
    const tableBody = document.getElementById('tableBody');
    const countText = document.getElementById('countText');

    function updateTable() {
        let searchFilter = searchInput.value.toUpperCase();
        let statusFilter = filterStatus.value;

        let rows = tableBody.querySelectorAll('tr');
        rows.forEach(row => {
            let text = row.textContent || row.innerText;
            let statusBadge = row.querySelector('td:nth-child(7) .badge');
            let statusText = statusBadge ? statusBadge.textContent.trim() : '';

            // Tentukan visible atau tidak
            let matchesSearch = text.toUpperCase().indexOf(searchFilter) > -1;
            let matchesStatus = (statusFilter === '' || statusFilter === statusText);

            row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });

        // Update jumlah data yang tampil
        let visibleRows = Array.from(rows).filter(r => r.style.display !== 'none');
        countText.textContent = `Menampilkan ${visibleRows.length} dari ${rows.length} data`;
    }

    // Event listeners
    searchInput.addEventListener('keyup', updateTable);
    filterStatus.addEventListener('change', updateTable);

    // Default filter status Aktif saat load
    updateTable();
});

// Konfirmasi delete
function confirmDelete(id, username) {
    Swal.fire({
        title: 'Hapus Data Margin?',
        text: `Yakin ingin menghapus margin milik "${username}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74a3b',
        cancelButtonColor: '#858796',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.getElementById('deleteForm');
            form.action = `/margin-penjualan/${id}`;
            form.submit();
        }
    });
}
</script>
@endpush
@endsection
