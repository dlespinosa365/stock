<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('providers')->insert([
            'name' => 'ALUSA',
            'id' => 1
        ]);
        DB::table('providers')->insert([
            'name' => 'CASA',
            'id' => 2
        ]);
        DB::table('providers')->insert([
            'name' => 'NO ESPECIFICADO',
            'id' => 3
        ]);
    }
}
