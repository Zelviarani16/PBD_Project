@extends('layouts.app')

@section('title', 'Tambah Pengadaan')
@section('page-title', 'Form Tambah Pengadaan')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Form Tambah Pengadaan</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('pengadaan.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="user_iduser" class="form-label">User Pembuat</label>
                <input type="text" class="form-control" value="{{ $currentUser['username'] }}" readonly>
                <input type="hidden" name="user_iduser" value="{{ $currentUser['iduser'] }}">
            </div>


            <div class="mb-3">
                <label for="vendor_idvendor" class="form-label">Vendor</label>
                <select name="vendor_idvendor" id="vendor_idvendor" class="form-select" required>
                    <option value="">-- Pilih Vendor --</option> 
                    @foreach($vendors as $v)
                        <option value="{{ $v->idvendor }}">{{ $v->nama_vendor }}</option> <!-- pastikan idvendor -->
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="ppn" class="form-label">PPN (%)</label>
                <input type="number" class="form-control" id="ppn" name="ppn" value="10" readonly>
            </div>

            <a href="{{ route('pengadaan.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection
