<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $titles = [
            'Penasehat I',
            'Penasehat II',
            'Penasehat III',
            'Ketua',
            'Wakil Ketua',
            'Sekretaris I',
            'Sekretaris II',
            'Bendahara I',
            'Bendahara II',
            'Seksi Organisasi',
            'Seksi Kurikulum',
            'Seksi Seni',
            'Seksi Operator'
        ];

        for ($i = 0; $i < count($titles); $i++) {
            Jabatan::create([
                'nama' => $titles[$i],
            ]);
        }
    }
}
