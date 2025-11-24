@extends('layouts.app')

@section('title', 'Edit Vendor')
@section('page-title', 'Edit Vendor')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Form Edit Vendor</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('vendor.update', $vendor->idvendor) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="idvendor" class="form-label">ID Vendor</label>
                <input type="text" class="form-control" id="idvendor" name="idvendor"
                       value="{{ $vendor->idvendor }}" readonly>
            </div>

            <div class="mb-3">
                <label for="nama_vendor" class="form-label">Nama Vendor</label>
                <input type="text" class="form-control" id="nama_vendor" name="nama_vendor"
                       value="{{ $vendor->nama_vendor }}" maxlength="100" required>
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3" required>{{ $vendor->alamat }}</textarea>
            </div>

            <div class="mb-3">
                <label for="telepon" class="form-label">Telepon</label>
                <input type="text" class="form-control" id="telepon" name="telepon"
                       value="{{ $vendor->telepon }}" maxlength="15" required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="status" name="status" value="1"
                        {{ $vendor->status == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="status">
                        Aktif
                    </label>
                </div>
            </div>

            <a href="{{ route('vendor.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-warning">Update</button>
        </form>
    </div>
</div>
@endsection
