<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubMateri extends Model
{
    use HasFactory;

    protected $fillable = [
        'materi_id',
        'fase',
        'tingkat',
        'label'
    ];

    function materi() {
        return $this->belongsTo(MateriAjar::class);
    }
}
