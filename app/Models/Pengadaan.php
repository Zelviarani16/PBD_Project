<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Vendor;

class Pengadaan extends Model
{
    protected $table = 'pengadaan';
    protected $primaryKey = 'idpengadaan';
    public $timestamps = false;

    // Ambil semua pengadaan dari view
    public static function getAll()
    {
        return DB::select('SELECT * FROM v_pengadaan_all ORDER BY tanggal_pengadaan DESC');
    }

    // Ambil pengadaan berdasarkan ID
    public static function getById($id)
    {
        $result = DB::select('SELECT * FROM v_pengadaan_all WHERE idpengadaan = ?', [$id]);
        return $result ? $result[0] : null;
    }

    // Tambah pengadaan baru (langsung ke tabel utama)
    public static function insertPengadaan($user_iduser, $vendor_idvendor)
    {
        DB::insert('
            INSERT INTO pengadaan (timestamp, user_iduser, vendor_idvendor, status, subtotal_nilai, ppn, total_nilai)
            VALUES (NOW(), ?, ?, "P", 0, 10, 0)
        ', [$user_iduser, $vendor_idvendor]);

        // Ambil ID terakhir (auto increment)
        $id = DB::getPdo()->lastInsertId();
        return $id;
    }

    // Update total & status
    public static function updateTotal($idpengadaan)
    {
        DB::statement('CALL sp_update_total_pengadaan(?)', [$idpengadaan]);
    }

    public function create()
    {
        $vendors = Vendor::all();
        $users = User::all();
        return view('pengadaan.create', compact('vendors', 'users'));
    }

}
