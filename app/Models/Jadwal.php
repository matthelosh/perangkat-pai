<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'tapel_id',
        'guru_id',
        'rombel_id',
        'hari',
        'jml_jam',
        'jamke',
        'is_active'
    ];

    function guru() {
        return $this->belongsTo(Guru::class, 'guru_id','nip');
    }

    function rombel() {
        return $this->belongsTo(Rombel::class, 'rombel_id','kode');
    }
}
