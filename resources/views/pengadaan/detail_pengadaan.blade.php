@extends('layouts.app')

@section('title', 'Detail Pengadaan')
@section('page-title', 'Detail Pengadaan')

@section('content')
<div class="card shadow-lg p-4 rounded-3">
    {{-- Informasi Pengadaan --}}
    <div class="mb-4">
        <h4 class="fw-bold">Informasi Pengadaan</h4>
        <table class="table table-borderless">
            <tr>
                <th>ID Pengadaan</th>
                <td>{{ $pengadaan->idpengadaan }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td>{{ $pengadaan->tanggal_pengadaan }}</td>
            </tr>
            <tr>
                <th>Nama User</th>
                <td>{{ $pengadaan->dibuat_oleh }}</td>
            </tr>
            <tr>
                <th>Nama Vendor</th>
                <td>{{ $pengadaan->nama_vendor }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <span class="badge 
                        @if($pengadaan->status_text == 'Pending') bg-warning text-dark
                        @elseif($pengadaan->status_text == 'In Process') bg-info text-dark
                        @elseif($pengadaan->status_text == 'Sebagian') bg-primary text-white
                        @elseif($pengadaan->status_text == 'Selesai') bg-success
                        @elseif($pengadaan->status_text == 'Batal') bg-danger
                        @else bg-secondary text-white
                        @endif">
                        {{ $pengadaan->status_text }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Subtotal</th>
                <td>Rp {{ number_format($pengadaan->subtotal_nilai, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>PPN (10%)</th>
                <td>{{ number_format($pengadaan->ppn_rupiah, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total Nilai</th>
                <td>Rp {{ number_format($pengadaan->total_nilai, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    {{-- Tabel Detail Barang --}}
    <div class="mt-4">
        <h5 class="fw-bold mb-3">Detail Barang Pengadaan</h5>
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah</th>
                    <th>Sub Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($details as $index => $d)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $d->idbarang }}</td>
                        <td>{{ $d->nama_barang }}</td>
                        <td>Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                        <td>{{ $d->jumlah }}</td>
                        <td>Rp {{ number_format($d->sub_total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Belum ada barang ditambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($isEditable)
        <!-- Form Tambah Detail Pengadaan (hanya muncul jika status = 'P') -->
        <div class="mt-5">
            <h5 class="fw-bold mb-3">Tambah Barang ke Pengadaan</h5>
            <form action="{{ route('pengadaan.tambahDetail', $pengadaan->idpengadaan) }}" method="POST">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="idbarang" class="form-label">Pilih Barang</label>
                        <select name="idbarang" id="idbarang" class="form-select" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barangs as $barang)
                                <option value="{{ $barang->idbarang }}" data-harga="{{ $barang->harga }}">{{ $barang->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="harga_satuan" class="form-label">Harga Satuan</label>
                        <input type="number" name="harga_satuan" id="harga_satuan" class="form-control" placeholder="Rp..." readonly>
                    </div>

                    <div class="col-md-2">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control" placeholder="0" required>
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-plus-circle"></i> Tambah Barang
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- TOMBOL SIMPAN PENGADAAN & KEMBALI --}}
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('pengadaan.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            
            <form action="{{ route('pengadaan.finalize', $pengadaan->idpengadaan) }}" 
                  method="POST" 
                  onsubmit="return confirm('Yakin ingin menyimpan pengadaan ini? Setelah disimpan tidak bisa diubah lagi.')">
                @csrf
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-save"></i> SIMPAN PENGADAAN
                </button>
            </form>
        </div>
    @else
        {{-- READ ONLY MODE --}}
        <div class="alert alert-info mt-4">
            <i class="bi bi-info-circle"></i> <strong>Pengadaan sudah difinalisasi.</strong> Tidak bisa menambah atau mengubah barang lagi.
        </div>
        
        <div class="text-start mt-3">
            <a href="{{ route('pengadaan.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    @endif
</div>

<!-- Harga Otomatis -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectBarang = document.getElementById('idbarang');
        const inputHarga = document.getElementById('harga_satuan');

        if(selectBarang) {
            selectBarang.addEventListener('change', function() {
                const harga = this.options[this.selectedIndex].dataset.harga || '';
                inputHarga.value = harga;
            });
        }
    });
</script>
@endsection