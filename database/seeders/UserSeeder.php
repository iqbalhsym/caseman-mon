<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lokal Super Admin
        \App\Models\User::updateOrCreate(
            ['username' => 'adminarya'],
            [
                'name' => 'Admin Arya',
                'email' => 'adminarya@gmail.com',
                'role_id' => 1,
                'password' => bcrypt('admin123'),
            ]
        );

        // Administrator LDAP
        $hud = \App\Models\User::where('username', 'mohammad.hud')->first();
        if (!$hud) {
            $hud = new \App\Models\User();
            $hud->username = 'mohammad.hud';
        }
        $hud->name = 'Mohammad Hud';
        $hud->email = 'Mohammad.Hud@rs.ui.ac.id';
        $hud->role_id = 1;
        $hud->password = bcrypt('password_ldap_placeholder');
        $hud->save();
    }
}
