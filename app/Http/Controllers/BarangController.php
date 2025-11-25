<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{

    // TAMPILKAN SEMUA DATA BARANG (PAKAI VIEW)
    public function index()
    {
        // Ambil semua barang aktif & tidak aktif dari view v_barang_all
        $barang = DB::select('SELECT * FROM v_barang_all ORDER BY nama_barang ASC');

        // compact mengubah variabe $barang menjadi array ['barang' => $barang]
        // Setiap variabel pasti harus dimasukkan compact supaya bisa diakses
        return view('barang.index', compact('barang'));
    }

    // FORM TAMBAH BARANG --> TAMPILAN FORMNYA
    public function create()
    {
        // Ambil data satuan aktif dari view v_satuan_aktif
        $satuan = DB::select('SELECT * FROM v_satuan_aktif ORDER BY nama_satuan ASC');
        
        return view('barang.create', compact('satuan'));
    }

    // SIMPAN BARANG BARU
    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'idbarang' => 'required|max:10',
            'jenis' => 'required|in:A,B,C',
            'nama' => 'required|max:45',
            'idsatuan' => 'required',
            'harga' => 'required|integer|min:0',
        ]);

        // Ambil data dari form input
        $idbarang = $request->idbarang;
        $jenis = $request->jenis;
        $nama = $request->nama;
        $idsatuan = $request->idsatuan;
        $harga = $request->harga;
        $status = $request->has('status') ? 1 : 0;

        DB::insert('
            INSERT INTO barang (idbarang, jenis, nama, idsatuan, harga, status)
            VALUES (?, ?, ?, ?, ?, ?)
            ',[$idbarang, $jenis, $nama, $idsatuan, $harga, $status]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    // FORM EDIT BARANG
    public function edit($id)
    {
        // Ambil data barang berdasarkan ID dari view v_barang_all
        $barang = DB::select('SELECT * FROM v_barang_all WHERE idbarang = ?', [$id]);

        // Ambil data satuan aktif untuk dropdown
        $satuan = DB::select('SELECT * FROM v_satuan_aktif ORDER BY nama_satuan ASC');

        // Karena DB::select() mengembalikan array of object, ambil elemen pertama
        if(count($barang) == 0) abort(404); 
            $barang = $barang[0];

        return view('barang.edit', compact('barang', 'satuan'));
    }

    // UPDATE DATA BARANG (PAKAI STORED PROCEDURE)
    public function update(Request $request, $id)
    {
        // Validasi input dari form edit
        $request->validate([
            'jenis' => 'required|in:A,B,C,1',
            'nama' => 'required|max:45',
            'idsatuan' => 'required',
            'harga' => 'required|integer|min:0',
        ]);

        // Ambil data dari form input
        $idbarang = $id; // ambil ID dari parameter route
        $jenis = $request->jenis;
        $nama = $request->nama;
        $idsatuan = $request->idsatuan;
        $harga = $request->harga;
        $status = $request->has('status') ? 1 : 0;

        DB::update('
            UPDATE barang
            SET jenis = ?, nama = ?, idsatuan = ?, harga = ?, status = ?
            WHERE idbarang = ?
            ', [$jenis, $nama, $idsatuan, $harga, $status, $id]);

        // Redirect kembali ke index dengan notifikasi sukses
        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        try {
            DB::delete('DELETE FROM barang WHERE idbarang = ?', [$id]);
            return redirect()->route('barang.index')->with('success, Barang berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('barang.index')->with('error', 'Gagal menghapus barang. Data mungkin sedang digunakan.');
        }
    }
}