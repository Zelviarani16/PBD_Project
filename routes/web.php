<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MarginPenjualanController;
use App\Http\Controllers\KartuStokController;
use App\Http\Controllers\PengadaanController;
use App\Http\Controllers\PenerimaanController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ReturBarangController;

// ======================
// LOGIN / LOGOUT (NO AUTH)
// ======================
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return redirect('/login');
});


// ======================
// ROUTE WAJIB LOGIN
// ======================
Route::middleware('authcheck')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ======================
    // BARANG
    // ======================
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');   // <-- ubah ini
    Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy'); // <-- ubah ini

    // ======================
    // SATUAN
    // ======================
    Route::get('/satuan', [SatuanController::class, 'index'])->name('satuan.index');
    Route::get('/satuan/create', [SatuanController::class, 'create'])->name('satuan.create');
    Route::post('/satuan', [SatuanController::class, 'store'])->name('satuan.store');
    Route::get('/satuan/{id}/edit', [SatuanController::class, 'edit'])->name('satuan.edit');
    Route::put('/satuan/{id}', [SatuanController::class, 'update'])->name('satuan.update');
    Route::delete('/satuan/{id}', [SatuanController::class, 'destroy'])->name('satuan.delete');

    // ======================
    // VENDOR
    // ======================
    Route::get('/vendor', [VendorController::class, 'index'])->name('vendor.index');
    Route::get('/vendor/create', [VendorController::class, 'create'])->name('vendor.create');
    Route::post('/vendor/store', [VendorController::class, 'store'])->name('vendor.store');
    Route::get('/vendor/{id}/edit', [VendorController::class, 'edit'])->name('vendor.edit');
    Route::post('/vendor/{id}/update', [VendorController::class, 'update'])->name('vendor.update');
    Route::get('/vendor/{id}/delete', [VendorController::class, 'destroy'])->name('vendor.delete');

    // ======================
    // ROLE
    // ======================
    Route::get('/role', [RoleController::class, 'index'])->name('role.index');
    Route::get('/role/create', [RoleController::class, 'create'])->name('role.create');
    Route::post('/role/store', [RoleController::class, 'store'])->name('role.store');
    Route::get('/role/{id}/edit', [RoleController::class, 'edit'])->name('role.edit');
    Route::post('/role/{id}/update', [RoleController::class, 'update'])->name('role.update');
    Route::get('/role/{id}/delete', [RoleController::class, 'destroy'])->name('role.delete');

    // ======================
    // USER
    // ======================
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/user/{id}/update', [UserController::class, 'update'])->name('user.update');
    Route::get('/user/{id}/delete', [UserController::class, 'destroy'])->name('user.delete');

    // ======================
    // MARGIN PENJUALAN
    // ======================

    Route::get('/margin', [MarginPenjualanController::class, 'index'])->name('margin.index');
    Route::get('/margin/create', [MarginPenjualanController::class, 'create'])->name('margin.create');
    Route::post('/margin/store', [MarginPenjualanController::class, 'store'])->name('margin.store');
    Route::get('/margin/{id}/edit', [MarginPenjualanController::class, 'edit'])->name('margin.edit');
    Route::post('/margin/{id}/update', [MarginPenjualanController::class, 'update'])->name('margin.update');
    Route::get('/margin/{id}/delete', [MarginPenjualanController::class, 'destroy'])->name('margin.delete');

    // ======================
    // RETUR BARANG
    // ======================
    Route::get('/retur', [ReturBarangController::class, 'index'])->name('retur.index');
    Route::get('/retur/create', [ReturBarangController::class, 'create'])->name('retur.create');
    Route::post('/retur/store', [ReturBarangController::class, 'store'])->name('retur.store');
    Route::get('/retur/{id}/detail', [ReturBarangController::class, 'detail'])->name('retur.detail');
    Route::post('/retur/{id}/detail/tambah', [ReturBarangController::class, 'tambahDetail'])->name('retur.tambahDetail');

    // ======================
    // KARTU STOK
    // ======================
    Route::get('/kartu-stok', [KartuStokController::class, 'index'])->name('kartu.index');

    // ======================
    // PENGADAAN
    // ======================
    Route::get('/pengadaan', [PengadaanController::class, 'index'])->name('pengadaan.index');
    Route::get('/pengadaan/create', [PengadaanController::class, 'create'])->name('pengadaan.create');
    Route::post('/pengadaan/store', [PengadaanController::class, 'store'])->name('pengadaan.store');
    Route::get('/pengadaan/{id}/detail', [PengadaanController::class, 'detail'])->name('pengadaan.detail');
    Route::post('/pengadaan/{id}/tambah-detail', [PengadaanController::class, 'tambahDetail'])->name('pengadaan.tambahDetail');
    Route::post('/pengadaan/{id}/selesai', [PengadaanController::class, 'selesai'])->name('pengadaan.selesai');
    Route::post('/pengadaan/{id}/finalize', [PengadaanController::class, 'finalize'])->name('pengadaan.finalize');
    Route::post('/pengadaan/batal/{id}', [PengadaanController::class, 'batal'])->name('pengadaan.batal');


    // ======================
    // PENERIMAAN
    // ======================
    Route::get('/penerimaan', [PenerimaanController::class, 'index'])->name('penerimaan.index');
    Route::get('/penerimaan/create', [PenerimaanController::class, 'create'])->name('penerimaan.create');
    Route::post('/penerimaan/store', [PenerimaanController::class, 'store'])->name('penerimaan.store');
    Route::get('/penerimaan/{id}/detail', [PenerimaanController::class, 'detail'])->name('penerimaan.detail');
    Route::post('/penerimaan/{id}/tambah-detail', [PenerimaanController::class, 'tambahDetail'])->name('penerimaan.tambahDetail');
    Route::get('/penerimaan/{id}/delete', [PenerimaanController::class, 'destroy'])->name('penerimaan.delete');

    // ======================
    // PENJUALAN
    // ======================
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan/create', [PenjualanController::class, 'create'])->name('penjualan.create');
    Route::post('/penjualan/store', [PenjualanController::class, 'store'])->name('penjualan.store');
    Route::get('/penjualan/{id}/detail', [PenjualanController::class, 'detail'])->name('penjualan.detail');
    Route::post('/penjualan/{id}/detail/add', [PenjualanController::class, 'addDetail'])->name('penjualan.detail.add');
    Route::post('/penjualan/detail/update', [PenjualanController::class, 'updateDetail'])->name('penjualan.detail.update');
    Route::post('/penjualan/detail/delete', [PenjualanController::class, 'deleteDetail'])->name('penjualan.detail.delete');
    Route::post('/penjualan/cancel', [PenjualanController::class, 'cancel'])->name('penjualan.cancel');
});
