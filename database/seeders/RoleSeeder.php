<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['id' => 1, 'name' => 'administrator',    'alias' => 'Administrator'],
            ['id' => 2, 'name' => 'casemanager',      'alias' => 'Case Manager'],
            ['id' => 3, 'name' => 'supervisor',       'alias' => 'Supervisor'],
            ['id' => 4, 'name' => 'viewer',           'alias' => 'Viewer'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['id' => $role['id']],
                [
                    'name'       => $role['name'],
                    'alias'      => $role['alias'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
