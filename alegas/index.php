<?php
include "vendor/autoload.php";
use Goutte\Client;

$cr = new Client();

$productos=[];

$cr_ = $cr->request("GET", "http://gpsenorbita.sytes.net/alegases/menu/menu.htm");

$cr_->filter("tbody tr")->each(function($node) use(&$productos){
    $nombre = $node->filter("td .detalle")->text();
    $cliente = $node->filter("td option")->text();
    dd($nombre);
    array_push($productos,[
        "nombre" => $nombre,
        "cliente" => $cliente,

    ]);
});
