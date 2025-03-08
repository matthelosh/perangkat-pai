<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuitansi extends Model
{
    use HasFactory;

    protected $fillable = [
        'no',
        'tanggal',
        'dari',
        'terbilang',
        'kegiatan_id',
        'jumlah'
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kaldik::class, 'kegiatan_id', 'id');
    }
}
