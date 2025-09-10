<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Alice Smith',
            'email' => 'alice@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Bob Jobs',
            'email' => 'bob@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Charlie Brown',
            'email' => 'charlie@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
