<?php

namespace Database\Seeders;

use App\Models\Soal;
use App\Models\Tp;
use Illuminate\Database\Seeder;

class SoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tps = Tp::all();
        foreach ($tps as $tp) {
            Soal::create([
                'tingkat' => \fake()->randomElement(['1', '2', '3', '4', '5', '6']),
                'tp_id' => $tp->kode,
                'elemen_id' => $tp->elemen_id,
                'guru_id' => \null,
                'agama' => 'Islam',
                'tipe' => \fake()->randomElement(['pilihan', 'isian', 'uraian']),
                'level' => fake()->randomElement(['lot', 'mot', 'hot']),
                'semester' => fake()->randomElement(['1', '2']),
                'pertanyaan' => \fake()->paragraph(),
                'a' => \fake()->paragraph(),
                'b' => \fake()->paragraph(),
                'c' => \fake()->paragraph(),
                'd' => \fake()->paragraph(),
                'kunci' => fake()->randomElement(['a', 'b', 'c', 'd'])
            ]);
        }
    }
}
