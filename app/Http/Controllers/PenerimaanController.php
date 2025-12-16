<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenerimaanController extends Controller
{
    public function index()
    {
        // WAJIB RAW SQL
        $penerimaan = DB::select("SELECT * FROM v_penerimaan_all ORDER BY tanggal_penerimaan DESC");

        return view('penerimaan.index', compact('penerimaan'));
    }

    public function create()
    {
        // Gunakan COLLATE agar tidak error mix collations
        $pengadaans = DB::table('v_pengadaan_all')
            ->whereIn('status_text', ['Pending', 'In Process', 'Sebagian'])
            ->orderBy('tanggal_pengadaan', 'DESC')
            ->whereRaw("status_text COLLATE utf8mb4_unicode_ci IN (?, ?, ?)", ['Pending', 'In Process', 'Sebagian'])
            ->get();

        // Ambil user dari session login
        $currentUser = [
            'iduser' => session('iduser'),
            'username' => session('username')
        ];

        return view('penerimaan.create', compact('pengadaans', 'currentUser'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idpengadaan' => 'required'
        ]);

        $user_iduser = session('iduser'); // user yang login

        // Panggil stored procedure sp_tambah_penerimaan
        DB::statement("CALL sp_tambah_penerimaan(?, ?, @p_idpenerimaan)", [
            $request->idpengadaan,
            $user_iduser
        ]);

        // Ambil output parameter
        $result = DB::select("SELECT @p_idpenerimaan AS idpenerimaan")[0];

        return redirect()
            ->route('penerimaan.detail', $result->idpenerimaan)
            ->with('success', 'Penerimaan berhasil dibuat.');
    }

    public function detail($idpenerimaan)
    {
        // Ambil header penerimaan
        $penerimaan = DB::select("SELECT * FROM v_penerimaan_all WHERE idpenerimaan = ?", [$idpenerimaan]);
        if (!$penerimaan) abort(404);
        $penerimaan = $penerimaan[0];

        $idpengadaan = $penerimaan->idpengadaan;

        // Ambil detail penerimaan (barang yang sudah diterima)
        $detail = DB::select("SELECT * FROM v_penerimaan_detail WHERE idpenerimaan = ?", [$idpenerimaan]);

        // Barang yang masih ada sisa
        $barangs = DB::table('v_detail_pengadaan_untuk_penerimaan')
            ->where('idpengadaan', $idpengadaan)
            ->get();

        // Logic editable berdasarkan status terbaru
        // Setelah klik finalize, form jadi read-only
        $isEditable = in_array($penerimaan->status_text, ['Pending', 'Sebagian']);

        $penerimaan->is_final = $penerimaan->status_text !== 'Pending';

        return view('penerimaan.detail', compact('penerimaan', 'detail', 'barangs', 'isEditable'));
    }

    public function tambahDetail(Request $request, $idpenerimaan)
    {
        $request->validate([
            'idbarang' => 'required',
            'jumlah_diterima' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric'
        ]);

        // Cek status terbaru
        $penerimaan = DB::table('v_penerimaan_all')->where('idpenerimaan', $idpenerimaan)->first();
        if (!$penerimaan || $penerimaan->status_text == 'Selesai') {
            return back()->with('error', 'Penerimaan sudah difinalisasi. Tidak bisa menambah barang.');
        }

        DB::statement("CALL sp_tambah_detail_penerimaan(?, ?, ?, ?)", [
            $idpenerimaan,
            $request->idbarang,
            $request->jumlah_diterima,
            $request->harga_satuan
        ]);

        return redirect()->route('penerimaan.detail', $idpenerimaan)
            ->with('success', 'Detail penerimaan berhasil ditambahkan.');
    }

    public function finalize($idpenerimaan)
    {
        try {
            // Jalankan SP
            DB::statement('CALL sp_finalisasi_penerimaan(?)', [$idpenerimaan]);

            // Buat read-only setelah klik simpan
            session()->flash('readonly', true);

            // Ambil penerimaan terbaru (bs digunaka =n utk view)
            $penerimaan = DB::table('v_penerimaan_all')->where('idpenerimaan', $idpenerimaan)->first();

            return redirect()->route('penerimaan.detail', $idpenerimaan);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal finalisasi: ' . $e->getMessage());
        }
    }
}
