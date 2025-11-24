@extends('layouts.app')

@section('title', 'Tambah Satuan')

@section('content')
<div class="card">
    <div class="card-header">
        <h4><i class='bx bx-plus'></i> Tambah Satuan</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('satuan.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nama_satuan" class="form-label">Nama Satuan</label>
                <input type="text" name="nama_satuan" id="nama_satuan" class="form-control" required>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="status" id="status" checked>
                <label class="form-check-label" for="status">Aktif</label>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('satuan.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
