<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KartuStokController extends Controller
{
    public function index()
    {
        $kartuStok = DB::select('SELECT * FROM v_kartu_stok ORDER BY created_at DESC');

        // Tambahkan mapping jenis transaksi
        $jenis = [
            'M' => ['label'=>'Masuk','class'=>'bg-success'],
            'K' => ['label'=>'Keluar','class'=>'bg-danger'],
            'R' => ['label'=>'Return','class'=>'bg-info'],
            'P' => ['label'=>'Pembatalan','class'=>'bg-warning'],
        ];

        return view('kartu_stok.index', compact('kartuStok', 'jenis'));
    }

}


