<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KartuStok extends Model
{
    use HasFactory;

    protected $table = 'v_kartu_stok_complete'; // pakai view
    protected $primaryKey = 'idkartu_stok';
    public $timestamps = false; // karena view

    protected $fillable = [
        'nama_barang',
        'jenis_transaksi',
        'masuk',
        'keluar',
        'stock',
        'created_at',
        'idtransaksi'
    ];
}
