<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\MovementTypeSeeder;
use Database\Seeders\LocationSeeder;
use Database\Seeders\AdminUsersSeeder;
use Database\Seeders\ProviderSeeder;
use Database\Seeders\CustomerSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            MovementTypeSeeder::class,
            LocationSeeder::class,
            AdminUsersSeeder::class,
            ProviderSeeder::class,
            CustomerSeeder::class,
        ]);
    }
}
