<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GabungData extends Model
{
    protected $fillable = [
        'rm', 
        'nama', 
        'sep',	
        'kelas', 	
        'naik', 	
        'ruang',	
        'masuk',	
        'jam_masuk',	
        'keluar',	
        'jam_keluar',	
        'ket',	
        'dpjp', 
        'kamar'
    ];
    protected $casts = [
        'tgl_masuk' => 'datetime',
        'tgl_keluar' => 'datetime',
    ];
}
