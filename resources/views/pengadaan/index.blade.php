@extends('layouts.app')

@section('title', 'Data Pengadaan')
@section('page-title', 'Manajemen Data Pengadaan')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Data Pengadaan</h3>
            <p>Kelola semua data pengadaan barang dalam sistem</p>
        </div>
        <a href="{{ route('pengadaan.create') }}" class="btn btn-primary">
            <i class='bx bx-plus'></i> Tambah Pengadaan
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class='bx bx-list-ul'></i> Daftar Pengadaan</span>
        <div class="d-flex gap-2">
            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari pengadaan..." style="width: 250px;">
            <select id="filterStatus" class="form-select form-select-sm" style="width: 150px;">
                <option value="">Semua Status</option>
                <option value="Pending">Pending</option>
                <option value="In Process">In Process</option>
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
                        <th style="width: 80px;">ID</th>
                        <th>Tanggal</th>
                        <th>Vendor</th>
                        <th style="width: 120px;">Subtotal</th>
                        <th style="width: 80px;">PPN</th>
                        <th style="width: 120px;">Total Nilai</th>
                        <th style="width: 100px;">Status</th>
                        <th>User</th>
                        <th style="width: 150px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($pengadaans as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="badge bg-secondary">{{ $item->idpengadaan }}</span></td>
                        <td>{{ $item->tanggal_pengadaan }}</td>
                        <td>{{ $item->nama_vendor }}</td>
                        <td>Rp {{ number_format($item->subtotal_nilai, 0, ',', '.') }}</td>
                        <td>{{ $item->ppn }}%</td>
                        <td>Rp {{ number_format($item->total_nilai, 0, ',', '.') }}</td>
                        <td>
                            @if($item->status_text == 'Selesai')
                                <span class="badge bg-success">{{ $item->status_text }}</span>
                            @elseif($item->status_text == 'Pending')
                                <span class="badge bg-warning text-dark">{{ $item->status_text }}</span>
                            @elseif($item->status_text == 'In Process')
                                <span class="badge bg-info text-dark">{{ $item->status_text }}</span>
                            @else
                                <span class="badge bg-danger">{{ $item->status_text }}</span>
                            @endif
                        </td>
                        <td>{{ $item->dibuat_oleh }}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('pengadaan.detail', $item->idpengadaan) }}" class="btn btn-outline-info" title="Detail">
                                    <i class='bx bx-show'></i>
                                </a>
                                @if(in_array($item->status_text, ['Pending', 'In Process']))
                                <button type="button" class="btn btn-outline-warning" 
                                    onclick="confirmBatal('{{ $item->idpengadaan }}')" 
                                    title="Batal">
                                    <i class='bx bx-x-circle'></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            <i class='bx bx-box' style="font-size: 48px;"></i>
                            <p class="mt-2">Belum ada data pengadaan</p>
                            <a href="{{ route('pengadaan.create') }}" class="btn btn-sm btn-primary">
                                <i class='bx bx-plus'></i> Tambah Pengadaan Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(count($pengadaans) > 0)
    <div class="card-footer d-flex justify-content-between align-items-center">
        <span id="countText" class="text-muted"></span>
    </div>
    @endif
</div>

<!-- Form Batal (Hidden) -->
<form id="batalForm" method="POST" style="display: none;">
    @csrf
</form>

@endsection

@push('scripts')
<script>
function updateCount() {
    let rows = document.querySelectorAll('#tableBody tr');
    let visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
    document.getElementById('countText').textContent =
        `Menampilkan ${visibleRows.length} dari ${rows.length} data`;
}

// Search
document.getElementById('searchInput').addEventListener('keyup', function() {
    let filter = this.value.toUpperCase();
    let rows = document.querySelectorAll('#tableBody tr');

    rows.forEach(row => {
        let text = row.textContent || row.innerText;
        row.style.display = text.toUpperCase().indexOf(filter) > -1 ? '' : 'none';
    });

    updateCount();
});

// Dropdown filter
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
            let statusBadge = row.querySelector('td:nth-child(8) .badge');
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

    filterStatus.value = 'In Process';
    applyFilters();

    filterStatus.addEventListener('change', applyFilters);
    searchInput.addEventListener('keyup', applyFilters);
});

// Confirm Batal
function confirmBatal(id) {
    Swal.fire({
        title: 'Batalkan Pengadaan?',
        text: `Yakin ingin membatalkan pengadaan ini?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f6c23e',
        cancelButtonColor: '#858796',
        confirmButtonText: 'Ya, Batalkan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.getElementById('batalForm');
            form.action = `/pengadaan/batal/${id}`;
            form.submit();
        }
    });
}
</script>
@endpush
