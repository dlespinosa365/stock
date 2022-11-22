<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('locations')->insert([
            'name' => 'Barra',
            'address' => 'Barra',
            'phone' => '',
            'location_type' => Location::$LOCATION_TYPE_INTERN,
            'id' => 4
        ]);
        DB::table('locations')->insert([
            'name' => 'Local',
            'address' => 'Local',
            'phone' => '',
            'location_type' => Location::$LOCATION_TYPE_INTERN,
            'id' => Location::$LOCATION_INTERN_ID
        ]);
        DB::table('locations')->insert([
            'name' => 'Camion Leonel',
            'address' => 'Camion Leonel',
            'phone' => '',
            'location_type' => Location::$LOCATION_TYPE_TRUCK,
            'id' => 2
        ]);
        DB::table('locations')->insert([
            'name' => 'Camion German',
            'address' => 'Camion German',
            'phone' => '',
            'location_type' => Location::$LOCATION_TYPE_TRUCK,
            'id' => 3
        ]);

    }
}
