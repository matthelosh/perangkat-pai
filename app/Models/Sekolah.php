<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sekolah extends Model
{
    use HasFactory;

    protected $fillable = [
        'npsn',
        'nss',
        'nama',
        'alamat',
        'rt',
        'rw',
        'desa',
        'kecamatan',
        'kode_pos',
        'kabupaten',
        'telp',
        'email',
        'website',
        'logo',
        'nama_ks',
        'nip_ks'
    ];

    function rombels() {
        return $this->hasMany(Rombel::class, 'sekolah_id','npsn');
    }

    function gurus() {
        return $this->belongsToMany(Guru::class, 'guru_sekolah', 'sekolah_id', 'guru_id');
    }

    function teachers() {
        return $this->hasMany(Guru::class, 'sekolah_id','npsn');

    }
}
