<?php

namespace Database\Seeders;

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
                $links[] = 'http://gpsenorbita.sytes.net/alegases/empresas/'. $element->childNodes->item(1)->getAttribute('href');
            }
        }
        return $links;
    }

    private function parseHtmlFromCustomerPage(string $html)
    {
        $dom = new DomDocument();
        @$dom->loadHTML($html);
        $child_elements = $dom->getElementsByTagName('td');
        $customer = [];
        foreach ($child_elements as $element) {
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
        return $customer;
    }
}
