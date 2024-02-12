<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nisn',
        'nama',
        'jk',
        'sekolah_id'
    ];

    public function rombels() : BelongsToMany
    {
        return $this->belongToMany(Rombel::class);
    }
}
