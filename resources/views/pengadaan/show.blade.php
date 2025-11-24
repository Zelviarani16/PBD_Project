@extends('layouts.app')

@section('title', 'Detail Pengadaan')
@section('page-title', 'Detail Transaksi Pengadaan')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Detail Pengadaan #{{ $detail[0]->idpengadaan ?? '-' }}</h5>
        <a href="{{ route('pengadaan.index') }}" class="btn btn-secondary btn-sm">
            <i class='bx bx-arrow-back'></i> Kembali
        </a>
    </div>

    <div class="card-body">
        @if(count($detail) > 0)
        <div class="mb-3">
            <p><strong>Vendor:</strong> {{ $detail[0]->nama_vendor }}</p>
            <p><strong>Tanggal Pengadaan:</strong> {{ $detail[0]->tanggal_pengadaan }}</p>
            <p>
                <strong>Status:</strong>
                @if($detail[0]->status == '1')
                    <span class="badge bg-success">Selesai</span>
                @elseif($detail[0]->status == '0')
                    <span class="badge bg-warning text-dark">Pending</span>
                @else
                    <span class="badge bg-danger">Batal</span>
                @endif
            </p>
            <p><strong>Dibuat Oleh:</strong> {{ $detail[0]->dibuat_oleh }}</p>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Sub Total</th>
                        <th>Total per Barang</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($detail as $index => $d)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $d->nama_barang }}</td>
                        <td>{{ $d->jumlah }}</td>
                        <td>Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($d->sub_total, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($d->total_per_barang, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-end mt-3">
            <h5><strong>Total Pengadaan:</strong> Rp {{ number_format($detail[0]->total_pengadaan, 0, ',', '.') }}</h5>
        </div>
        @else
        <p class="text-center text-muted">Tidak ada detail untuk transaksi ini.</p>
        @endif
    </div>
</div>
@endsection
