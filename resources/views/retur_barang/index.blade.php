@extends('layouts.app')

@section('title', 'Data Retur Barang')
@section('page-title', 'Manajemen Retur Barang')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3>Data Retur Barang</h3>
        <p>Kelola semua retur barang dalam sistem</p>
    </div>
    <a href="{{ route('retur.create') }}" class="btn btn-primary">
        <i class='bx bx-plus'></i> Tambah Retur
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Retur</th>
                        <th>Tanggal</th>
                        <th>ID Penerimaan</th>
                        <th>Vendor</th>
                        <th>Petugas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returs as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->idretur }}</td>
                        <td>{{ $item->tanggal_retur }}</td>
                        <td>{{ $item->idpenerimaan }}</td>
                        <td>{{ $item->vendor }}</td>
                        <td>{{ $item->petugas }}</td>
                        <td>
                            <a href="{{ route('retur.detail', $item->idretur) }}" class="btn btn-sm btn-info">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            Belum ada retur barang.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
