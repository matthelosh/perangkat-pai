<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guru = Role::where('name', 'gpai')->first();
        $guru->syncPermissions([
            'read guru',
            'update guru',
            'read sekolah',
            'update sekolah',
            'create siswa',
            'read siswa',
            'update siswa',
            'delete siswa',
            'create agenda',
            'read agenda',
            'update agenda',
            'delete agenda',
            'read user',
            'update user',
            'read menu',
            'create rombel',
            'read rombel',
            'update rombel',
            'delete rombel',
            'create jadwal',
            'read jadwal',
            'update jadwal',
            'delete jadwal',
            'read fase',
            'read cp',
            'create tp',
            'read tp',
            'update tp',
            'delete tp',
            'create atp',
            'read atp',
            'update atp',
            'delete atp',
            'create prota',
            'read prota',
            'update prota',
            'delete prota',
            'create prosem',
            'read prosem',
            'update prosem',
            'delete prosem',
            'create ma',
            'read ma',
            'update ma',
            'delete ma',
        ]);
    }
}
