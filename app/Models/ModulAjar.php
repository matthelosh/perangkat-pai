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
        'kompetensi_awal',
        'p5',
        'sarpras',
        'target_siswa',
        'model',
        'tps',
        'pemahaman',
        'pertanyaan',
        'persiapan',
        'pendahuluan',
        'inti',
        'penutup',
        'remidial',
        'pengayaan',
        'refleksi_guru',
        'refleksi_siswa',
        'asesmen'
    ];

    public function atp()
    {
        return $this->belongsTo(Atp::class, 'atp_id', 'id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id','nip');
    }

    public function rombel()
    {
        return $this->belongsTo(Rombel::class, 'rombel_id', 'kode');
    }
}
