@extends('layouts.app')

@section('title', 'Edit Role')
@section('page-title', 'Edit Role')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Form Edit Role</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('role.update', $role->idrole) }}" method="POST">
            @csrf
            @method('POST') {{-- Karena route update pakai POST --}}

            <div class="mb-3">
                <label for="idrole" class="form-label">ID Role</label>
                <input type="text" class="form-control" id="idrole" name="idrole" value="{{ $role->idrole }}" readonly>
            </div>

            <div class="mb-3">
                <label for="nama_role" class="form-label">Nama Role</label>
                <input type="text" class="form-control" id="nama_role" name="nama_role" value="{{ $role->nama_role }}" maxlength="100" required>
            </div>

            <a href="{{ route('role.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-warning">Update</button>
        </form>
    </div>
</div>
@endsection
