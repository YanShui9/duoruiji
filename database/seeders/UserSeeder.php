<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => '管理员',
            'email' => 'admin@test.com',
            'password' => Hash::make('123456'),
            'is_admin' => 1,
        ]);
    }
}
