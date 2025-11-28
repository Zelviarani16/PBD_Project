@extends('layouts.app')

@section('title', 'Detail Penerimaan')
@section('page-title', 'Detail Penerimaan')

@section('content')
@php
    $isEditable = !$penerimaan->is_final;
@endphp
<div class="card shadow-lg p-4 rounded-3">

    {{-- Informasi Penerimaan --}}
    <div class="mb-4">
        <h4 class="fw-bold">Informasi Penerimaan</h4>
        <table class="table table-borderless">
            <tr><th>ID Penerimaan</th><td>{{ $penerimaan->idpenerimaan }}</td></tr>
            <tr><th>ID Pengadaan</th><td>{{ $penerimaan->idpengadaan }}</td></tr>
            <tr><th>User</th><td>{{ $penerimaan->dibuat_oleh ?? '-' }}</td></tr>
            <tr>
                <th>Status</th>
                <td>
                    <span class="badge
                        @if($penerimaan->status_text == 'Pending') bg-warning text-dark
                        @elseif($penerimaan->status_text == 'Sebagian') bg-info text-dark
                        @elseif($penerimaan->status_text == 'Selesai') bg-success
                        @elseif($penerimaan->status_text == 'Batal') bg-danger
                        @else bg-secondary text-white
                        @endif">
                        {{ $penerimaan->status_text }}
                    </span>
                </td>
            </tr>
            <tr><th>Tanggal</th><td>{{ $penerimaan->tanggal_penerimaan }}</td></tr>
        </table>
    </div>

    {{-- Detail Barang --}}
    <div class="mt-4">
        <h5 class="fw-bold mb-3">Detail Barang Penerimaan</h5>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Harga Satuan</th>
                        <th>Jumlah Pengadaan</th>
                        <th>Jumlah Terima</th>
                        <th>Sisa</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangs as $index => $b)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $b->nama_barang }}</td>
                            <td>Rp {{ number_format($b->harga_satuan,0,',','.') }}</td>
                            <td>{{ $b->jumlah_pengadaan }}</td>
                            <td>{{ $b->jumlah_terima_total }}</td>
                            <td>{{ $b->sisa_qty }}</td>
                            <td>Rp {{ number_format($b->harga_satuan * $b->jumlah_terima_total,0,',','.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada barang diterima.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($isEditable)
        {{-- Form tambah barang --}}
        <div class="mt-5">
            <h5 class="fw-bold mb-3">Tambah Barang ke Penerimaan</h5>
            <form action="{{ route('penerimaan.tambahDetail', $penerimaan->idpenerimaan) }}" method="POST">
                @csrf
                <fieldset>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="idbarang" class="form-label">Pilih Barang</label>
                            <select name="idbarang" id="idbarang" class="form-select" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barangs as $b)
                                    @if($b->sisa_qty > 0)
                                        <option value="{{ $b->idbarang }}" data-harga="{{ $b->harga_satuan }}">
                                            {{ $b->nama_barang }} (Sisa: {{ $b->sisa_qty }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="harga_satuan" class="form-label">Harga Satuan</label>
                            <input type="number" name="harga_satuan" id="harga_satuan" class="form-control" readonly>
                        </div>
                        <div class="col-md-2">
                            <label for="jumlah" class="form-label">Jumlah Terima</label>
                            <input type="number" name="jumlah_diterima" id="jumlah" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-plus-circle"></i> Tambah Barang
                            </button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>

        {{-- Tombol Simpan / Finalisasi --}}
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('penerimaan.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <form action="{{ route('penerimaan.finalize', $penerimaan->idpenerimaan) }}" 
                  method="POST" 
                  onsubmit="return confirm('Yakin ingin menyimpan penerimaan ini? Setelah disimpan tidak bisa diubah lagi.')">
                @csrf
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-save"></i> SIMPAN PENERIMAAN
                </button>
            </form>
        </div>
    @else
        {{-- READ ONLY --}}
        <div class="alert alert-info mt-4">
            <i class="bi bi-info-circle"></i> <strong>Penerimaan sudah difinalisasi.</strong> Tidak bisa menambah atau mengubah barang lagi.
        </div>
        <div class="text-start mt-3">
            <a href="{{ route('penerimaan.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    @endif
</div>

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
