<?php

namespace Database\Seeders;

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

        $productLinksFromHtml = $this->getHtmlFromPage('http://gpsenorbita.sytes.net/alegases/productos/abmproductos.php');
        $this->parseHtmlFromProductListPage($productLinksFromHtml);

        foreach ($this->productsData as $productData) {
            $htmlProductPage = $this->getHtmlFromPage($productData['link']);
            $data = $this->parseHtmlFromProductPage($htmlProductPage);
            $productData['data'] = $data;
            $productData['product_type'] = ProductType::where('name', 'like', strtoupper(trim($productData['product_type_name'])))->first();
            $this->createProduct($productData);
        }
    }
    private function getHtmlFromPage($link)
    {
        $responseFromGeneralList = Http::get($link);
        return $responseFromGeneralList->body();
    }

    private function createProduct($productData)
    {
        $product = new Product();
        $product->serial_number = strtoupper(trim($productData['data']['serial_number']));
        $product->is_out = false;
        $product->product_type_id = $productData['product_type']?->id;
        $product->provider_id = 1;
        $product->save();
    }


    private function parseHtmlFromProductListPage(string $htmlResponseBody)
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($htmlResponseBody);
        $child_elements = $dom->getElementsByTagName('td');
        foreach ($child_elements as $element) {
            if ($element->getAttribute('class') === 'detalle' && trim($element->nodeValue) !== '') {
                $this->productsData[] = [
                    'link' => 'http://gpsenorbita.sytes.net/alegases/productos/' . $element->nextSibling->childNodes[1]->getAttribute('href'),
                    'product_type_name' => $element->nodeValue
                ];
            }
        }
    }
    private function parseHtmlFromProductPage(string $htmlResponseBody)
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($htmlResponseBody);
        $child_elements = $dom->getElementsByTagName('td');
        $product = [];
        foreach ($child_elements as $element) {
            if ($element->getAttribute('width') === '340' && $element->previousSibling->nodeValue === ' Código: ') {
                $product['serial_number'] = $element->nodeValue;
            }
        }
        return $product;
    }
}
