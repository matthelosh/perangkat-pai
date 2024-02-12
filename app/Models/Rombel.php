<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rombel extends Model
{
    use HasFactory;
    protected $fillable = [
        'sekolah_id',
        'guru_id',
        'kode',
        'label',
        'tapel',
        'fase',
        'tingkat',
        'is_active'
    ];

    function sekolah() {
        return $this->belongsTo(Sekolah::class, 'sekolah_id', 'npsn');
    }

    function guru() {
        return $this->belongsTo(Guru::class, 'guru_id','nip');
    }

    public function siswas(): BelongsToMany
    {
        return $this->belongsToMany(Siswa::class);
    }
}
