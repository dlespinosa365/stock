<?php

namespace Database\Seeders;

use App\Models\ProductType;
use DOMDocument;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;


class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productTypesValuesHtml = $this->getHtmlFromListPage();
        $valuesFromHtml = $this->parseHtmlFromProductListPage($productTypesValuesHtml);
        foreach ($valuesFromHtml as $name) {
            $this->createProductType($name);
        }
    }
    private function getHtmlFromListPage()
    {
        $productTypeData = [];
        $responseFromGeneralList = Http::get('http://gpsenorbita.sytes.net/alegases/productos/abmproductos.php');
        //tipos de productos
        return $responseFromGeneralList->body();
    }

    private function createProductType(string $name){
        $productType = new ProductType();
        $productType->name = $name;
        $productType->save();
    }


    private function parseHtmlFromProductListPage(string $htmlResponseBody)
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($htmlResponseBody);
        $child_elements = $dom->getElementsByTagName('td');
        $productTypes = [];
        foreach ($child_elements as $element) {
            if ($element->getAttribute('class') === 'detalle' &&  trim($element->nodeValue) !== '') {
                $productTypes[] = strtoupper(trim($element->nodeValue));
            }
        }
        return $productTypes;
    }
}
