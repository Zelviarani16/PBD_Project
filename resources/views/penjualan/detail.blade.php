@extends('layouts.app')

@section('title', 'Detail Penjualan')
@section('page-title', 'Detail Penjualan')

@section('content')
@php
    $isEditable = !$penjualan->is_final;
@endphp

<div class="card shadow-lg p-4 rounded-3">
    {{-- Informasi Penjualan --}}
    <div class="mb-4">
        <h4 class="fw-bold">Informasi Penjualan</h4>
        <table class="table table-borderless">
            <tr><th>ID Penjualan</th><td>{{ $penjualan->idpenjualan }}</td></tr>
            <tr><th>Tanggal</th><td>{{ $penjualan->tanggal_penjualan }}</td></tr>
            <tr><th>Kasir</th><td>{{ $penjualan->kasir }}</td></tr>
            <tr><th>Subtotal</th><td>Rp {{ number_format($penjualan->subtotal_nilai ?? 0, 0, ',', '.') }}</td></tr>
            <tr><th>Margin</th><td>{{ $penjualan->margin_persen ?? 0 }}%</td></tr>
            <tr><th>PPN</th><td>{{ $penjualan->ppn ?? 10 }}%</td></tr>
            <tr><th>Total</th><td>Rp {{ number_format($penjualan->total_nilai ?? 0, 0, ',', '.') }}</td></tr>
        </table>
    </div>

    {{-- Detail Barang --}}
    <div class="mt-4">
        <h5 class="fw-bold mb-3">Detail Barang Penjualan</h5>
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Harga Asli</th>
                    <th>Harga Jual</th>
                    <th>Keuntungan/Satuan</th>
                    <th>Jumlah</th>
                    <th>Sub Total</th>
                    <th>Sub Total Keuntungan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($details as $i => $d)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $d->nama_barang }}</td>
                        <td>Rp {{ number_format($d->harga_modal, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($d->harga_jual, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($d->keuntungan_satuan, 0, ',', '.') }}</td>
                        <td>{{ $d->jumlah }}</td>
                        <td>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($d->total_keuntungan, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Belum ada barang ditambahkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($isEditable)
        {{-- Form Tambah Barang --}}
        <div class="mt-5">
            <h5 class="fw-bold mb-3">Tambah Barang ke Penjualan</h5>
            <form action="{{ route('penjualan.detail.add', $penjualan->idpenjualan) }}" method="POST">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="idbarang" class="form-label">Barang</label>
                        <select name="idbarang" id="idbarang" class="form-control" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach ($barangs as $b)
                                <option value="{{ $b->idbarang }}" data-harga="{{ $b->harga }}" data-stok="{{ $b->stok }}">
                                    {{ $b->nama }} | Harga: {{ number_format($b->harga) }} | Stok: {{ $b->stok }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="harga_satuan" class="form-label">Harga</label>
                        <input type="number" name="harga_satuan" id="harga_satuan" class="form-control" readonly>
                    </div>

                    <div class="col-md-2">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control" min="1" required>
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-plus-circle"></i> Tambah Barang
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Tombol Finalisasi --}}
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <form action="{{ route('penjualan.finalize', $penjualan->idpenjualan) }}" method="POST"
                  onsubmit="return confirm('Yakin ingin menyimpan penjualan ini? Setelah disimpan tidak bisa diubah lagi.')">
                @csrf
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-save"></i> SIMPAN PENJUALAN
                </button>
            </form>
        </div>
    @else
        {{-- Read-only --}}
        <div class="alert alert-info mt-4">
            <i class="bi bi-info-circle"></i> <strong>Penjualan sudah difinalisasi.</strong> Tidak bisa menambah atau mengubah barang lagi.
        </div>
        <div class="text-start mt-3">
            <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectBarang = document.getElementById('idbarang');
    const hargaInput = document.getElementById('harga_satuan');

    selectBarang?.addEventListener('change', function() {
        const harga = this.options[this.selectedIndex].dataset.harga || '';
        const stok = this.options[this.selectedIndex].dataset.stok || '';
        hargaInput.value = harga;

        if(stok !== '' && parseInt(stok) <= 0) {
            alert('Stok barang habis!');
        }
    });
});
</script>
@endpush
@endsection
