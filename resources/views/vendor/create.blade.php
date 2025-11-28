@extends('layouts.app')

@section('title', 'Tambah Vendor')
@section('page-title', 'Tambah Vendor Baru')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Form Tambah Vendor</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('vendor.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="idvendor" class="form-label">ID Vendor</label>
                <input type="text" class="form-control" id="idvendor" name="idvendor" maxlength="10" required>
            </div>

            <div class="mb-3">
                <label for="nama_vendor" class="form-label">Nama Vendor</label>
                <input type="text" class="form-control" id="nama_vendor" name="nama_vendor" maxlength="100" required>
            </div>

            <div class="mb-3">
                <label for="badan_hukum" class="form-label">Badan Hukum</label>
                <select name="badan_hukum" id="badan_hukum" class="form-select" required>
                    <option value="P">PT</option>
                    <option value="C">CV</option>
                </select>
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="status" name="status" value="1" checked>
                    <label class="form-check-label" for="status">Aktif</label>
                </div>
            </div>

            <a href="{{ route('vendor.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection
