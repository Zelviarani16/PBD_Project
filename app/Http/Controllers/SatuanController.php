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
            'nama_satuan' => 'required|max:45',
        ]);

        // AUTO GENERATE IDSATUAN: AB1, AB2, AB3 ...
        // Ambil ID terakhir
        $last = DB::select("SELECT idsatuan FROM satuan ORDER BY idsatuan DESC LIMIT 1");

        if (count($last) > 0) {
            // Ambil angka di belakang AB
            $lastNumber = (int) substr($last[0]->idsatuan, 2); 
            $newNumber = $lastNumber + 1;
        } else {
            // Jika tabel masih kosong → mulai dari 1
            $newNumber = 1;
        }

        // Buat ID baru
        $idsatuan = "AB" . $newNumber;

        // Ambil input
        $nama_satuan = $request->nama_satuan;
        $status = $request->has('status') ? 1 : 0;

        // INSERT
        DB::insert("
            INSERT INTO satuan (idsatuan, nama_satuan, status)
            VALUES (?, ?, ?)
        ", [$idsatuan, $nama_satuan, $status]);

        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil ditambahkan!');
    }


    // ========================
    // FORM EDIT SATUAN
    // ========================
    public function edit($id)
    {
        $satuan = DB::select('SELECT * FROM v_satuan_all WHERE idsatuan = ?', [$id]);

        if (count($satuan) == 0) abort(404);

        $satuan = $satuan[0];

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

         DB::update('
            UPDATE satuan
            SET nama_satuan = ?, status = ?
            WHERE idsatuan = ?
        ', [$nama_satuan, $status, $id]);

        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil diperbarui!');
    }

    // ========================
    // HAPUS DATA SATUAN
    // ========================
        public function destroy($id)
    {
        try {
            DB::delete('DELETE FROM satuan WHERE idsatuan = ?', [$id]);
            return redirect()->route('satuan.index')->with('success, Satuan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('satuan.index')->with('error', 'Gagal menghapus satuan. Data mungkin sedang digunakan.');
        }
    }
}
