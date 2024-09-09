<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 15; $i++) {
            $name = $faker->sentence(2);
            $description = $faker->paragraph;
            $photo = $faker->imageUrl();

            DB::table('food')->insert([
                'name' => $name,
                'description' => $description,
                'photo' => $photo,
            ]);
        }

        $this->call(UsersTableSeeder::class);
    }
}