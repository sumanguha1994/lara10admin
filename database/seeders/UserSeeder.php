<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::Create([
            'role_id' => '1',
            'name' => 'cdi admin',
            'email' => 'cdi_admin@yopmail.com',
            'phone' => '8745741225',
            'password' => Hash::make('123456')
        ]);
    }
}
