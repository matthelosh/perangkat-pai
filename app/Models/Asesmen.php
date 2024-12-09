<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asesmen extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'label',
        'tingkat',
        'tipe',
        'tapel',
        'semester',
        'jml_soal',
        'kunci'
    ];

    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }

    public function analises()
    {
        return $this->hasMany(Analisis::class);
    }
}
