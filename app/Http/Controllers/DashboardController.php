<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // ============ DATA MASTER ============
            $totalBarang = DB::select("SELECT COUNT(*) as total FROM barang")[0]->total ?? 0;
            
            // fn_total_barang_by_status - Hitung total barang aktif
            $totalBarangAktif = DB::select("SELECT fn_total_barang_by_status(1) as total")[0]->total ?? 0;
            
            $totalVendor = DB::select("SELECT COUNT(*) as total FROM vendor")[0]->total ?? 0;
            $totalUser = DB::select("SELECT COUNT(*) as total FROM user")[0]->total ?? 0;

            // ============ DATA PENGADAAN ============
            $pengadaanData = DB::select("
                SELECT 
                    COUNT(*) as total_pengadaan,
                    SUM(CASE WHEN status = 'P' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'S' THEN 1 ELSE 0 END) as selesai,
                    SUM(total_nilai) as total_nilai
                FROM pengadaan
            ")[0];
            
            $totalPengadaan = $pengadaanData->total_pengadaan ?? 0;
            $pengadaanPending = $pengadaanData->pending ?? 0;
            $pengadaanSelesai = $pengadaanData->selesai ?? 0;
            $totalNilaiPengadaan = $pengadaanData->total_nilai ?? 0;

            // ============ DATA PENERIMAAN ============
            $penerimaanData = DB::select("
                SELECT 
                    COUNT(*) as total_penerimaan,
                    SUM(CASE WHEN status = 'P' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'S' THEN 1 ELSE 0 END) as selesai
                FROM penerimaan
            ")[0];
            
            $totalPenerimaan = $penerimaanData->total_penerimaan ?? 0;
            $penerimaanPending = $penerimaanData->pending ?? 0;
            $penerimaanSelesai = $penerimaanData->selesai ?? 0;

            $totalNilaiPenerimaan = DB::select("
                SELECT COALESCE(SUM(dp.sub_total_terima), 0) as total 
                FROM detail_penerimaan dp
            ")[0]->total ?? 0;

            // ============ DATA PENJUALAN ============
            $penjualanData = DB::select("
                SELECT 
                    COUNT(*) as total_penjualan,
                    SUM(total_nilai) as total_nilai
                FROM penjualan
            ")[0];
            
            $totalPenjualan = $penjualanData->total_penjualan ?? 0;
            $totalNilaiPenjualan = $penjualanData->total_nilai ?? 0;

            $totalMarginPenjualan = DB::select("
                SELECT COALESCE(SUM(p.subtotal_nilai * (m.persen / 100)), 0) as total_margin 
                FROM penjualan p 
                JOIN margin_penjualan m ON p.idmargin_penjualan = m.idmargin_penjualan
            ")[0]->total_margin ?? 0;

            // ============ STOK BARANG ============
            $stokBarang = DB::select("
                SELECT * FROM v_barang_stok_terakhir 
                WHERE status = 1 
                ORDER BY stok DESC 
                LIMIT 5
            ");

            $stokMinimal = DB::select("
                SELECT COUNT(*) as total FROM v_barang_stok_terakhir 
                WHERE stok < 10 AND status = 1
            ")[0]->total ?? 0;

            // ============ DATA TERBARU ============
            $pengadaanTerbaru = DB::select("
                SELECT * FROM v_pengadaan_all 
                ORDER BY tanggal_pengadaan DESC 
                LIMIT 5
            ");

            $penjualanTerbaru = DB::select("
                SELECT * FROM v_penjualan_all 
                ORDER BY tanggal_penjualan DESC 
                LIMIT 5
            ");

            $penerimaanTerbaru = DB::select("
                SELECT * FROM v_penerimaan_all 
                ORDER BY tanggal_penerimaan DESC 
                LIMIT 5
            ");

            // ============ PERFORMA BULAN INI ============
            $bulanIni = now()->month;
            $tahunIni = now()->year;

            // fn_total_penjualan_perbulan - Hitung total penjualan bulan ini
            $penjualanBulanIni = DB::select("
                SELECT fn_total_penjualan_perbulan(?, ?) as total
            ", [$bulanIni, $tahunIni])[0]->total ?? 0;

            // fn_total_pengadaan_perbulan - Hitung total pengadaan bulan ini
            $pengadaanBulanIni = DB::select("
                SELECT fn_total_pengadaan_perbulan(?, ?) as total
            ", [$bulanIni, $tahunIni])[0]->total ?? 0;

            // fn_total_penerimaan_perbulan - Hitung total penerimaan bulan ini
            $penerimaanBulanIni = DB::select("
                SELECT fn_total_penerimaan_perbulan(?, ?) as total
            ", [$bulanIni, $tahunIni])[0]->total ?? 0;

            // ============ TOP VENDOR ============
            $topVendor = DB::select("
                SELECT 
                    v.idvendor, 
                    v.nama_vendor, 
                    COUNT(*) as jumlah_transaksi, 
                    SUM(p.total_nilai) as total_nilai
                FROM pengadaan p
                JOIN vendor v ON p.vendor_idvendor = v.idvendor
                GROUP BY v.idvendor, v.nama_vendor
                ORDER BY total_nilai DESC
                LIMIT 5
            ");

            // ============ TOP PRODUK ============
            $topProduk = DB::select("
                SELECT 
                    b.idbarang, 
                    b.nama, 
                    SUM(dp.jumlah) as total_terjual, 
                    SUM(dp.subtotal) as total_nilai
                FROM detail_penjualan dp
                JOIN barang b ON dp.idbarang = b.idbarang
                GROUP BY b.idbarang, b.nama
                ORDER BY total_terjual DESC
                LIMIT 5
            ");

            return view('dashboard', compact(
                'totalBarang', 'totalBarangAktif', 'totalVendor', 'totalUser',
                'totalPengadaan', 'pengadaanPending', 'pengadaanSelesai', 'totalNilaiPengadaan',
                'totalPenerimaan', 'penerimaanPending', 'penerimaanSelesai', 'totalNilaiPenerimaan',
                'totalPenjualan', 'totalNilaiPenjualan', 'totalMarginPenjualan',
                'stokBarang', 'stokMinimal',
                'pengadaanTerbaru', 'penjualanTerbaru', 'penerimaanTerbaru',
                'penjualanBulanIni', 'pengadaanBulanIni', 'penerimaanBulanIni',
                'topVendor', 'topProduk'
            ));
        } catch (\Exception $e) {
            return view('dashboard.error', [
                'message' => 'Error loading dashboard: ' . $e->getMessage()
            ]);
        }
    }
}