<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    use HasFactory;

    protected $fillable = [
        'elemen_id',
        'tingkat',
        'tp_id',
        'guru_id',
        'agama',
        'tipe',
        'level',
        'pertanyaan',
        'a',
        'b',
        'c',
        'd',
        'kunci'
    ];


    public function asesmens()
    {
        return $this->belongsToMany(Asesmen::class, 'asesmen_soal', 'asesmen_id', 'soal_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'nip');
    }

    public function tp()
    {
        return $this->belongsTo(Tp::class, 'tp_id', 'kode');
    }
}
