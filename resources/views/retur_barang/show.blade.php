@extends('layouts.app')

@section('title', 'Detail Retur Barang')
@section('page-title', 'Detail Retur Barang')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Detail Retur Barang</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Alasan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($detail as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->jumlah }}</td>
                        <td>{{ $item->alasan }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">Tidak ada detail retur</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
