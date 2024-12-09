<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analisis extends Model
{
    use HasFactory;

    protected $fillable = [
        'asesmen_id',
        'siswa_id',
        'rombel_id',
        'pgs',
        'isians',
        'uraians'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'nisn');
    }

    public function asesmen()
    {
        return $this->belongsTo(Asesmen::class);
    }
}
