@extends('layouts.app')

@section('title', 'Tambah Role')
@section('page-title', 'Tambah Role Baru')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Form Tambah Role</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('role.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="idrole" class="form-label">ID Role</label>
                <input type="text" class="form-control" id="idrole" name="idrole" value="{{ old('idrole') }}" maxlength="10" required>
            </div>
            <div class="mb-3">
                <label for="nama_role" class="form-label">Nama Role</label>
                <input type="text" class="form-control" id="nama_role" name="nama_role" value="{{ old('nama_role') }}" maxlength="100" required>
            </div>
            <a href="{{ route('role.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection
