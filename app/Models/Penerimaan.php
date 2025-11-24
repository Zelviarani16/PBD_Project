<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penerimaan extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'penerimaan';

    // Primary key
    protected $primaryKey = 'idpenerimaan';

    // Jika primary key bukan auto increment, ubah ini
    public $incrementing = false;

    // Tipe primary key
    protected $keyType = 'int';

    // Timestamps
    public $timestamps = true;

    // Mass assignable fields
    protected $fillable = [
        'idpengadaan',
        'iduser',
        'status_penerimaan'
    ];

    // Relasi ke Pengadaan
    public function pengadaan()
    {
        return $this->belongsTo(Pengadaan::class, 'idpengadaan', 'idpengadaan');
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }

    // Relasi ke Detail Penerimaan
    public function detail()
    {
        return $this->hasMany(DetailPenerimaan::class, 'idpenerimaan', 'idpenerimaan');
    }
}
