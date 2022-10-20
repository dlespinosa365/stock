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
            'id' => Location::$LOCATION_INTERN_ID
        ]);
    }
}
