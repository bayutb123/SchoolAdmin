<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = [
            [
                'name' => 'Administrator', // 1
                'description' => 'Admin',
            ],
            [
                'name' => 'Staff',
                'description' => 'Staff',
            ],
        ];

        foreach ($role as $key => $value) {
            Role::create($value);
        }

    }
}
