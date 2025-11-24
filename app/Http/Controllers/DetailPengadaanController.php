<?php

namespace App\Http\Controllers;

use App\Models\Pengadaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailPengadaanController extends Controller
{
  
    public function index($idpengadaan)
    {
        // Ambil data pengadaan utama
        $pengadaan = DB::table('pengadaan')->where('idpengadaan', $idpengadaan)->first();

        // Ambil detail dari view
        $details = DB::select("SELECT * FROM v_pengadaan_detail WHERE idpengadaan = ?", [$idpengadaan]);

        // Kirim dua variabel ke view
        return view('pengadaan.detail_pengadaan', compact('pengadaan', 'details'));
    }

}
