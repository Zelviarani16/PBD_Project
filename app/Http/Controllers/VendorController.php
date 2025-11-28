<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
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
    //  SIMPAN DATA BARU 
    // =============================
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'idvendor' => 'required|max:10',
            'nama_vendor' => 'required|max:100',
            'badan_hukum' => 'required|in:P,C',
            'status' => 'nullable',
        ]);

        // Insert langsung ke DB
        DB::table('vendor')->insert([
            'idvendor' => $request->idvendor,
            'nama_vendor' => $request->nama_vendor,
            'badan_hukum' => $request->badan_hukum,
            'status' => $request->has('status') ? '1' : '0', // char(1)
        ]);

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
    //  UPDATE DATA 
    // =============================
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_vendor' => 'required|max:100',
            'badan_hukum' => 'required|in:P,C',
            'status' => 'nullable',
        ]);

        DB::table('vendor')->where('idvendor', $id)->update([
            'nama_vendor' => $request->nama_vendor,
            'badan_hukum' => $request->badan_hukum,
            'status' => $request->has('status') ? '1' : '0',
        ]);

        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil diperbarui!');
    }

    // =============================
    //  HAPUS DATA 
    // =============================
    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();

        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil dihapus');
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
