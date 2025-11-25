@extends('layouts.app')

@section('title', 'Data Barang')
@section('page-title', 'Manajemen Data Barang')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Data Barang</h3>
            <p>Kelola semua data barang dalam sistem</p>
        </div>
        <a href="{{ route('barang.create') }}" class="btn btn-primary">
            <i class='bx bx-plus'></i> Tambah Barang
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class='bx bx-list-ul'></i> Daftar Barang</span>
        <div class="d-flex gap-2">
            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari barang..." style="width: 250px;">
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
                        <th>Nama Barang</th>
                        <th style="width: 100px;">Jenis</th>
                        <th>Satuan</th>
                        <th style="width: 150px;">Harga</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 150px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <!-- $barang dari file controller, isinya get all tadi berbentuk array -->
                    @forelse($barang as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span class="badge bg-secondary">{{ $item->idbarang }}</span></td>
                        <td class="fw-semibold">{{ $item->nama_barang }}</td>
                        <td>
                            <span class="badge 
                                @if($item->jenis == 'A') bg-primary
                                @elseif($item->jenis == 'B') bg-success
                                @else bg-info
                                @endif">
                                {{ $item->jenis }}
                            </span>
                        </td>
                        <!-- kalau nama satuan ada nilainya tampilkan, kalau null beri - -->
                        <td><span class="badge bg-light text-dark">{{ $item->nama_satuan ?? '-' }}</span></td>
                        <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td>
                            @if($item->status_text === 'Aktif')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('barang.edit', $item->idbarang) }}" class="btn btn-outline-warning" title="Edit">
                                    <i class='bx bx-edit'></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" onclick="confirmDelete('{{ $item->idbarang }}', '{{ $item->nama_barang }}')" title="Hapus">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class='bx bx-box' style="font-size: 48px;"></i>
                            <p class="mt-2">Belum ada data barang</p>
                            <a href="{{ route('barang.create') }}" class="btn btn-sm btn-primary">
                                <i class='bx bx-plus'></i> Tambah Barang Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Kalau tidak ada data, ini tdk akan muncul (tdk akan dieksekusi) -->
    @if(count($barang) > 0)
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

<!-- Di layout utama ada stack scripts, dipanggil disini -->
@push('scripts')
<script>
    // Update berapa baris data yg terlihat di tabel.
    // Ambil semua baris data <tr> di tbody
    // Update jumlah menampilkan ... dari ... 
    function updateCount() {
        let rows = document.querySelectorAll('#tableBody tr');
        let visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
        document.getElementById('countText').textContent =
            `Menampilkan ${visibleRows.length} dari ${rows.length} data`;
    }

    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase(); // Ambil input search, ubah jadu huruf besar utk case-insensitive
        let rows = document.querySelectorAll('#tableBody tr'); // Ambil semua baris tabel

        rows.forEach(row => { // Ambil semua teks di baris
            let text = row.textContent || row.innerText;
            row.style.display = text.toUpperCase().indexOf(filter) > -1 ? '' : 'none'; // Cek apakah teks ada kata yg dicari
        });

        updateCount(); // Update jumlah "Menampilkan ... dari ..." setelah search
    });

    // Filter by status
    document.getElementById('filterStatus').addEventListener('change', function() {
        let filter = this.value; // ambil nilai filter, Kalau user pilih “Aktif” → filter = "Aktif"
        let rows = document.querySelectorAll('#tableBody tr');

        // Loop semua baris dan cek kolom statusnya, child(7) itu kolom status
        rows.forEach(row => {
            let statusBadge = row.querySelector('td:nth-child(7) .badge'); // .badge didalam elemen itu ada elemen <span> dari badge
            if (!statusBadge) return; // cek aja, kalau tidak ada elemen badge berarti baris ini kosong/error

            let statusText = statusBadge.textContent.trim(); // Statusnya disimpan disini -> aktif noaktif
            if (filter === '' || filter === statusText) {
                row.style.display = '';
            } else { // kalau kondisi || tidak terpenuhi, maka sembunyikan baris tsb. misal dropdown pilih aktif, jika status text nya noaktif maka disembunyikan baris tsb
                row.style.display = 'none';
            }
        });

        updateCount(); // update count setelah filter
    });
    filterStatus.value = 'Aktif';
    applyFilters();

    filterStatus.addEventListener('change', applyFilters);
    searchInput.addEventListener('keyup', applyFilters);

    // Panggil sekali saat load
    updateCount();

    // Confirm delete with SweetAlert
    // Fungsi hapus dipanggil saat tombol hapus di klik.
    // Parameter id dan naam = id, nama barang yg mau dihapus
    function confirmDelete(id, nama) {
        Swal.fire({
            title: 'Hapus Barang?',
            text: `Yakin ingin menghapus "${nama}"? Data tidak dapat dikembalikan!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74a3b',
            cancelButtonColor: '#858796',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) { // Pengecekan kalau user klik ya, hapus maka logika dlm kurung jalan
                let form = document.getElementById('deleteForm');
                // Diambil id nya, hapus berdasarkan id
                form.action = `/barang/${id}`;
                form.submit();
            }
        });
    }
</script>
@endpush
