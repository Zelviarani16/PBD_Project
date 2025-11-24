<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenerimaan extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'detail_penerimaan';

    // Primary key
    protected $primaryKey = 'iddetail_penerimaan';

    public $incrementing = true; // ubah jika pk bukan auto increment
    protected $keyType = 'int';

    // Timestamps
    public $timestamps = true;

    // Mass assignable fields
    protected $fillable = [
        'idpenerimaan',
        'idbarang',
        'jumlah_terima',
    ];

    // Relasi ke Penerimaan
    public function penerimaan()
    {
        return $this->belongsTo(Penerimaan::class, 'idpenerimaan', 'idpenerimaan');
    }

    // Relasi ke Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'idbarang', 'idbarang');
    }
}
