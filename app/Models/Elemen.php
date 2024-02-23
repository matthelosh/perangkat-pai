<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Elemen extends Model
{
    use HasFactory;

    protected $fillable = [
        'fase',
        'cp_id',
        'kode',
        'label',
        'deskripsi_cp'
    ];

    function cp() {
        return $this->belongsTo(Cp::class);
    }

    function tps() {
        return $this->hasMany(Tp::class, 'elemen_id', 'kode');
    }
    function atps() {
        return $this->hasMany(Atp::class, 'elemen_id', 'kode');
    }
}
