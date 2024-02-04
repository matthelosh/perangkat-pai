<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'create guru',
            'read guru',
            'update guru',
            'delete guru',
            'create sekolah',
            'read sekolah',
            'update sekolah',
            'delete sekolah',
            'create siswa',
            'read siswa',
            'update siswa',
            'delete siswa',
            'create agenda',
            'read agenda',
            'update agenda',
            'delete agenda',
            'create user',
            'read user',
            'update user',
            'delete user',
            'create menu',
            'read menu',
            'update menu',
            'delete menu',
            'create rombel',
            'read rombel',
            'update rombel',
            'delete rombel',
            'create jadwal',
            'read jadwal',
            'update jadwal',
            'delete jadwal',
            'create fase',
            'read fase',
            'update fase',
            'delete fase',
            'create cp',
            'read cp',
            'update cp',
            'delete cp',
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
            'create role',
            'read role',
            'update role',
            'delete role',
            'create permission',
            'read permission',
            'update permission',
            'delete permission',
        ];

        foreach($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
