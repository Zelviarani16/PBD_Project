@extends('layouts.app')

@section('title', 'Data Penjualan')
@section('page-title', 'Manajemen Data Penjualan')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Data Penjualan</h3>
            <p>Kelola semua data penjualan barang</p>
        </div>
        <a href="{{ route('penjualan.create') }}" class="btn btn-primary">
            <i class='bx bx-plus'></i> Mulai Penjualan
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class='bx bx-list-ul'></i> Daftar Penjualan</span>
        <div class="d-flex gap-2">
            <!-- <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari penjualan..." style="width: 250px;"> -->
            <!-- <select id="filterStatus" class="form-select form-select-sm" style="width: 150px;">
                <option value="">Semua</option>
                <option value="Selesai">Selesai</option>
                <option value="Pending">Pending</option>
                <option value="Batal">Batal</option>
            </select> -->
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th style="width:90px;">ID</th>
                        <th>Tanggal</th>
                        <th>Kasir</th>
                        <th style="width:120px;">Subtotal</th>
                        <th style="width:80px;">Margin (%) </th>
                        <th style="width:80px;">Margin Nilai</th>
                        <th style="width:80px;">PPN</th>
                        <th style="width:140px;">Total</th>
                        <th style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($penjualans as $i => $p)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><span class="badge bg-secondary">{{ $p->idpenjualan }}</span></td>
                        <td>{{ $p->tanggal_penjualan }}</td>
                        <td>{{ $p->kasir }}</td>
                        <td>Rp {{ number_format($p->subtotal_nilai, 0, ',', '.') }}</td>
                        <td>{{ $p->margin_persen }}%</td>
                        <td>Rp {{ number_format($p->subtotal_nilai * ($p->margin_persen / 100), 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($p->subtotal_nilai * ($p->ppn / 100), 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($p->total_nilai, 0, ',', '.') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('penjualan.detail', $p->idpenjualan) }}" class="btn btn-outline-info" title="Detail">
                                    <i class='bx bx-show'></i>
                                </a>
                                <!-- <form action="{{ route('penjualan.cancel') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="idpenjualan" value="{{ $p->idpenjualan }}">
                                    <button type="submit" class="btn btn-outline-danger" title="Batal" onclick="return confirm('Batalkan transaksi {{ $p->idpenjualan }}?')">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </form> -->
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class='bx bx-box' style="font-size: 48px;"></i>
                            <p class="mt-2">Belum ada data penjualan</p>
                            <a href="{{ route('penjualan.create') }}" class="btn btn-sm btn-primary">
                                <i class='bx bx-plus'></i> Mulai Penjualan
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(count($penjualans) > 0)
    <div class="card-footer d-flex justify-content-between align-items-center">
        <span id="countText" class="text-muted"></span>
    </div>
    @endif
</div>

<!-- Hidden cancel form already inlined per-row -->
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterStatus = document.getElementById('filterStatus');
    const tableBody = document.getElementById('tableBody');
    const countText = document.getElementById('countText');

    function updateCount() {
        let rows = tableBody.querySelectorAll('tr');
        let visible = Array.from(rows).filter(r => r.style.display !== 'none');
        if (countText) countText.textContent = `Menampilkan ${visible.length} dari ${rows.length} data`;
    }

    function applyFilters() {
        let q = searchInput.value.toUpperCase();
        let status = filterStatus.value.toUpperCase();
        let rows = tableBody.querySelectorAll('tr');

        rows.forEach(row => {
            let text = row.textContent || row.innerText;
            let showBySearch = text.toUpperCase().indexOf(q) > -1;

            // status badge not always present; we check by content
            let statusCell = row.querySelector('td:nth-child(8)');
            let statusText = statusCell ? statusCell.textContent.trim().toUpperCase() : '';

            let showByStatus = (status === '' || statusText.indexOf(status) > -1);

            row.style.display = (showBySearch && showByStatus) ? '' : 'none';
        });

        updateCount();
    }

    filterStatus.addEventListener('change', applyFilters);
    searchInput.addEventListener('keyup', applyFilters);

    // set default
    updateCount();
});
</script>
@endpush
