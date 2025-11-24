@extends('layouts.app')

@section('title', 'Tambah Retur Barang')
@section('page-title', 'Form Tambah Retur Barang')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Form Tambah Retur</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('retur.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="iduser" class="form-label">Petugas</label>
                <select name="iduser" id="iduser" class="form-select" required>
                    <option value="">-- Pilih Petugas --</option>
                    @foreach(\App\Models\User::all() as $u)
                        <option value="{{ $u->iduser }}">{{ $u->username }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="idpenerimaan" class="form-label">Penerimaan</label>
                <select name="idpenerimaan" id="idpenerimaan" class="form-select" required>
                    <option value="">-- Pilih Penerimaan --</option>
                    @foreach($penerimaans as $p)
                        <option value="{{ $p->idpenerimaan }}">{{ $p->idpenerimaan }} | {{ $p->vendor }}</option>
                    @endforeach
                </select>
            </div>

            <a href="{{ route('retur.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Buat Retur</button>
        </form>
    </div>
</div>
@endsection
