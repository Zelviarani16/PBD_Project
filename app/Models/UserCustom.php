<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model ini digunakan untuk tabel 'user' pada database kamu.
 * Berbeda dari default Laravel, jadi dibuat file terpisah agar tidak bentrok.
 */
class UserCustom extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'iduser';

    public $incrementing = false;   // <<< WAJIB karena iduser bukan angka
    protected $keyType = 'string';  // <<< WAJIB karena idnya USR1, USR2

    public $timestamps = false;

    protected $fillable = ['iduser', 'username', 'password', 'idrole'];


    // relasi ke tabel role
    public function role()
    {
        return $this->belongsTo(Role::class, 'idrole', 'idrole');
    }
}
