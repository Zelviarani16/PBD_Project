@extends('layouts.app')

@section('title', 'Edit Margin Penjualan')
@section('page-title', 'Form Edit Margin Penjualan')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h3>Edit Margin Penjualan</h3>
        <p>Ubah data margin penjualan sesuai kebutuhan.</p>
    </div>
    <a href="{{ route('margin_penjualan.index') }}" class="btn btn-secondary">
        <i class='bx bx-arrow-back'></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('margin_penjualan.update', $margin->idmargin_penjualan) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="persen" class="form-label">Persentase Margin (%)</label>
                <input type="number" step="0.01" class="form-control" id="persen" name="persen"
                    value="{{ $margin->persen }}" required>
            </div>

            <div class="mb-3">
                <label for="iduser" class="form-label">Dibuat Oleh (User)</label>
                <select name="iduser" id="iduser" class="form-select" required>
                    @foreach($users as $user)
                        <option value="{{ $user->iduser }}" {{ $margin->iduser == $user->iduser ? 'selected' : '' }}>
                            {{ $user->username }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select" required>
                    <option value="1" {{ $margin->status_text === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ $margin->status_text === 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-warning">
                    <i class='bx bx-edit'></i> Perbarui
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
