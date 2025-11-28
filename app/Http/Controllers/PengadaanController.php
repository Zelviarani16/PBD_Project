<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengadaanController extends Controller
{
    public function index()
    {
        $pengadaans = DB::select('SELECT * FROM v_pengadaan_all ORDER BY tanggal_pengadaan DESC');
        return view('pengadaan.index', compact('pengadaans'));
    }

    public function create()
    {
        $vendors = DB::select('SELECT * FROM vendor');

        // Ambil user dari session login
        $currentUser = [
            'iduser' => session('iduser'),
            'username' => session('username')
        ];

        return view('pengadaan.create', compact('vendors', 'currentUser'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vendor_idvendor' => 'required',
        ]);

        $user_iduser = session('iduser'); // user yang login

        // Panggil stored procedure
        DB::statement('CALL sp_tambah_pengadaan(?, ?)', [
            $user_iduser,
            $request->vendor_idvendor
        ]);

        // Ambil ID pengadaan terbaru berdasarkan kombinasi unik
        $idpengadaan = DB::table('pengadaan')
            ->where('user_iduser', $user_iduser)
            ->where('vendor_idvendor', $request->vendor_idvendor)
            ->latest('timestamp')
            ->value('idpengadaan');

        return redirect()->route('pengadaan.detail', $idpengadaan)
            ->with('success', 'Pengadaan baru berhasil dibuat!');
    }

    public function detail($id)
    {
        $pengadaan = DB::table('v_pengadaan_all')
            ->where('idpengadaan', $id)
            ->first();
            
        if (!$pengadaan) {
            return redirect()->route('pengadaan.index')->with('error', 'Data pengadaan tidak ditemukan.');
        }

        $details = DB::select('SELECT * FROM v_pengadaan_detail WHERE idpengadaan = ?', [$id]);

        foreach ($details as $d) {
            $d->sub_total = $d->harga_satuan * $d->jumlah;
        }

        $barangs = DB::select('SELECT * FROM barang ORDER BY nama ASC');

        // CEK STATUS: 'P' = bisa edit, selain itu = read only
        $isEditable = ($pengadaan->status_text === 'Pending');

        return view('pengadaan.detail_pengadaan', compact('pengadaan', 'details', 'barangs', 'isEditable'));
    }

    public function tambahDetail(Request $request, $id)
    {
        $request->validate([
            'idbarang' => 'required',
            'jumlah' => 'required|integer|min:1'
        ]);
        
        // CEK APAKAH MASIH BISA EDIT (status = 'P')
        $status = DB::table('pengadaan')
            ->where('idpengadaan', $id)
            ->value('status');
        
        if ($status !== 'P') {
            return back()->with('error', 'Pengadaan sudah difinalisasi, tidak bisa menambah barang');
        }
        
        try {
            // INSERT DETAIL (DATA LANGSUNG MASUK DB, TAPI BELUM FINALISASI)
            DB::statement('CALL sp_insert_detail_pengadaan_item(?, ?, ?)', [
                $id,
                $request->idbarang,
                $request->jumlah
            ]);
            
            return back()->with('success', 'Barang berhasil ditambahkan');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan barang: ' . $e->getMessage());
        }
    }
    
    public function finalize($id)
    {
        try {
            DB::statement('CALL sp_finalisasi_pengadaan(?)', [$id]);
            
            return redirect()->route('pengadaan.index')
                ->with('success', 'Pengadaan berhasil disimpan dan difinalisasi');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal finalisasi: ' . $e->getMessage());
        }
    }
    
    public function batal($id)
    {
        DB::statement("UPDATE pengadaan SET status = 'B' WHERE idpengadaan = ?", [$id]);
        return redirect()->route('pengadaan.index')->with('warning', 'Pengadaan telah dibatalkan.');
    }

    public function destroy($id)
    {
        // Hapus pengadaan dan detailnya
        DB::transaction(function () use ($id) {
            DB::table('detail_pengadaan')->where('idpengadaan', $id)->delete();
            DB::table('pengadaan')->where('idpengadaan', $id)->delete();
        });

        return redirect()->route('pengadaan.index')->with('success', 'Pengadaan berhasil dihapus!');
    }
}