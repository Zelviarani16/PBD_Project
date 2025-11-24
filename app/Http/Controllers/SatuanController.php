<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SatuanController extends Controller
{
    // ========================
    // TAMPIL SEMUA DATA SATUAN
    // ========================
    public function index()
    {
        // Ambil semua data satuan aktif & tidak aktif dari view v_satuan_all
        $satuan = DB::select('SELECT * FROM v_satuan_all ORDER BY nama_satuan ASC');

        // Kirim ke view
        return view('satuan.index', compact('satuan'));
    }

    // ========================
    // FORM TAMBAH SATUAN
    // ========================
    public function create()
    {
        // Tidak perlu ambil data lain karena hanya input nama dan status
        return view('satuan.create');
    }

    // ========================
    // SIMPAN SATUAN BARU
    // ========================
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'idsatuan' => 'required|max:10',
            'nama_satuan' => 'required|max:45',
        ]);

        // Ambil inputan dari form
        $idsatuan = $request->idsatuan;
        $nama_satuan = $request->nama_satuan;
        $status = $request->has('status') ? 1 : 0;

        // Panggil SP untuk tambah satuan
        DB::statement('CALL sp_tambah_satuan(?, ?, ?)', [
            $idsatuan,
            $nama_satuan,
            $status
        ]);

        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil ditambahkan!');
    }

    // ========================
    // FORM EDIT SATUAN
    // ========================
    public function edit($id)
    {
        // Ambil data satuan dari view berdasarkan id
        $satuan = DB::select('SELECT * FROM v_satuan_all WHERE idsatuan = ?', [$id]);

        if (count($satuan) > 0) {
            $satuan = $satuan[0];
        } else {
            abort(404);
        }

        return view('satuan.edit', compact('satuan'));
    }

    // ========================
    // UPDATE DATA SATUAN
    // ========================
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_satuan' => 'required|max:45',
        ]);

        $idsatuan = $id;
        $nama_satuan = $request->nama_satuan;
        $status = $request->has('status') ? 1 : 0;

        // Panggil SP update
        DB::statement('CALL sp_update_satuan(?, ?, ?)', [
            $idsatuan,
            $nama_satuan,
            $status
        ]);

        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil diperbarui!');
    }

    // ========================
    // HAPUS DATA SATUAN
    // ========================
    public function destroy($id)
    {
        try {
            DB::statement('CALL sp_hapus_satuan(?)', [$id]);
            return redirect()->route('satuan.index')->with('success', 'Satuan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('satuan.index')->with('error', 'Gagal menghapus satuan. Data mungkin sedang digunakan.');
        }
    }

    // ========================
    // JUMLAH SATUAN BERDASARKAN STATUS (FUNCTION)
    // ========================
    public function totalSatuanByStatus($status = 'semua')
    {
        $total = DB::select('SELECT fn_total_satuan_by_status(?) AS total', [$status]);
        return response()->json(['total' => $total[0]->total ?? 0]);
    }
}
