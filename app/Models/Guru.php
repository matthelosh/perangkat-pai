<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Guru extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip',
        'nuptk',
        'no_sertifikat',
        'nrg',
        'gelar_depan',
        'nama',
        'gelar_belakang',
        'jk',
        'foto',
        'hp',
        'alamat',
        'sekolah_id',
        'status_kepegawaian',
        'belajar_id'
    ];


    function sekolah() {
        return $this->belongsTo(Sekolah::class, 'sekolah_id', 'npsn');
    }

    public function sekolahs()
    {
        return $this->belongsToMany(Sekolah::class);
    }

    function rombels() {
        return $this->hasMany(Rombel::class, 'guru_id', 'nip');
    }

    function account() {
        return $this->morphOne(User::class, 'userable');
    }
}
