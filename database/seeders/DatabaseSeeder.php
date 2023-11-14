<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = [
            [
                'name' => 'Admin',
                'last_name' => 'Admin',
                'email' => 'admin@school.sch',
                'password' => 'admin',
                'role_id' => 1,
            ],
        ];

        foreach ($user as $key => $value) {
            User::create($value);
        }

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
