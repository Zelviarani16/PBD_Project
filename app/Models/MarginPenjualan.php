<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarginPenjualan extends Model
{
    protected $table = 'margin_penjualan';
    protected $primaryKey = 'idmargin_penjualan';
    public $timestamps = false;

    protected $fillable = [
        'idmargin_penjualan',
        'persen',
        'status',
        'iduser',
        'created_at',
        'updated_at'
    ];
}
