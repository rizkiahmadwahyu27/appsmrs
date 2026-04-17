<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataPasien extends Model
{
    protected $fillable = [
        'rm',
        'nama',
        'no_sep',
        'kelas',
        'naik_kelas',
        'status',
        'tgl_masuk',
        'tgl_keluar',
        'dpjp',
        'ruangan',
        'keterangan'
    ];

    protected $casts = [
        'tgl_masuk' => 'datetime',
        'tgl_keluar' => 'datetime',
    ];
}
