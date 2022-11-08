<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class MovementTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('movement_types')->insert([
            'name' => 'Ingreso',
            'id' => 1
        ]);
        DB::table('movement_types')->insert([
            'name' => 'Servicio',
            'id' => 2
        ]);
        DB::table('movement_types')->insert([
            'name' => 'Baja de cliente',
            'id' => 3
        ]);
        DB::table('movement_types')->insert([
            'name' => 'Baja de local',
            'id' => 4
        ]);
    }
}
