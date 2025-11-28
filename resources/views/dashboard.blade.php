@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa;">
    
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2 mb-1">
                <i class="bi bi-speedometer2 text-primary me-2"></i>Dashboard Synvent
            </h1>
            <!-- <p class="text-muted mb-0">{{ now()->format('l, d F Y - H:i') }}</p> -->
        </div>
    </div>

    <!-- KPI Cards - Data Master -->
    <div class="row mb-4">
        <!-- Total Barang -->
        <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Total Barang</p>
                            <h3 class="mb-1">{{ $totalBarang }}</h3>
                            <small class="text-success"><i class="bi bi-check-circle-fill"></i> {{ $totalBarangAktif }} Aktif</small>
                        </div>
                        <!-- <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-box2 text-primary fs-4"></i>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Vendor -->
        <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Total Vendor</p>
                            <h3 class="mb-1">{{ $totalVendor }}</h3>
                            <small class="text-info"><i class="bi bi-building"></i> Mitra Bisnis</small>
                        </div>
                        <!-- <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-shop text-success fs-4"></i>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Total User -->
        <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Total User</p>
                            <h3 class="mb-1">{{ $totalUser }}</h3>
                            <small class="text-warning"><i class="bi bi-person-fill"></i> Pengguna Sistem</small>
                        </div>
                        <!-- <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="bi bi-people text-info fs-4"></i>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Stok Minimal -->
        <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Stok Minimal</p>
                            <h3 class="mb-1 text-danger">{{ $stokMinimal }}</h3>
                            <small class="text-danger"><i class="bi bi-exclamation-triangle-fill"></i> Perlu Restok</small>
                        </div>
                        <!-- <div class="bg-danger bg-opacity-10 p-3 rounded">
                            <i class="bi bi-exclamation-circle text-danger fs-4"></i>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Cards - Transaksi -->
    <div class="row mb-4">
        <!-- Pengadaan -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-top: 3px solid #667eea;">
                <div class="card-body">
                    <h6 class="text-muted mb-3"><i class="bi bi-cart-check text-primary"></i> Pengadaan</h6>
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <h5 class="mb-0">{{ $totalPengadaan }}</h5>
                            <small class="text-muted">Total</small>
                        </div>
                        <div class="col-4">
                            <h5 class="mb-0 text-warning">{{ $pengadaanPending }}</h5>
                            <small class="text-warning">Pending</small>
                        </div>
                        <div class="col-4">
                            <h5 class="mb-0 text-success">{{ $pengadaanSelesai }}</h5>
                            <small class="text-success">Selesai</small>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="small">
                        <p class="mb-2"><strong>Total Nilai:</strong><br>{{ 'Rp ' . number_format($totalNilaiPengadaan, 0, ',', '.') }}</p>
                        <p class="mb-0"><strong>Bulan Ini:</strong><br>{{ 'Rp ' . number_format($pengadaanBulanIni, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Penerimaan -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-top: 3px solid #f5576c;">
                <div class="card-body">
                    <h6 class="text-muted mb-3"><i class="bi bi-inbox text-danger"></i> Penerimaan</h6>
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <h5 class="mb-0">{{ $totalPenerimaan }}</h5>
                            <small class="text-muted">Total</small>
                        </div>
                        <div class="col-4">
                            <h5 class="mb-0 text-warning">{{ $penerimaanPending }}</h5>
                            <small class="text-warning">Pending</small>
                        </div>
                        <div class="col-4">
                            <h5 class="mb-0 text-success">{{ $penerimaanSelesai }}</h5>
                            <small class="text-success">Selesai</small>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="small">
                        <p class="mb-2"><strong>Total Nilai:</strong><br>{{ 'Rp ' . number_format($totalNilaiPenerimaan, 0, ',', '.') }}</p>
                        <p class="mb-0"><strong>Bulan Ini:</strong><br>{{ 'Rp ' . number_format($penerimaanBulanIni, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Penjualan -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-top: 3px solid #00f2fe;">
                <div class="card-body">
                    <h6 class="text-muted mb-3"><i class="bi bi-cash-coin text-info"></i> Penjualan</h6>
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <h5 class="mb-0">{{ $totalPenjualan }}</h5>
                            <small class="text-muted">Transaksi</small>
                        </div>
                        <div class="col-6">
                            <h5 class="mb-0 text-info">{{ 'Rp ' . number_format($totalMarginPenjualan / 1000000, 1, ',', '.') . 'Jt' }}</h5>
                            <small class="text-info">Margin</small>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="small">
                        <p class="mb-0"><strong>Total Penjualan:</strong><br>{{ 'Rp ' . number_format($totalNilaiPenjualan, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tables -->
    <div class="row mb-4">
        <!-- Pengadaan Terbaru -->
        <div class="col-lg-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom p-3">
                    <h6 class="mb-0"><i class="bi bi-clock-history text-primary"></i> Pengadaan Terbaru</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Vendor</th>
                                    <th>Nilai</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengadaanTerbaru as $pengadaan)
                                    <tr>
                                        <td><small>#{{ $pengadaan->idpengadaan }}</small></td>
                                        <td><small>{{ $pengadaan->nama_vendor }}</small></td>
                                        <td><small>{{ 'Rp ' . number_format($pengadaan->total_nilai, 0, ',', '.') }}</small></td>
                                        <td>
                                            @php
                                                $badgeColor = $pengadaan->status_text === 'Pending' ? 'warning' : ($pengadaan->status_text === 'Selesai' ? 'success' : 'info');
                                            @endphp
                                            <span class="badge bg-{{ $badgeColor }}">{{ $pengadaan->status_text }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Penjualan Terbaru -->
        <div class="col-lg-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom p-3">
                    <h6 class="mb-0"><i class="bi bi-clock-history text-success"></i> Penjualan Terbaru</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Kasir</th>
                                    <th>Total</th>
                                    <th>Margin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($penjualanTerbaru as $penjualan)
                                    <tr>
                                        <td><small>#{{ $penjualan->idpenjualan }}</small></td>
                                        <td><small>{{ $penjualan->kasir }}</small></td>
                                        <td><small>{{ 'Rp ' . number_format($penjualan->total_nilai, 0, ',', '.') }}</small></td>
                                        <td><small class="text-success">{{ 'Rp ' . number_format($penjualan->margin_nilai, 0, ',', '.') }}</small></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Barang Stok Tinggi -->
        <div class="col-lg-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom p-3">
                    <h6 class="mb-0"><i class="bi bi-box2 text-success"></i> Barang Stok Tinggi</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Barang</th>
                                    <th>Stok</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stokBarang as $barang)
                                    <tr>
                                        <td><small>{{ $barang->nama }}</small></td>
                                        <td><small><strong>{{ $barang->stok }}</strong></small></td>
                                        <td><small>{{ 'Rp ' . number_format($barang->harga, 0, ',', '.') }}</small></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Penerimaan Terbaru -->
        <div class="col-lg-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom p-3">
                    <h6 class="mb-0"><i class="bi bi-inbox text-info"></i> Penerimaan Terbaru</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Dibuat Oleh</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($penerimaanTerbaru as $penerimaan)
                                    <tr>
                                        <td><small>#{{ $penerimaan->idpenerimaan }}</small></td>
                                        <td><small>{{ $penerimaan->dibuat_oleh }}</small></td>
                                        <td><small>{{ \Carbon\Carbon::parse($penerimaan->tanggal_penerimaan)->format('d/m/Y') }}</small></td>
                                        <td>
                                            @php
                                                $badgeColor = $penerimaan->status_text === 'Pending' ? 'warning' : ($penerimaan->status_text === 'Selesai' ? 'success' : 'info');
                                            @endphp
                                            <span class="badge bg-{{ $badgeColor }}">{{ $penerimaan->status_text }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top Vendor -->
        <div class="col-lg-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom p-3">
                    <h6 class="mb-0"><i class="bi bi-shop text-warning"></i> Top 5 Vendor</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Vendor</th>
                                    <th>Transaksi</th>
                                    <th>Total Pembelian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topVendor as $vendor)
                                    <tr>
                                        <td><small>{{ $vendor->nama_vendor }}</small></td>
                                        <td><span class="badge bg-secondary">{{ $vendor->jumlah_transaksi }}</span></td>
                                        <td><small>{{ 'Rp ' . number_format($vendor->total_nilai, 0, ',', '.') }}</small></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Produk -->
        <div class="col-lg-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom p-3">
                    <h6 class="mb-0"><i class="bi bi-box2-heart text-info"></i> Top 5 Produk Penjualan</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Terjual</th>
                                    <th>Total Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topProduk as $produk)
                                    <tr>
                                        <td><small>{{ $produk->nama }}</small></td>
                                        <td><span class="badge bg-success">{{ $produk->total_terjual }} pcs</span></td>
                                        <td><small>{{ 'Rp ' . number_format($produk->total_nilai, 0, ',', '.') }}</small></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection