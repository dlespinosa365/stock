<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */ 
    public function run()
    {
        DB::table('products')->insert([
            'id' => 'id',
            'productType' => 'Barra',
            'provider' => 'Barra',
            'currentLocation' => 'currentLocation',
            'serial_number' => 'serial_number',
        ]);
    }
}
