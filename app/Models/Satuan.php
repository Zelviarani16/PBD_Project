<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $table = 'satuan';
    protected $primaryKey = 'idsatuan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'idsatuan',
        'nama_satuan',
        'status'
    ];

    protected $casts = [
        'status' => 'integer',
    ];
}
