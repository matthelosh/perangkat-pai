<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kaldik extends Model
{
    use HasFactory;

    protected $fillable = [
        "tapel_id",
        "label",
        "mulai",
        "selesai",
        "deskripsi",
        "is_libur",
        "warna",
        'pelaksana'
    ];
}
