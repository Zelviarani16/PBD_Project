@extends('layouts.app')

@section('title', 'Tambah Penjualan')
@section('page-title', 'Form Tambah Penjualan')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Form Tambah Penjualan</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('penjualan.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="user_iduser" class="form-label">User</label>
                <input type="text" class="form-control" value="{{ $currentUser['username'] }}" readonly>
                <input type="hidden" name="user_iduser" value="{{ $currentUser['iduser'] }}">
            </div>

            <div class="mb-3">
                <label for="idmargin_penjualan" class="form-label">Margin Penjualan (aktif)</label>
                <select name="idmargin_penjualan" id="idmargin_penjualan" class="form-select" required>
                    <option value="">-- Pilih Margin --</option>
                    @foreach($margins as $m)
                        <option value="{{ $m->idmargin_penjualan }}">{{ $m->persen }}%</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="ppn" class="form-label">PPN (%)</label>
                <input type="number" class="form-control" id="ppn" name="ppn" value="10" readonly>
            </div>

            <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Mulai Transaksi</button>
        </form>
    </div>
</div>
@endsection
