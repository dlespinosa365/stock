<?php

namespace Database\Seeders;

use DOMDocument;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productLinksFromHtml = $this->getHtmlFromPage('http://gpsenorbita.sytes.net/alegases/productos/abmproductos.php');
        $listLinksForProducts = $this->parseHtmlFromProductListPage($productLinksFromHtml);
        foreach ($listLinksForProducts as $link) {
            $htmlProductPage = $this->getHtmlFromPage($link);
            $productData = $this->parseHtmlFromProductPage($htmlProductPage);
            dd($productData);
        }
    }
    private function getHtmlFromPage($link)
    {
        $responseFromGeneralList = Http::get($link);
        return $responseFromGeneralList->body();
    }

    private function createProduct($productData){

    }


    private function parseHtmlFromProductListPage(string $htmlResponseBody)
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($htmlResponseBody);
        $child_elements = $dom->getElementsByTagName('td');
        $productLinks = [];
        foreach ($child_elements as $element) {
            if ($element->getAttribute('class') === 'detalle' ) {
                $productLinks[] = 'http://gpsenorbita.sytes.net/alegases/productos/'.$element->nextSibling->childNodes[1]->getAttribute('href');
            }
        }
        return $productLinks;
    }
    private function parseHtmlFromProductPage(string $htmlResponseBody)
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($htmlResponseBody);
        $child_elements = $dom->getElementsByTagName('td');
        $product = [];
        foreach ($child_elements as $element) {
            if ($element->getAttribute('width') === '340' && $element->previousSibling->nodeValue === ' Código: ') {
                $product[] = [
                    'serial_number' => $element->nodeValue,
                ];
            }
        }
        return $product;
    }
}
