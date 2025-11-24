<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class DetailPengadaan extends Model
{
    protected $table = 'detail_pengadaan';
    protected $primaryKey = 'iddetail_pengadaan';
    public $timestamps = false;

    // Ambil semua detail berdasarkan pengadaan
    public static function getDetail($idpengadaan)
    {
        return DB::select('SELECT * FROM v_detail_pengadaan WHERE idpengadaan = ?', [$idpengadaan]);
    }

    // Tambah detail pengadaan (langsung hitung subtotal)
    public static function insertDetail($idpengadaan, $idbarang, $harga_satuan, $jumlah)
    {
        DB::insert('
            INSERT INTO detail_pengadaan (idpengadaan, idbarang, harga_satuan, jumlah, sub_total)
            VALUES (?, ?, ?, ?, ?)
        ', [$idpengadaan, $idbarang, $harga_satuan, $jumlah, $harga_satuan * $jumlah]);

        // Panggil SP untuk update total otomatis
        DB::statement('CALL sp_update_total_pengadaan(?)', [$idpengadaan]);
    }
}
