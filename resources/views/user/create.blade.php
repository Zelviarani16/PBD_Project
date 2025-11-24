@extends('layouts.app')

@section('title', 'Tambah User')
@section('page-title', 'Tambah User Baru')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Form Tambah User</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('user.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="iduser" class="form-label">ID User</label>
                <input type="text" class="form-control" id="iduser" name="iduser" maxlength="10" required>
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" maxlength="45" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="mb-3">
                <label for="idrole" class="form-label">Role</label>
                <select name="idrole" id="idrole" class="form-select" required>
                    <option value="">-- Pilih Role --</option>
                    @foreach($role as $r)
                        <option value="{{ $r->idrole }}">{{ $r->nama_role }}</option>
                    @endforeach
                </select>
            </div>

            <a href="{{ route('user.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection
