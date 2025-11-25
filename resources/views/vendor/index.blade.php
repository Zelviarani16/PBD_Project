@extends('layouts.app')

@section('title', 'Data Vendor')
@section('page-title', 'Manajemen Data Vendor')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Data Vendor</h3>
            <p>Kelola semua data vendor dalam sistem</p>
        </div>
        <a href="{{ route('vendor.create') }}" class="btn btn-primary">
            <i class='bx bx-plus'></i> Tambah Vendor
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class='bx bx-list-ul'></i> Daftar Vendor</span>
        <div class="d-flex gap-2">
            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari vendor..." style="width: 250px;">
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
                        <th style="width: 100px;">ID</th>
                        <th>Nama Vendor</th>
                        <th>Badan Hukum</th>
                        <th style="width: 120px;">Status</th>
                        <th style="width: 150px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($vendor as $index => $v)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="badge bg-secondary">{{ $v->idvendor }}</span></td>
                        <td class="fw-semibold">{{ $v->nama_vendor }}</td>
                        <td>{{ $v->badan_hukum }}</td>
                        <td>
                            @if($v->status_text === 'Aktif')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('vendor.edit', $v->idvendor) }}" class="btn btn-outline-warning" title="Edit">
                                    <i class='bx bx-edit'></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" onclick="confirmDelete('{{ $v->idvendor }}', '{{ $v->nama_vendor }}')" title="Hapus">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class='bx bx-box' style="font-size: 48px;"></i>
                            <p class="mt-2">Belum ada data vendor</p>
                            <a href="{{ route('vendor.create') }}" class="btn btn-sm btn-primary">
                                <i class='bx bx-plus'></i> Tambah Vendor Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(count($vendor) > 0)
    <div class="card-footer d-flex justify-content-between align-items-center">
        <span id="countText" class="text-muted"></span>
    </div>
    @endif
</div>

<!-- Form Delete (Hidden) -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    // Update jumlah menampilkan ... dari ...
    function updateCount() {
        let rows = document.querySelectorAll('#tableBody tr');
        let visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
        document.getElementById('countText').textContent =
            `Menampilkan ${visibleRows.length} dari ${rows.length} data`;
    }

    // Search filter
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelectorAll('#tableBody tr');
        rows.forEach(row => {
            let text = row.textContent || row.innerText;
            row.style.display = text.toUpperCase().indexOf(filter) > -1 ? '' : 'none';
        });
        updateCount();
    });

    // Filter by status
    document.getElementById('filterStatus').addEventListener('change', function() {
        let filter = this.value;
        let rows = document.querySelectorAll('#tableBody tr');
        rows.forEach(row => {
            let statusBadge = row.querySelector('td:nth-child(5) .badge');
            if (!statusBadge) return;
            let statusText = statusBadge.textContent.trim();
            if (filter === '' || filter === statusText) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
        updateCount();
    });

    filterStatus.value = 'Aktif';
    applyFilters();

    filterStatus.addEventListener('change', applyFilters);
    searchInput.addEventListener('keyup', applyFilters);

    // Panggil sekali saat load
    updateCount();

    // Confirm delete SweetAlert
    function confirmDelete(id, nama) {
        Swal.fire({
            title: 'Hapus Vendor?',
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
                form.action = `/vendor/${id}`;
                form.submit();
            }
        });
    }
</script>
@endpush
