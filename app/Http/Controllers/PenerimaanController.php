<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenerimaanController extends Controller
{
    // Menampilkan semua penerimaan
    public function index()
    {
        // Pastikan kolom iduser dan status_text ada di view
        $penerimaan = DB::table('v_penerimaan_all')->orderBy('tanggal_penerimaan', 'desc')->get();

        return view('penerimaan.index', compact('penerimaan'));
    }

    // Form untuk buat penerimaan baru
    public function create()
    {
        // Dropdown ambil yg status in process atau sebagian
        $pengadaans = DB::table('v_pengadaan_all')
        ->whereIn('status_text', ['Pending', 'In Process', 'Sebagian'])
        ->orderByDesc('tanggal_pengadaan')
        ->get();

        // AMBIL USER YANG LOGIN
        $currentUser = [
            'iduser' => session('iduser'),
            'username' => session('username')
        ];

        return view('penerimaan.create', compact('pengadaans', 'currentUser'));
    }

    // Simpan penerimaan baru
    public function store(Request $request)
    {
        $request->validate([
            'idpengadaan' => 'required|integer',
        ]);

        // USER YANG LOGIN
        $iduser = session('iduser');

         DB::statement('CALL sp_tambah_penerimaan(?, ?, @p_idpenerimaan)', [
            $request->idpengadaan,
            $iduser
        ]);

        $idpenerimaan = DB::select('SELECT @p_idpenerimaan AS idpenerimaan')[0]->idpenerimaan;

        return redirect()->route('penerimaan.detail', $idpenerimaan)
            ->with('success', 'Penerimaan baru berhasil dibuat!');
    }

    // Halaman detail penerimaan
    public function detail($id)
    {
        $penerimaan = DB::table('v_penerimaan_all')
            ->where('idpenerimaan', $id)
            ->first();

        if (!$penerimaan) {
            return redirect()->route('penerimaan.index')->with('error', 'Data penerimaan tidak ditemukan.');
        }

        $barangs = DB::table('v_detail_pengadaan_untuk_penerimaan')
            ->where('idpengadaan', $penerimaan->idpengadaan)
            ->get();

        return view('penerimaan.detail', compact('penerimaan', 'barangs'));
    }



    // Tambah detail penerimaan
    public function tambahDetail(Request $request, $id)
    {
        $request->validate([
            'idbarang' => 'required',
            'jumlah' => 'required|integer|min:1',
        ]);

        $barang = DB::table('v_detail_pengadaan_untuk_penerimaan')
                    ->where('idbarang', $request->idbarang)
                    ->first();

        if (!$barang) {
            return redirect()->route('penerimaan.detail', $id)
                             ->with('error', 'Barang tidak ditemukan.');
        }

        DB::statement('CALL sp_tambah_detail_penerimaan(?, ?, ?, ?)', [
            $id,
            $request->idbarang,
            $request->jumlah,
            $barang->harga_satuan
        ]);

        return redirect()->route('penerimaan.detail', $id)
                         ->with('success', 'Barang berhasil diterima');
    }
}
