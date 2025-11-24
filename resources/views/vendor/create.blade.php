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

        <div class="form-group">
            <label>ID Vendor</label>
            <input type="text" name="idvendor" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Nama Vendor</label>
            <input type="text" name="nama_vendor" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Badan Hukum</label>
            <select name="badan_hukum" class="form-control">
                <option value="P">PT</option>
                <option value="C">CV</option>
            </select>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="A">Aktif</option>
                <option value="N">Nonaktif</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>

    </div>
</div>
@endsection
