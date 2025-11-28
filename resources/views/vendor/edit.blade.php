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
                <input type="text" class="form-control" id="idvendor" name="idvendor" value="{{ $vendor->idvendor }}" readonly>
            </div>

            <div class="mb-3">
                <label for="nama_vendor" class="form-label">Nama Vendor</label>
                <input type="text" class="form-control" id="nama_vendor" name="nama_vendor" value="{{ $vendor->nama_vendor }}" maxlength="100" required>
            </div>

            <div class="mb-3">
                <label for="badan_hukum" class="form-label">Badan Hukum</label>
                <select name="badan_hukum" id="badan_hukum" class="form-select" required>
                    <option value="P" {{ $vendor->badan_hukum === 'P' ? 'selected' : '' }}>PT</option>
                    <option value="C" {{ $vendor->badan_hukum === 'C' ? 'selected' : '' }}>CV</option>
                </select>
            </div>

            <div class="mb-3">
                <div class="form-check">
                    {{-- Gunakan null coalescing agar tidak error jika kolom tidak ada --}}
                    <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ ($vendor->status ?? '0') === '1' ? 'checked' : '' }}>
                    <label class="form-check-label" for="status">Aktif</label>
                </div>
            </div>

            <a href="{{ route('vendor.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-warning">Update</button>
        </form>
    </div>
</div>
@endsection
