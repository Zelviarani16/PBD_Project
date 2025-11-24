@extends('layouts.app')

@section('title', 'Tambah Penerimaan')
@section('page-title', 'Form Tambah Penerimaan')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Form Tambah Penerimaan</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('penerimaan.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="idpengadaan" class="form-label">Pilih Pengadaan (Pending)</label>
                <select name="idpengadaan" id="idpengadaan" class="form-select" required>
                    <option value="">-- Pilih Pengadaan --</option>
                    @foreach($pengadaans as $p)
                        <option value="{{ $p->idpengadaan }}">
                            {{ $p->idpengadaan }} | Total: Rp {{ number_format($p->total_nilai,0,',','.') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="user_iduser" class="form-label">User Pembuat</label>
                <input type="text" class="form-control" value="{{ $currentUser['username'] }}" readonly>
                <input type="hidden" name="user_iduser" value="{{ $currentUser['iduser'] }}">
            </div>

            <a href="{{ route('penerimaan.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection
