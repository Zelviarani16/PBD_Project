@extends('layouts.app')

@section('title', 'Data Satuan')
@section('page-title', 'Manajemen Data Satuan')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h3>Data Satuan</h3>
        <p>Kelola semua data satuan dalam sistem</p>
    </div>
    <a href="{{ route('satuan.create') }}" class="btn btn-primary">
        <i class='bx bx-plus'></i> Tambah Satuan
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class='bx bx-list-ul'></i> Daftar Satuan</span>
        <div class="d-flex gap-2">
            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari satuan..." style="width: 250px;">
            <select id="filterStatus" class="form-select form-select-sm" style="width: 150px;">
                <option value="">Semua Status</option>
                <option value="Aktif">Aktif</option>
                <option value="Nonaktif">Nonaktif</option>
            </select>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th style="width: 80px;">ID</th>
                        <th>Nama Satuan</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 150px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($satuan as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="badge bg-secondary">{{ $item->idsatuan }}</span></td>
                        <td class="fw-semibold">{{ $item->nama_satuan }}</td>
                        <td>
                            @if($item->status_text === 'Aktif')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('satuan.edit', $item->idsatuan) }}" class="btn btn-outline-warning">
                                    <i class='bx bx-edit'></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger"
                                    onclick="confirmDelete('{{ $item->idsatuan }}', '{{ $item->nama_satuan }}')">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class='bx bx-box' style="font-size: 48px;"></i>
                            <p class="mt-2">Belum ada data satuan</p>
                            <a href="{{ route('satuan.create') }}" class="btn btn-sm btn-primary">
                                <i class='bx bx-plus'></i> Tambah Satuan Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(count($satuan) > 0)
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
    function updateCount() {
        let rows = document.querySelectorAll('#tableBody tr');
        let visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
        document.getElementById('countText').textContent =
            `Menampilkan ${visibleRows.length} dari ${rows.length} data`;
    }

    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelectorAll('#tableBody tr');
        rows.forEach(row => {
            let text = row.textContent || row.innerText;
            row.style.display = text.toUpperCase().indexOf(filter) > -1 ? '' : 'none';
        });
        updateCount();
    });

    document.getElementById('filterStatus').addEventListener('change', function() {
        let filter = this.value;
        let rows = document.querySelectorAll('#tableBody tr');
        rows.forEach(row => {
            let statusBadge = row.querySelector('td:nth-child(4) .badge');
            if (!statusBadge) return;
            let statusText = statusBadge.textContent.trim();
            row.style.display = (filter === '' || filter === statusText) ? '' : 'none';
        });
        updateCount();
    });

    filterStatus.value = 'Aktif';
    applyFilters();

    filterStatus.addEventListener('change', applyFilters);
    searchInput.addEventListener('keyup', applyFilters);

    updateCount();

    function confirmDelete(id, nama) {
        Swal.fire({
            title: 'Hapus Satuan?',
            text: `Yakin ingin menghapus "${nama}"? Data tidak dapat dikembalikan!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74a3b',
            cancelButtonColor: '#858796',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.getElementById('deleteForm');
                form.action = `/satuan/${id}`;
                form.submit();
            }
        });
    }
</script>
@endpush
@endsection
