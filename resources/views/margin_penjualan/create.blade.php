@extends('layouts.app')

@section('title', 'Tambah Margin Penjualan')
@section('page-title', 'Tambah Margin Penjualan Baru')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Form Tambah Margin Penjualan</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('margin.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="idmargin_penjualan" class="form-label">ID Margin Penjualan</label>
                <input type="text" class="form-control" id="idmargin_penjualan" name="idmargin_penjualan" 
                       value="{{ old('idmargin_penjualan') }}" maxlength="10" required>
            </div>

            <div class="mb-3">
                <label for="persen" class="form-label">Persentase Margin (%)</label>
                <input type="number" step="0.01" class="form-control" id="persen" name="persen" 
                       value="{{ old('persen') }}" min="0" required>
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" 
                       value="{{ old('username') }}" maxlength="45" required>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="1" id="status" name="status" checked>
                <label class="form-check-label" for="status">
                    Aktif
                </label>
            </div>

            <a href="{{ route('margin.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection
