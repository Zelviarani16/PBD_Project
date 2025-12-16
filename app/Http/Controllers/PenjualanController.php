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
        $margins = DB::select('SELECT idmargin_penjualan, persen FROM margin_penjualan WHERE status = 1 ORDER BY idmargin_penjualan DESC');

        $currentUser = [
            'iduser' => session('iduser'),
            'username' => session('username')
        ];

        $barangs_for_dropdown = DB::table('v_barang_stok_terakhir')->orderBy('nama')->get();

        return view('penjualan.create', compact('margins', 'barangs_for_dropdown', 'currentUser'));
    }

    // STORE - panggil sp_tambah_penjualan lalu redirect ke detail untuk tambah item
    public function store(Request $request)
    {
        $iduser = session('iduser');

        // Tambah header penjualan
        DB::statement("CALL sp_tambah_penjualan(?, @o_idpenjualan)", [$iduser]);
        $newId = DB::select("SELECT @o_idpenjualan as id")[0]->id;

        // Tambah detail penjualan jika ada
        if (!empty($request->barang) && is_array($request->barang)) {
            foreach ($request->barang as $item) {
                DB::statement("CALL sp_tambah_detail_penjualan(?, ?, ?)", [
                    $newId,
                    $item['idbarang'],
                    $item['jumlah']
                ]);
            }
        }

        // Finalisasi header otomatis → hitung subtotal, ppn, margin, total
        DB::statement("CALL sp_finalisasi_penjualan(?)", [$newId]);

        return redirect()->route('penjualan.detail', ['id' => $newId])
                     ->with('success', 'Penjualan berhasil dibuat, silakan tambah barang!');
    }

    // DETAIL - lihat header + detail (pakai view v_penjualan_status)
    public function detail($id)
    {
        $penjualanArr = DB::select('SELECT * FROM v_penjualan_status WHERE idpenjualan = ?', [$id]);
        if (!$penjualanArr || count($penjualanArr) === 0) {
            return redirect()->route('penjualan.index')->with('error', 'Data penjualan tidak ditemukan.');
        }

        $penjualan = $penjualanArr[0];

        $details = DB::select('SELECT * FROM v_penjualan_detail_lengkap WHERE idpenjualan = ? ORDER BY nama_barang ASC', [$id]);
        $barangs = DB::table('v_barang_stok_terakhir')->orderBy('nama')->get();

        $isEditable = !$penjualan->is_final;

        return view('penjualan.detail', compact('penjualan', 'details', 'barangs', 'isEditable'));
    }

    // ADD DETAIL - panggil sp_tambah_detail_penjualan
    public function addDetail(Request $request, $id)
    {
        $request->validate([
            'idbarang' => 'required',
            'jumlah' => 'required|integer|min:1'
        ]);

        DB::statement('CALL sp_tambah_detail_penjualan(?, ?, ?)', [
            $id,
            $request->idbarang,
            $request->jumlah
        ]);

        // Update header otomatis
        DB::statement("CALL sp_finalisasi_penjualan(?)", [$id]);

        return back()->with('success', 'Detail penjualan berhasil ditambahkan.');
    }

    // UPDATE DETAIL - panggil sp_update_detail_penjualan
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

        // Update header otomatis
        $idpenjualan = DB::select('SELECT idpenjualan FROM detail_penjualan WHERE iddetail_penjualan = ?', [$request->iddetail_penjualan])[0]->idpenjualan;
        DB::statement("CALL sp_finalisasi_penjualan(?)", [$idpenjualan]);

        return back()->with('success', 'Detail penjualan berhasil diperbarui.');
    }

    // CANCEL TRANSAKSI - panggil sp_cancel_penjualan_delete
    public function cancel(Request $request)
    {
        $request->validate(['idpenjualan' => 'required']);

        DB::statement('CALL sp_cancel_penjualan_delete(?)', [$request->idpenjualan]);

        return redirect()->route('penjualan.index')->with('success', 'Transaksi dibatalkan dan stok dikembalikan.');
    }

    // FINALIZE - set penjualan jadi final
    public function finalize($id)
    {
        try {
            DB::table('penjualan_status')->updateOrInsert(
                ['idpenjualan' => $id],
                ['is_final' => 1]
            );

            return redirect()->route('penjualan.detail', $id)
                ->with('success', 'Penjualan berhasil disimpan dan difinalisasi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal finalisasi: ' . $e->getMessage());
        }
    }
}
