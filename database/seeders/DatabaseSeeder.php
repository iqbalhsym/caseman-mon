<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'adminarya',
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
            'role_id' => 1,
            'status' => 'active'
        ]);
    }
}
