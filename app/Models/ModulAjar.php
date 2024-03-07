<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulAjar extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'rombel_id',
        'guru_id',
        'atp_id',
        'judul',
    ];
}
