<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atp extends Model
{
    use HasFactory;

    protected $fillable = [
        'guru_id',
        'kode',
        'elemen_id',
        'tingkat',
        'fase',
        'semester',
        'aw',
        'materi',
        'tps',
        'konten',
        'asesmen',
        'p5'
    ];

    function elemen()
    {
        return $this->belongsTo(Elemen::class, 'elemen_id', 'kode');
    }

    function prosems()
    {
        return $this->hasMany(Prosem::class, 'atp_id', 'id');
    }
    function prota()
    {
        return $this->hasOne(Prota::class, 'atp_id', 'kode');
    }


    function mas()
    {
        return $this->hasOne(ModulAjar::class, 'atp_id', 'id');
    }
}
