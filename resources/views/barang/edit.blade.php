@extends('layouts.app')

@section('title', 'Edit Barang')
@section('page-title', 'Edit Barang')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Form Edit Barang</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('barang.update', $barang->idbarang) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="idbarang">ID Barang</label>
                <input type="text" class="form-control" name="idbarang" id="idbarang" value="{{ $barang->idbarang }}" readonly>
            </div>

            <div class="mb-3">
                <label for="nama">Nama Barang</label>
                <input type="text" class="form-control" name="nama" id="nama" value="{{ $barang->nama_barang }}">
            </div>

            <div class="mb-3">
                <label for="jenis">Jenis</label>
                <input type="text" class="form-control" name="jenis" id="jenis" value="{{ $barang->jenis }}">
            </div>

            <div class="mb-3">
                <label for="idsatuan">Satuan</label>
                <select class="form-select" name="idsatuan" id="idsatuan">
                    @foreach($satuan as $s)
                        <option value="{{ $s->idsatuan }}" {{ $s->nama_satuan == $barang->nama_satuan ? 'selected' : '' }}>
                            {{ $s->nama_satuan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="harga">Harga</label>
                <input type="number" class="form-control" name="harga" id="harga" value="{{ $barang->harga }}">
            </div>

            <div class="mb-3">
            <label for="status">Status</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ $barang->status_text == 'Aktif' ? 'checked' : '' }}>
                <label class="form-check-label" for="status">
                    Aktif
                </label>
            </div>
        </div>

            <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-warning">Update</button>
        </form>
    </div>
</div>
@endsection
