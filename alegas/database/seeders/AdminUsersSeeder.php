<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class AdminUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Denis',
            'email' => 'dlespinosa365@gmail.com',
            'password' => bcrypt('Testing123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Benjamin',
            'email' => 'benjamalo02@gmail.com',
            'password' => bcrypt('Testing123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Gaby',
            'email' => '2.gabiiy@gmail.com',
            'password' => bcrypt('Testing123'),
        ]);
        DB::table('users')->insert([
            'name' => 'Alejandro',
            'email' => 'alejandro',
            'password' => bcrypt('ventas@alegases.uy'),
        ]);
    }
}
