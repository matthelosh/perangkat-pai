<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriAjar extends Model
{
    use HasFactory;
    protected $fillable = [
        'tingkat', 
        'semester',
        'bab',
        'label',
        'elemen'
    ];

    function kontens() {
        return $this->hasMany(SubMateri::class, 'materi_id', 'id');
    }
}
