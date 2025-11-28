@extends('layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit Data User')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Form Edit User</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('user.update', $user->iduser) }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="iduser" class="form-label">ID User</label>
                <input type="text" class="form-control" id="iduser" name="iduser" value="{{ $user->iduser }}" readonly>
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password (Kosongkan jika tidak diubah)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select name="idrole" id="role" class="form-control" required>
                    @foreach($role as $r)
                        <option value="{{ $r->idrole }}" {{ $r->idrole == $user->idrole ? 'selected' : '' }}>
                            {{ $r->nama_role }}
                        </option>
                    @endforeach
                </select>
            </div>

            <a href="{{ route('user.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-warning">Update</button>
        </form>
    </div>
</div>
@endsection
