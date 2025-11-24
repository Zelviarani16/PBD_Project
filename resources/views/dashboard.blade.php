@extends('layouts.app')

@section('title', 'Dashboard Superadmin')

@section('content')
<h3>📊 Dashboard</h3>
<p>Selamat datang di halaman Super Admin!</p>

<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Barang</h5>
                <p class="card-text">{{ $totalBarang }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Pengadaan</h5>
                <p class="card-text">{{ $totalPengadaan }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Vendor</h5>
                <p class="card-text">{{ $totalVendor }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
