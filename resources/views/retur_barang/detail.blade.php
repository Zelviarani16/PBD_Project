@extends('layouts.app')

@section('title', 'Detail Retur Barang')
@section('page-title', 'Detail Retur Barang')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Detail Retur #{{ $retur->idretur }}</h5>
    </div>
    <div class="card-body">

        {{-- Header Retur --}}
        <table class="table table-borderless">
            <tr><th>ID Retur</th><td>{{ $retur->idretur }}</td></tr>
            <tr><th>Tanggal</th><td>{{ $retur->tanggal_retur }}</td></tr>
            <tr><th>Penerimaan</th><td>{{ $retur->idpenerimaan }}</td></tr>
            <tr><th>Vendor</th><td>{{ $retur->vendor }}</td></tr>
            <tr><th>Petugas</th><td>{{ $retur->petugas }}</td></tr>
        </table>

        {{-- Detail --}}
        <h5 class="mt-4">Detail Barang Retur</h5>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Alasan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($details as $index => $d)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $d->nama }}</td>
                    <td>{{ $d->jumlah }}</td>
                    <td>{{ $d->alasan }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Belum ada detail retur.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Form Tambah Detail --}}
        <h5 class="mt-4">Tambah Detail Barang</h5>
        <form action="{{ route('retur.tambahDetail', $retur->idretur) }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="idbarang" class="form-label">Barang</label>
                        <select name="iddetail_penerimaan" id="idbarang" class="form-select" required>
                        <option value="">-- Pilih Barang --</option>
                    @foreach(DB::table('v_retur_create')->where('idpenerimaan', $retur->idpenerimaan)->get() as $b)
                        <option value="{{ $b->iddetail_penerimaan }}" data-stock="{{ $b->stock }}">
                            {{ $b->nama_barang }} 
                            — Diterima: {{ $b->stock }} 
                            (Detail #{{ $b->iddetail_penerimaan }})
                        </option>
                    @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="jumlah" class="form-label">Jumlah</label>
                    <input type="number" class="form-control" name="jumlah" required min="1">
                </div>

                <div class="col-md-4">
                    <label for="alasan" class="form-label">Alasan</label>
                    <input type="text" class="form-control" name="alasan" maxlength="200">
                </div>
            </div>

            <button type="submit" class="btn btn-success mt-3">Tambah Detail</button>
        </form>

        {{-- Back Button --}}
        <div class="text-end mt-4">
            <a href="{{ route('retur.index') }}" class="btn btn-secondary">Kembali</a>
        </div>

    </div>
</div>

{{-- Script validasi stok --}}
<script>
document.getElementById('idbarang').addEventListener('change', function () {
    let stok = this.options[this.selectedIndex].dataset.stock;
    document.querySelector('input[name="jumlah"]').max = stok;
});
</script>

@endsection
