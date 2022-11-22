<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Product;
use App\Models\ProductType;
use DOMDocument;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;


class ProductSeeder extends Seeder
{

    private $productsData = [];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $postUrl = 'http://gpsenorbita.sytes.net/alegases/stock/veoinventario.php';
        $ids = ['11', '5', '7', '8', '10'];
        foreach ($ids as $id) {
            $htmlListRequest = Http::asForm()->timeout(3)->post($postUrl, [
                'enviar' => 'buscar',
                'vubi' => $id
            ]);
            $this->addProductFromHtmlList($htmlListRequest->body());
        }

    }

    private function addProductFromHtmlList($htmlList) {
        $dom = new DOMDocument();
        @$dom->loadHTML($htmlList);
        $trs = $dom->getElementsByTagName('tr');

        for ($i = 2; $i < count($trs); $i++) {
            $productSerial = strtoupper(trim($trs[$i]->childNodes[1]->nodeValue));
            $productType = strtoupper(trim($trs[$i]->childNodes[2]->nodeValue));
            $this->productsData[$productSerial.''] = [
                'serial' => $productSerial,
                'type_name' => $productType,
                'type' => $this->resolveProductTypeId($productType),
                'current_location' => $this->resolveProductCurrentLocation($productSerial)
            ];
            $this->createProduct($this->productsData[$productSerial.'']);
        }

    }


    private function createProduct($productData)
    {
        $product = new Product();
        $product->serial_number = strtoupper(trim($productData['serial']));
        $product->is_out = $productData['current_location'] ? false : true ;
        $product->product_type_id = $productData['type']?->id;
        $product->provider_id = 2;
        $product->current_location_id = $productData['current_location'];
        $product->save();
    }

    private function resolveProductTypeId($productTypeName) {
        if (!$productTypeName) {
            return null;
        }
        $productType = ProductType::where('name', 'like', '%'.$productTypeName. '%')->first();
        return $productType;
    }

    private function resolveProductCurrentLocation($serial) {
        $urlCurrent = 'http://gpsenorbita.sytes.net/alegases/stock/veoincidentes.php';
        $htmlListRequest = Http::asForm()->timeout(3)->post($urlCurrent, [
                'vserie' => $serial
        ]);
        $dom = new DOMDocument();
        @$dom->loadHTML($htmlListRequest->body());
        $tds = $dom->getElementsByTagName('td');
        for ($i=0; $i < count($tds) ; $i++) {
            if($tds[$i]->getAttribute('class')=== 'detalle80'){
                $finalTd = trim($tds[$i]->nodeValue);
            }
        }
        $location = null;
        if(isset($finalTd)) {

            if($finalTd === 'cambio de ubicacion a ALEJANDRO'){
                $location = 1;    //Id de ubicacion --> Local
            }
            if($finalTd === 'cambio de ubicacion a RICHARD'){
                $location = 1;
            }
            if($finalTd === 'cambio de ubicacion a camion german'){
                $location = 3;
            }
            if($finalTd === 'cambio de ubicacion a PERDIDOS -NO UBICADOS'){
                $location = 1;
            }
            if(strpos($finalTd,'Ingreso')){
                $location = 1;
            }
            if(strpos($finalTd,'traspaso')){
                $name =  substr($finalTd, 0, 10);
                $location = $this->returnLocationByName($name);
            }
        }
        return $location;
    }

    private function returnLocationByName($name){
        return Location::where('name', 'like', $name)->first()?->id;
    }

}

