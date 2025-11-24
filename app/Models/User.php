<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    // Nama tabel di database
    protected $table = 'user';
    protected $primaryKey = 'iduser';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    // Kolom yang bisa diisi (fillable)
    protected $fillable = [
        'iduser',
        'username',
        'password',
        'idrole',
    ];

    // Relasi ke tabel Role
    public function role()
    {
        return $this->belongsTo(Role::class, 'idrole', 'idrole');
    }
}
