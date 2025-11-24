<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Penjualan extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'idpenjualan';
    public $incrementing = false;
    public $timestamps = false;

    public static function getAll()
    {
        return DB::select('SELECT * FROM v_penjualan_all ORDER BY tanggal_penjualan DESC');
    }

    public static function getById($id)
    {
        $res = DB::select('SELECT * FROM v_penjualan_all WHERE idpenjualan = ?', [$id]);
        return $res ? $res[0] : null;
    }
}
