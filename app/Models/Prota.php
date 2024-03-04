<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prota extends Model
{
    use HasFactory;

    protected $fillable = [
        'guru_id',
        'rombel_id',
        'atp_id',
        'tanggal',
        'semester',
        'minggu_ke',
        'aw'
    ];

    public function guru() {
        return $this->belongsTo(Guru::class, 'guru_id','nip');
    }

    public function rombel() {
        return $this->belongsTo(Rombel::class, 'rombel_id','kode');
    }

    public function atp() {
        return $this->belongsTo(Atp::class, 'atp_id', 'kode');
    }
}
