<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penerimaan extends Model
{
    use HasFactory;

    protected $table = 'penerimaan';
    protected $primaryKey = 'idpenerimaan';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'idpengadaan',
        'iduser',
        'status_penerimaan'
    ];

    public function pengadaan()
    {
        return $this->belongsTo(Pengadaan::class, 'idpengadaan', 'idpengadaan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }

    public function detail()
    {
        return $this->hasMany(DetailPenerimaan::class, 'idpenerimaan', 'idpenerimaan');
    }
}
