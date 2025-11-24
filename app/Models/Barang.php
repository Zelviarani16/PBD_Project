<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{

    protected $table = 'barang';
    protected $primaryKey = 'idbarang';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'idbarang',
        'jenis',
        'nama',
        'idsatuan',
        'status',
        'harga'
    ];

    // Cast tipe data
    protected $casts = [
        'status' => 'integer',
        'harga' => 'integer',
    ];

    /**
     * Relasi ke tabel satuan
     * Setiap barang memiliki satu satuan
     */
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'idsatuan', 'idsatuan');
    }
}