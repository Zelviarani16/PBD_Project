<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendor';
    protected $primaryKey = 'idvendor';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'idvendor',
        'nama_vendor',
        'badan_hukum',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];
}
      