<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([[
            'role' => 'admin',
            'name' => 'Admin',
            'slug' => 'admin',
        ],[
            'role' => 'user',
            'name' => 'User',
            'slug' => 'user',
        ]]);
    }
}
