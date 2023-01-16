<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductType;
use DOMDocument;
use App\Helpers\CustomHelper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Location;
use App\Models\Customer;



class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $listLinks = $this->getUrlList();
        foreach ($listLinks as $link) {
            $customerPageRequest = Http::get($link);
            $customerPageAttributes = $this->parseHtmlFromCustomerPage($customerPageRequest->body());
            $customerPageAttributes['source_url'] = $link;
            if (in_array($customerPageAttributes['rut'], ['', '0']) && $customerPageAttributes['social_reason'] === '') {
                continue;
            }
            $this->createACustomer($customerPageAttributes);
        }
    }

    private function createACustomer($customerData)
    {
        $address = $customerData['address'];
        if (!$this->isTheColumnName($customerData['zona'], 'especificado')) {
            $address .= ', ' . $customerData['zona'];
        }
        if (!$this->isTheColumnName($customerData['departamento'], 'especificado')) {
            $address .= ', ' . $customerData['departamento'];
        }
        if (strtoupper($customerData['nombre'])) {
            $name = strtoupper($customerData['nombre']);
        } elseif  (strtoupper($customerData['social_reason'])) {
            $name = strtoupper($customerData['social_reason']);
        } else {
            $name = $customerData['rut'];
        }

        $location = new Location();
        $location->name = $name;
        $location->address = strtoupper($address);
        $location->phone = $this->formatPhone($customerData['phone']);
        $location->location_type = Location::$LOCATION_TYPE_CUSTOMER;
        $location->save();

        $customer = new Customer();
        $customer->social_reason = $name;
        $customer->rut = $customerData['rut'];
        $customer->email = $customerData['email'];
        $customer->location_id = $location->id;
        $customer->external_number = $customerData['externo'];
        $customer->save();

        foreach ($customerData['products'] as $p) {
            $product = new Product();
            $product->serial_number = $p['serial'];
            $product->is_out = false;
            $product->current_location_id = $location->id;
            $product->product_type_id = $this->resolveProductTypeId($p['productType']);
            $product->save();
        }
    }

    private function resolveProductTypeId($productTypeName) {
        if (!$productTypeName) {
            return null;
        }
        $productType = ProductType::where('name', 'like', '%'.$productTypeName. '%')->first();
        return $productType?->id;
    }

    private function formatPhone(string $phone)
    {
        $res = preg_replace("/[^0-9]/", '', $phone);
        return $res;
    }

    private function getUrlList()
    {
        $responseFromGeneralList = Http::asForm()->post(
            'http://gpsenorbita.sytes.net/alegases/empresas/buscoemp2.php',
            [
                'buscar' => '',
                'vcampo' => '1',
                'enviar' => 'Buscar'
            ]
        );
        $links = $this->parseHtmlFromGeneralListPage($responseFromGeneralList->body());
        return $links;
    }

    private function isTheColumnName(string $nameFromTd, string $nameToCompare)
    {
        $nameFromTd = strtoupper(trim($nameFromTd));
        $nameToCompare = strtoupper($nameToCompare);
        return str_contains($nameFromTd, $nameToCompare);
    }

    private function parseHtmlFromGeneralListPage($html)
    {
        $dom = new DomDocument();
        @$dom->loadHTML($html);
        $child_elements = $dom->getElementsByTagName('td');
        $links = [];
        foreach ($child_elements as $element) {
            if ($element->getAttribute('class') === 'detalle') {
                $links[] = 'http://gpsenorbita.sytes.net/alegases/empresas/'. $element->childNodes->item(1)->getAttribute('href'). '&opcion=3';
            }
        }
        return $links;
    }

    private function parseHtmlFromCustomerPage(string $html)
    {
        $dom = new DomDocument();
        @$dom->loadHTML($html);
        $tds = $dom->getElementsByTagName('td');
        $customer = [];
        foreach ($tds as $element) {
            if ($element->getAttribute('class') === 'titulo') {
                if ($this->isTheColumnName($element->nodeValue, 'externo')) {
                    $customer['externo'] = CustomHelper::removeSpecialsChars($element->nextElementSibling->nodeValue);
                }
                if ($this->isTheColumnName($element->nodeValue, 'nombre')) {
                    $customer['nombre'] = CustomHelper::removeSpecialsChars($element->nextElementSibling->nodeValue);
                }
                if ($this->isTheColumnName($element->nodeValue, 'social')) {
                    $customer['social_reason'] = CustomHelper::removeSpecialsChars($element->nextSibling->nodeValue);
                }
                if ($this->isTheColumnName($element->nodeValue, 'direcci')) {
                    $customer['address'] = CustomHelper::removeSpecialsChars($element->nextSibling->nodeValue);
                }
                if ($this->isTheColumnName($element->nodeValue, 'telefono')) {
                    $customer['phone'] = CustomHelper::removeSpecialsChars($element->nextSibling->nodeValue);
                }
                if ($this->isTheColumnName($element->nodeValue, 'email')) {
                    $customer['email'] = CustomHelper::removeSpecialsChars($element->nextSibling->nodeValue);
                }
                if ($this->isTheColumnName($element->nodeValue, 'rut')) {
                    $customer['rut'] = CustomHelper::removeSpecialsChars($element->nextSibling->nodeValue);
                }
                if ($this->isTheColumnName($element->nodeValue, 'zona')) {
                    $customer['zona'] = CustomHelper::removeSpecialsChars($element->nextSibling->nodeValue);
                }
                if ($this->isTheColumnName($element->nodeValue, 'departamento')) {
                    $customer['departamento'] = CustomHelper::removeSpecialsChars($element->nextSibling->nodeValue);
                }
            }
        }
        $table = $dom->getElementsByTagName('table')->item(1);
            $trs = $table->getElementsByTagName('tr');
            $products = [];
            if (count($trs) > 1) {
                for ($i=1; $i < count($trs); $i++) {
                    $products[] = [
                        'productType' => trim($trs->item($i)->childNodes->item(1)->nodeValue),
                        'serial' => trim($trs->item($i)->childNodes->item(5)->nodeValue)
                    ];
                }
            }
        $customer['products'] = $products;
        return $customer;
    }
}
