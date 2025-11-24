<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    // INDEX - daftar penjualan (pakai view v_penjualan_all)
    public function index()
    {
        $penjualans = DB::select('SELECT * FROM v_penjualan_all ORDER BY tanggal_penjualan DESC');
        return view('penjualan.index', compact('penjualans'));
    }

    // CREATE - form buat header penjualan
    public function create()
    {
    
        // dropdown margin aktif (biasanya hanya 1, tapi tampilkan daftar)
        $margins = DB::select('SELECT idmargin_penjualan, persen FROM margin_penjualan WHERE status = 1 ORDER BY idmargin_penjualan DESC');

        // AMBIL USER YANG LOGIN
        $currentUser = [
            'iduser' => session('iduser'),
            'username' => session('username')
        ];

        // barang yang punya stok / pernah diterima (pakai view stok)
        $barangs_for_dropdown = DB::table('v_barang_stok_terakhir')->orderBy('nama')->get();
        $barangs = DB::table('v_barang_stok_terakhir')->orderBy('nama')->get();

        return view('penjualan.create', compact('margins', 'barangs_for_dropdown', 'currentUser'));
    }

    // STORE - panggil sp_tambah_penjualan lalu redirect ke detail untuk tambah item
    public function store(Request $request)
    {
        // Tambah header penjualan
   $iduser = session('iduser');

    DB::statement("CALL sp_tambah_penjualan(?, @o_idpenjualan)", [
        $iduser,
    ]);

    $newId = DB::select("SELECT @o_idpenjualan as id")[0]->id;

    // Tambah detail penjualan (cek dulu kalau ada)
    if (!empty($request->barang) && is_array($request->barang)) {
        foreach ($request->barang as $item) {
            DB::statement("CALL sp_add_detail_penjualan(?, ?, ?)", [
                $newId,
                $item['idbarang'],
                $item['jumlah']
            ]);
        }
    }
    return redirect()->route('penjualan.detail', ['id' => $newId])
                 ->with('success', 'Penjualan berhasil dibuat, silakan tambah barang!');

}

    // DETAIL - lihat header + detail (pakai view v_penjualan_detail)
    public function detail($id)
    {
        $penjualan = DB::select('SELECT * FROM v_penjualan_all WHERE idpenjualan = ?', [$id]);

        if (!$penjualan || count($penjualan) === 0) {
            return redirect()->route('penjualan.index')->with('error', 'Data penjualan tidak ditemukan.');
        }

        // ambil detail
        $details = DB::select('SELECT * FROM v_penjualan_detail_lengkap WHERE idpenjualan = ? ORDER BY nama_barang ASC', [$id]);

        // barang dropdown di halaman detail: pakai v_stok_barang_penjualan
        $barangs_for_dropdown = DB::table('v_barang_stok_terakhir')->orderBy('nama')->get();
        $barangs = DB::table('v_barang_stok_terakhir')->orderBy('nama')->get();

        return view('penjualan.detail', [
            'penjualan' => $penjualan[0],
            'details' => $details,
            'barangs' => $barangs
        ]);
    }

    // ADD DETAIL - panggil sp_tambah_detail_penjualan
    public function addDetail(Request $request, $id)
    {
        $request->validate([
            'idbarang' => 'required',
            'jumlah' => 'required|integer|min:1'
        ]);

        // Karena sp_tambah_detail_penjualan mengambil harga otomatis dari barang
        DB::statement('CALL sp_tambah_detail_penjualan(?, ?, ?)', [
                $id,
                $request->idbarang,
                $request->jumlah
        ]);

        return back()->with('success', 'Detail penjualan berhasil ditambahkan.');
    }

    // UPDATE DETAIL - panggil sp_update_detail_penjualan(iddetail, qty_baru)
    public function updateDetail(Request $request)
    {
        $request->validate([
            'iddetail_penjualan' => 'required',
            'jumlah' => 'required|integer|min:1'
        ]);

        DB::statement('CALL sp_update_detail_penjualan(?, ?)', [
            $request->iddetail_penjualan,
            (int)$request->jumlah
        ]);

        return back()->with('success', 'Detail penjualan berhasil diperbarui.');
    }


    // CANCEL TRANSAKSI - panggil sp_cancel_penjualan_delete(idpenjualan)
    public function cancel(Request $request)
    {
        $request->validate(['idpenjualan' => 'required']);

        DB::statement('CALL sp_cancel_penjualan_delete(?)', [
            $request->idpenjualan
        ]);

        return redirect()->route('penjualan.index')->with('success', 'Transaksi dibatalkan dan stok dikembalikan.');
    }
}
