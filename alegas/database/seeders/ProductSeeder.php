<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\ProductType;
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
        $listLinks = $this->getUrlList();
       
    }
    private function getUrlList()
    {
        $productData = [];
        $responseFromGeneralList = Http::asForm()->post(
            'http://gpsenorbita.sytes.net/alegases/productos/muestropro.php',
            [
                'buscar' => '',
                'vcampo' => '1',
                'enviar' => 'Buscar'
            ]
        );
        $links = $this->parseHtmlFromGeneralListPage($responseFromGeneralList->body());
        foreach ($links as $link) {
            $customerPageRequest = Http::get($link);
            $customerPageAttributes = $this->parseHtmlFromCustomerPage($customerPageRequest->body());
            $customerPageAttributes['source_url'] = $link;
            $productData[] = $customerPageAttributes;
        }
        dd($productData);
        
        $location = new Location();


        $location->save();

        DB::table('products')->insert([
            [
            "id" => $productData->id,
            "serial_number" => $productData->serial_number,
            "product_type_id" => $productData->product_type_id,
            "provider_id" => $productData->provider_id,
            "current_location_id" => $productData->current_location_id,
            ]
        ]);
        return $productData;
    }


    private function isTheColumnName(string $nameFromTd, string $nameToCompare)
    {
        $nameFromTd = strtoupper(trim($nameFromTd));
        $nameToCompare = strtoupper($nameToCompare);
        return str_contains($nameFromTd, $nameToCompare);
    }

    private function parseHtmlFromGeneralListPage($html)
    {
        $lastPos = 0;
        $urls = [];
        while (($lastPos = strpos($html, 'codigo=', $lastPos)) !== false) {
            $initialNumber = $lastPos + 7;
            $number = '';
            while (in_array($html[$initialNumber], ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'])) {
                $number .= $html[$initialNumber];
                $initialNumber++;
            }
            $urls[] = 'http://gpsenorbita.sytes.net/alegases/productos/muestropro.php?codigo=' . $number;
            $lastPos = $lastPos + strlen('codigo=');
        }
        return $urls;
    }

    private function parseHtmlFromCustomerPage(string $html)
    {
        $dom = new DomDocument();
        @$dom->loadHTML($html);
        $child_elements = $dom->getElementsByTagName('td');
        $customer = [];
        foreach ($child_elements as $element) {
            if ($element->getAttribute('class') === 'titulo') {
                if ($this->isTheColumnName($element->nodeValue, 'serial_number')) {
                    $customer['serial_number'] = CustomHelper::removeSpecialsChars($element->nextElementSibling->nodeValue);
                }
                if ($this->isTheColumnName($element->nodeValue, 'product_type_id')) {
                    $customer['product_type_id'] = CustomHelper::removeSpecialsChars($element->nextElementSibling->nodeValue);
                }
                if ($this->isTheColumnName($element->nodeValue, 'provider_id')) {
                    $customer['provider_id'] = CustomHelper::removeSpecialsChars($element->nextSibling->nodeValue);
                }
                if ($this->isTheColumnName($element->nodeValue, 'current_location_id')) {
                    $customer['current_location_id'] = CustomHelper::removeSpecialsChars($element->nextSibling->nodeValue);
                }
            }
        }
        return $customer;
    }
}
