<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Data dashboard
        $totalBarang     = DB::table('barang')->count();
        $totalVendor     = DB::table('vendor')->count();
        $totalPengadaan  = DB::table('pengadaan')->count();

        return view('dashboard', compact('totalBarang', 'totalVendor', 'totalPengadaan'));
    }
}
