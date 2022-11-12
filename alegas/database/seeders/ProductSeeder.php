<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Goutte\Client;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $cr = new Client();

        $productos=[];

        $cr_ = $cr->request("GET", "http://gpsenorbita.sytes.net/alegases/menu/menu.htm");

        $cr_->filter("tbody tr")->each(function($node) use(&$productos){
            $nombre = $node->filter("td .detalle")->text();
            dd($nombre);
            array_push($productos,[
                "nombre" => $nombre,

            ]);
        });

    }
}
