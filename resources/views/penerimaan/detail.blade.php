@extends('layouts.app')

@section('title', 'Detail Penerimaan')
@section('page-title', 'Detail Penerimaan')

@section('content')
<div class="card p-4 rounded-3">
    <h4>Informasi Penerimaan</h4>
    <table class="table table-borderless">
        <tr>
            <th>ID Penerimaan</th>
            <td>{{ $penerimaan->idpenerimaan }}</td>
        </tr>
        <tr>
            <th>ID Pengadaan</th>
            <td>{{ $penerimaan->idpengadaan }}</td>
        </tr>
        <tr>
            <th>User</th>
            <td>{{ $penerimaan->dibuat_oleh ?? '-' }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                <span class="badge
                    @if($penerimaan->status_text == 'Pending') bg-primary text-white
                    @elseif($penerimaan->status_text == 'Sebagian') bg-warning text-dark
                    @elseif($penerimaan->status_text == 'Selesai') bg-success
                    @elseif($penerimaan->status_text == 'Batal') bg-danger
                    @else bg-secondary text-white
                    @endif">
                    {{ $penerimaan->status_text }}
                </span>
            </td>
        </tr>
        <tr>
            <th>Tanggal</th>
            <td>{{ $penerimaan->tanggal_penerimaan }}</td>
        </tr>
    </table>

    <h5 class="mt-4">Detail Barang</h5>
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
                        <td colspan="7" class="text-center text-muted">
                            Belum ada barang diterima.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>


    <h5 class="mt-4">Tambah Barang Terima</h5>
    <form action="{{ route('penerimaan.tambahDetail', $penerimaan->idpenerimaan) }}" method="POST">
        @csrf
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label for="idbarang">Pilih Barang</label>
                <select name="idbarang" id="idbarang" class="form-select" required>
                    <option value="">-- Pilih Barang --</option>
                    @foreach($barangs as $b)
                        @if($b->sisa_qty > 0)
                            <option value="{{ $b->idbarang }}">{{ $b->nama_barang }} (Sisa: {{ $b->sisa_qty }})</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="jumlah">Jumlah Terima</label>
                <input type="number" name="jumlah" id="jumlah" class="form-control" required min="1">
            </div>
            <div class="col-md-3">
                <button class="btn btn-success w-100">Tambah Barang</button>
            </div>
        </div>
    </form>

    <a href="{{ route('penerimaan.index') }}" class="btn btn-secondary mt-4">Kembali</a>
</div>
@endsection
