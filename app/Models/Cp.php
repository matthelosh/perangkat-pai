<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cp extends Model
{
    use HasFactory;

    protected $fillable = [
        'fase',
        'teks'
    ];

    function elemens() {
        return $this->hasMany(ELemen::class, 'cp_id', 'id');
    }
}
