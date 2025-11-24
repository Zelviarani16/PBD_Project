<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarginPenjualanController extends Controller
{
    // TAMPIL SEMUA DATA (PAKAI VIEW ALL)
    public function index()
    {
        $margins = DB::select('SELECT * FROM v_margin_penjualan_all ORDER BY created_at DESC');
        return view('margin_penjualan.index', compact('margins'));
    }

    // FORM TAMBAH DATA (PAKAI VIEW AKTIF)
    public function create()
    {
        // Ambil margin penjualan yang aktif dari view aktif
        $margin_aktif = DB::select('SELECT * FROM v_margin_penjualan_aktif WHERE status = 1 ORDER BY idmargin_penjualan ASC');

        return view('margin_penjualan.create', compact('margin_aktif'));
    }

    // SIMPAN DATA BARU
    public function store(Request $request)
    {
        $request->validate([
            'idmargin_penjualan' => 'required|max:10',
            'persen' => 'required|numeric|min:0',
            'username' => 'required|max:45',
        ]);

        $idmargin_penjualan = $request->idmargin_penjualan;
        $persen = $request->persen;
        $username = $request->username;
        $status = $request->has('status') ? 1 : 0;

        // Query mentah insert data baru
        DB::statement('INSERT INTO margin_penjualan (idmargin_penjualan, persen, username, created_at, updated_at, status)
                       VALUES (?, ?, ?, NOW(), NOW(), ?)', [
            $idmargin_penjualan, $persen, $username, $status
        ]);

        return redirect()->route('margin_penjualan.index')->with('success', 'Data margin penjualan berhasil ditambahkan!');
    }

    // HAPUS DATA
    public function destroy($id)
    {
        try {
            DB::statement('DELETE FROM margin_penjualan WHERE idmargin_penjualan = ?', [$id]);
            return redirect()->route('margin_penjualan.index')->with('success', 'Data margin penjualan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('margin_penjualan.index')->with('error', 'Gagal menghapus data. Mungkin sedang digunakan.');
        }
    }

    public function edit($id)
{
    // Ambil data margin
    $margin = DB::selectOne("
        SELECT *
        FROM v_margin_penjualan_all
        WHERE idmargin_penjualan = ?
    ", [$id]);

    if (!$margin) {
        return redirect()->route('margin_penjualan.index')
            ->with('error', 'Data tidak ditemukan.');
    }

    // Ambil list user
    $users = DB::select("
        SELECT iduser, username 
        FROM user
    ");

    return view('margin_penjualan.edit', compact('margin', 'users'));
}


public function update(Request $request, $id)
{
    $request->validate([
        'persen' => 'required|numeric|min:0',
        'iduser' => 'required',
        'status' => 'required|in:0,1',
    ]);

    DB::update("
        UPDATE margin_penjualan
        SET 
            persen = ?,
            iduser = ?,
            status = ?,
            updated_at = NOW()
        WHERE idmargin_penjualan = ?
    ", [
        $request->persen,
        $request->iduser,
        $request->status,
        $id
    ]);

    return redirect()->route('margin_penjualan.index')
        ->with('success', 'Data margin penjualan berhasil diperbarui!');
}

}
