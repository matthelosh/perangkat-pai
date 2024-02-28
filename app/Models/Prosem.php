<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prosem extends Model
{
    use HasFactory;

    protected $fillable = [
        'guru_id',
        'atp_id',
        'rombel_id',
        'tingkat',
        'tanggal',
        'minggu_ke',
        'hari'
    ];

    function guru() {
        return $this->belongsTo(Guru::class, 'guru_id', 'nip');
    }

    function atp() {
        return $this->belongsTo(Atp::class, 'atp_id', 'id');
    }

    function rombel() {
        return $this->belongsTo(Rombel::class, 'rombel_id', 'kode');
    }
}
