@extends('layouts.app')

@section('title', 'Detail Penerimaan')
@section('page-title', 'Detail Transaksi Penerimaan')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Detail Penerimaan #{{ $detail[0]->idpenerimaan ?? '-' }}</h5>
        <a href="{{ route('penerimaan.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    <div class="card-body">
        @if(count($detail) > 0)
        <p><strong>Tanggal Penerimaan:</strong> {{ $detail[0]->created_at }}</p>
        <p><strong>Penerima:</strong> {{ $detail[0]->penerima }}</p>
        <hr>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Jumlah Terima</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detail as $d)
                <tr>
                    <td>{{ $d->nama_barang }}</td>
                    <td>{{ $d->jumlah_terima }}</td>
                    <td>Rp {{ number_format($d->harga_satuan_terima, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($d->sub_total_terima, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-end mt-3">
            <h5><strong>Total Penerimaan:</strong> Rp {{ number_format($detail[0]->total_penerimaan, 0, ',', '.') }}</h5>
        </div>
        @else
        <p class="text-center text-muted">Tidak ada detail untuk transaksi ini.</p>
        @endif
    </div>
</div>
@endsection
