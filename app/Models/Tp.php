<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tp extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode', 
        'fase',
        'elemen_id',
        'guru_id',
        'kompetensi',
        'materi',
        'teks'
    ];

    function elemen() {
        return $this->belongsTo(Elemen::class, 'elemen_id', 'kode');
    }

    function guru() {
        return $this->belongsTo(Guru::class, 'guru_id', 'nip');
    }
}
