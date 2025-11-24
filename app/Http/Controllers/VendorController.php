<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    // =============================
    //  TAMPILKAN SEMUA DATA VENDOR (PAKAI VIEW)
    // =============================
    public function index()
    {
        // Ambil semua vendor (aktif & tidak aktif) dari view
        $vendor = DB::select('SELECT * FROM v_vendor_all ORDER BY nama_vendor ASC');

        return view('vendor.index', compact('vendor'));
    }

    // =============================
    //  FORM TAMBAH VENDOR
    // =============================
    public function create()
    {
        // Vendor tidak punya relasi, jadi langsung tampilkan form kosong
        return view('vendor.create');
    }

    // =============================
    //  SIMPAN DATA BARU (PAKAI SP)
    // =============================
    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'idvendor' => 'required|max:10',
            'nama_vendor' => 'required|max:100',
            'alamat' => 'required|max:255',
            'telepon' => 'required|max:15',
        ]);

        // Ambil data dari form input
        $idvendor = $request->idvendor;
        $nama_vendor = $request->nama_vendor;
        $alamat = $request->alamat;
        $telepon = $request->telepon;
        $status = $request->has('status') ? 1 : 0;

        // Panggil Stored Procedure untuk menambah vendor
        DB::statement('CALL sp_tambah_vendor(?, ?, ?, ?, ?)', [
            $idvendor,
            $nama_vendor,
            $alamat,
            $telepon,
            $status
        ]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil ditambahkan!');
    }

    // =============================
    //  FORM EDIT VENDOR
    // =============================
    public function edit($id)
    {
        // Ambil data vendor berdasarkan ID dari view
        $vendor = DB::select('SELECT * FROM v_vendor_all WHERE idvendor = ?', [$id]);

        if (count($vendor) > 0) {
            $vendor = $vendor[0];
        } else {
            abort(404);
        }

        return view('vendor.edit', compact('vendor'));
    }

    // =============================
    //  UPDATE DATA (PAKAI SP)
    // =============================
    public function update(Request $request, $id)
    {
        // Validasi form
        $request->validate([
            'nama_vendor' => 'required|max:100',
            'alamat' => 'required|max:255',
            'telepon' => 'required|max:15',
        ]);

        // Ambil data dari form
        $idvendor = $id;
        $nama_vendor = $request->nama_vendor;
        $alamat = $request->alamat;
        $telepon = $request->telepon;
        $status = $request->has('status') ? 1 : 0;

        // Panggil Stored Procedure update vendor
        DB::statement('CALL sp_update_vendor(?, ?, ?, ?, ?)', [
            $idvendor,
            $nama_vendor,
            $alamat,
            $telepon,
            $status
        ]);

        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil diperbarui!');
    }

    // =============================
    //  HAPUS DATA (PAKAI SP)
    // =============================
    public function destroy($id)
    {
        try {
            // Jalankan SP hapus vendor
            DB::statement('CALL sp_hapus_vendor(?)', [$id]);

            return redirect()->route('vendor.index')->with('success', 'Vendor berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('vendor.index')->with('error', 'Gagal menghapus vendor. Data mungkin sedang digunakan.');
        }
    }

    // =============================
    //  FUNCTION: JUMLAH VENDOR BERDASARKAN STATUS
    // =============================
    public function totalVendorByStatus($status = 'semua')
    {
        // Panggil function fn_total_vendor_by_status
        $total = DB::select('SELECT fn_total_vendor_by_status(?) AS total', [$status]);

        return response()->json(['total' => $total[0]->total ?? 0]);
    }
}
