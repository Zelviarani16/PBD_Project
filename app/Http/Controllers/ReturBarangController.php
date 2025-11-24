<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ReturBarang;

class ReturBarangController extends Controller
{
    // Menampilkan daftar retur
    public function index()
    {
        $returs = ReturBarang::getAll();
        return view('retur_barang.index', compact('returs'));
    }

    // Form tambah retur baru
    public function create()
    {
        $penerimaans = ReturBarang::getPenerimaan();
        return view('retur_barang.create', compact('penerimaans'));
    }

    // Simpan retur baru (Header)
    public function store(Request $request)
    {
        $request->validate([
            'idpenerimaan' => 'required|integer',
            'iduser' => 'required|string|max:10',
        ]);

        try {
            $idretur = 0;

            // Panggil SP untuk tambah retur header
            DB::statement('CALL sp_tambah_retur_barang(?, ?, @idretur)', [
                $request->idpenerimaan,
                $request->iduser
            ]);

            // Ambil output ID
            $idretur = DB::select('SELECT @idretur AS idretur')[0]->idretur;

            return redirect()->route('retur.detail', $idretur)
                ->with('success', 'Retur berhasil dibuat!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Detail retur + daftar barang
    public function detail($id)
    {
        $retur = ReturBarang::getById($id);
        if (!$retur) {
            return redirect()->route('retur_barang.index')
                ->with('error', 'Retur tidak ditemukan!');
        }

        $details = ReturBarang::getDetails($id);

        return view('retur_barang.detail', compact('retur', 'details'));
    }

    // Tambah detail retur (Barang)
// Controller: tambahDetail
public function tambahDetail(Request $request, $idretur)
{
    $request->validate([
        'iddetail_penerimaan' => 'required|integer',
        'jumlah' => 'required|integer|min:1',
        'alasan' => 'nullable|string|max:200'
    ]);

    $iddetail = $request->iddetail_penerimaan;
    $qty = (int) $request->jumlah;
    $alasan = $request->alasan ?? '';

    // Ambil stok sisa dari view
    $stock = DB::table('v_retur_create')
        ->where('iddetail_penerimaan', $iddetail)
        ->value('stock');

    if ($stock === null) {
        return back()->with('error', 'Data barang tidak valid!');
    }

    if ($qty > $stock) {
        return back()->with('error', 'Jumlah retur melebihi stok yang tersedia!');
    }

    try {
        DB::statement('CALL sp_tambah_detail_retur(?, ?, ?, ?)', [
            $idretur,
            $iddetail,
            $qty,
            $alasan
        ]);

        return back()->with('success', 'Detail retur berhasil ditambahkan!');
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal menambahkan detail retur: ' . $e->getMessage());
    }
}
}