<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
    }
}
