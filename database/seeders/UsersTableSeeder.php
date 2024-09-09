<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'developer',
            'password' => env('DEVELOPER_PASSWORD'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
        User::create([
            'name' => 'ali',
            'password' => env('ALI_PASSWORD'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
