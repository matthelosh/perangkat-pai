<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tapel;
use App\Models\Semester;

class TasemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tapels = [
            [
                'kode' => '2324',
                'label' => '2023/2024',
                'status' => 'active',
            ],
            [
                'kode' => '2425',
                'label' => '2024/2025',
                'status' => 'inactive',
            ],
            [
                'kode' => '2526',
                'label' => '2025/2026',
                'status' => 'inactive',
            ],
            
        ];

        $semesters = [
             [
                'kode' => '1',
                'label' => 'Ganjil',
                'status' => 'active',
            ],
            [
                'kode' => '2',
                'label' => 'Genap',
                'status' => 'inactive',
            ],

        ];

        foreach($tapels as $tapel) {
            Tapel::create([

                'kode' => $tapel['kode'],
                'label' => $tapel['label'],
                'status' => $tapel['status']
            ]);
        }

        foreach($semesters as $semester) {
            Semester::create([
                'kode' => $semester['kode'],
                'label' => $semester['label'],
                'status' => $semester['status']
            ]);
        }
    }
}
