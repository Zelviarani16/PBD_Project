@extends('layouts.app')

@section('title', 'Kartu Stok')
@section('page-title', 'Manajemen Kartu Stok')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Kartu Stok</h3>
            <p>Riwayat keluar/masuk stok barang</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class='bx bx-list-ul'></i> Daftar Kartu Stok</span>
        <div class="d-flex gap-2">
            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari barang..." style="width: 200px;">
            <select id="filterJenis" class="form-select form-select-sm" style="width: 150px;">
                <option value="">Semua Jenis</option>
                <option value="M">Masuk</option>
                <option value="K">Keluar</option>
                <option value="R">Return</option>
                <option value="P">Pembatalan</option>
            </select>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>ID Transaksi</th>
                        <th>Nama Barang</th>
                        <th>Jenis Transaksi</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th>Stock</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                 @forelse($kartuStok as $index => $item)
                    @php
                        $jt = $jenis[$item->jenis_transaksi] ?? ['label'=>'-','class'=>'bg-secondary'];
                    @endphp
                    <tr data-jenis="{{ $item->jenis_transaksi }}">
                        <td>{{ $index + 1 }}</td>
                        <td><span class="badge bg-secondary">{{ $item->idkartu_stok }}</span></td>
                        <td><span class="badge bg-info">{{ $item->idtransaksi }}</span></td>
                        <td>{{ $item->nama_barang }}</td>
                        <td><span class="badge {{ $jt['class'] }}">{{ $jt['label'] }}</span></td>
                        <td>{{ $item->masuk }}</td>
                        <td>{{ $item->keluar }}</td>
                        <td>{{ $item->stock }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class='bx bx-box' style="font-size: 48px;"></i>
                            <p class="mt-2">Belum ada data kartu stok</p>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

    @if(count($kartuStok) > 0)
    <div class="card-footer d-flex justify-content-between align-items-center">
        <span id="countText" class="text-muted"></span>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.getElementById('tableBody');
    const searchInput = document.getElementById('searchInput');
    const filterJenis = document.getElementById('filterJenis');
    const countText = document.getElementById('countText');

    function applyFilters() {
        const search = searchInput.value.toUpperCase();
        const jenisFilter = filterJenis.value;
        const rows = tableBody.querySelectorAll('tr');

        rows.forEach(row => {
            const text = row.textContent || row.innerText;
            const rowJenis = row.dataset.jenis;
            const matchesSearch = text.toUpperCase().includes(search);
            const matchesJenis = (jenisFilter === "" || rowJenis === jenisFilter);

            row.style.display = (matchesSearch && matchesJenis) ? '' : 'none';
        });

        updateCount();
    }

    function updateCount() {
        const rows = tableBody.querySelectorAll('tr');
        const visibleRows = Array.from(rows).filter(r => r.style.display !== 'none');
        countText.textContent = `Menampilkan ${visibleRows.length} dari ${rows.length} data`;
    }

    searchInput.addEventListener('keyup', applyFilters);
    filterJenis.addEventListener('change', applyFilters);

    applyFilters(); // set default saat load
});
</script>
@endpush
