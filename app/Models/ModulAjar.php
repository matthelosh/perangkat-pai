<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulAjar extends Model
{
    use HasFactory;

    protected $fillable = [
        'atp_id',
        'guru_id',
        'rombel_id',
        'p5',
        'tps',
        'media',
        'pemahaman',
        'pertanyaan',
        'pendahuluan',
        'inti',
        'penutup',
        'asesmen',
        'referensi'
    ];

    public function atp()
    {
        return $this->belongsTo(Atp::class, 'atp_id', 'id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'nip');
    }

    public function rombel()
    {
        return $this->belongsTo(Rombel::class, 'rombel_id', 'kode');
    }
}
