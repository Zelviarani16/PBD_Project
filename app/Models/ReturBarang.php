<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ReturBarang extends Model
{
    protected $table = 'retur_barang';
    protected $primaryKey = 'idretur';
    public $timestamps = false;

    public static function getAll()
    {
        return DB::select('SELECT * FROM v_retur_barang_view ORDER BY tanggal_retur DESC');
    }

    public static function getById($id)
    {
        $result = DB::select('SELECT * FROM v_retur_barang_view WHERE idretur = ?', [$id]);
        return $result ? $result[0] : null;
    }

    public static function getDetails($id)
    {
        return DB::select('SELECT * FROM v_retur_detail_view WHERE idretur = ?', [$id]);
    }

    public static function getPenerimaan()
    {
        return DB::select('SELECT * FROM v_penerimaan_with_vendor ORDER BY tanggal_penerimaan DESC');
    }

    public static function getDetailCreate($idpenerimaan)
    {
        return DB::select('SELECT * FROM v_retur_create WHERE idpenerimaan = ?', [$idpenerimaan]);
    }

}
