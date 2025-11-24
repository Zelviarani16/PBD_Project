@extends('layouts.app')

@section('title', 'Tambah Barang')
@section('page-title', 'Tambah Barang Baru')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Form Tambah Barang</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('barang.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="idbarang" class="form-label">ID Barang</label>
                <input type="text" class="form-control" id="idbarang" name="idbarang" value="{{ old('idbarang') }}" maxlength="10" required>
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Barang</label>
                <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}" maxlength="45" required>
            </div>
            <div class="mb-3">
                <label for="jenis" class="form-label">Jenis</label>
                <select name="jenis" id="jenis" class="form-select" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="A" {{ old('jenis')=='A' ? 'selected' : '' }}>A</option>
                    <option value="B" {{ old('jenis')=='B' ? 'selected' : '' }}>B</option>
                    <option value="C" {{ old('jenis')=='C' ? 'selected' : '' }}>C</option>
                    <!-- <option value="1" {{ old('jenis')=='1' ? 'selected' : '' }}>1</option> -->
                </select>
            </div>
            <div class="mb-3">
                <label for="idsatuan" class="form-label">Satuan</label>
                <select name="idsatuan" id="idsatuan" class="form-select" required>
                    <option value="">-- Pilih Satuan --</option>
                    @foreach($satuan as $s)
                        <option value="{{ $s->idsatuan }}">{{ $s->nama_satuan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="1" id="status" name="status" checked>
                <label class="form-check-label" for="status">
                    Aktif
                </label>
            </div>
            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" class="form-control" id="harga" name="harga" value="{{ old('harga') }}" min="0" required>
            </div>
            <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection
