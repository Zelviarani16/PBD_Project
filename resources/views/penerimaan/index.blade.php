@extends('layouts.app')

@section('title', 'Data Penerimaan')
@section('page-title', 'Manajemen Data Penerimaan')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Data Penerimaan</h3>
            <p>Kelola semua penerimaan barang dari pengadaan</p>
        </div>
        <a href="{{ route('penerimaan.create') }}" class="btn btn-primary">
            <i class='bx bx-plus'></i> Tambah Penerimaan
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class='bx bx-list-ul'></i> Daftar Penerimaan</span>
        <div class="d-flex gap-2">
            <input type="text" id="searchInput" class="form-control form-control-sm"
                   placeholder="Cari penerimaan..." style="width: 250px;">
            <select id="filterStatus" class="form-select form-select-sm" style="width: 150px;">
                <option value="">Semua Status</option>
                <option value="Pending">Pending</option>
                <option value="Sebagian">Sebagian</option>
                <option value="Selesai">Selesai</option>
                <option value="Batal">Batal</option>
            </select>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th style="width: 120px;">ID Penerimaan</th>
                        <th>ID Pengadaan</th>
                        <th>User</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th style="width: 150px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($penerimaan as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="badge bg-secondary">{{ $item->idpenerimaan }}</span></td>
                        <td>{{ $item->idpengadaan }}</td>
                        <td>{{ $item->dibuat_oleh ?? '-' }}</td>
                        <td>{{ $item->tanggal_penerimaan }}</td>
                        <td>
                            @if($item->status_text == 'Selesai')
                                <span class="badge bg-success">{{ $item->status_text }}</span>
                            @elseif($item->status_text == 'Sebagian')
                                <span class="badge bg-warning text-dark">{{ $item->status_text }}</span>
                            @elseif($item->status_text == 'Pending')
                                <span class="badge bg-primary text-white">{{ $item->status_text }}</span>
                            @else
                                <span class="badge bg-danger">{{ $item->status_text }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('penerimaan.detail', $item->idpenerimaan) }}"
                                   class="btn btn-outline-info"
                                   title="Detail">
                                    <i class='bx bx-show'></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class='bx bx-box' style="font-size: 48px;"></i>
                            <p class="mt-2">Belum ada data penerimaan</p>
                            <a href="{{ route('penerimaan.create') }}" class="btn btn-sm btn-primary">
                                <i class='bx bx-plus'></i> Tambah Penerimaan Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(count($penerimaan) > 0)
    <div class="card-footer d-flex justify-content-between align-items-center">
        <span id="countText" class="text-muted"></span>
    </div>
    @endif
</div>

<!-- Hidden Delete Form -->
<form id="deleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const tableBody = document.getElementById('tableBody');
    const filterStatus = document.getElementById('filterStatus');
    const searchInput = document.getElementById('searchInput');
    const countText = document.getElementById('countText');

    function updateCount() {
        let rows = tableBody.querySelectorAll('tr');
        let visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
        countText.textContent = `Menampilkan ${visibleRows.length} dari ${rows.length} data`;
    }

    function applyFilters() {
        let statusFilter = filterStatus.value.toUpperCase();
        let searchFilter = searchInput.value.toUpperCase();

        let rows = tableBody.querySelectorAll('tr');
        rows.forEach(row => {
            let statusBadge = row.querySelector('td:nth-child(6) .badge');
            let statusText = statusBadge ? statusBadge.textContent.trim().toUpperCase() : '';
            let text = row.textContent || row.innerText;

            if ((statusFilter === '' || statusText === statusFilter) &&
                text.toUpperCase().includes(searchFilter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        updateCount();
    }

    filterStatus.value = 'Pending';
    applyFilters();

    filterStatus.addEventListener('change', applyFilters);
    searchInput.addEventListener('keyup', applyFilters);

});

</script>
@endpush
