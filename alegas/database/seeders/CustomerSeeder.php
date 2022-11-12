<?php

namespace Database\Seeders;

use DOMDocument;
use App\Helpers\CustomHelper;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


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
        dd($listLinks);

    }

    private function getUrlList()
    {
        $customersData = [];
        $responseFromGeneralList = Http::asForm()->post(
            'http://gpsenorbita.sytes.net/alegases/empresas/buscoemp2.php',
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
            $customersData[] = $customerPageAttributes;
        }
        dd($customersData);
        // hola benja en este array vas a tener
        /**
         * [
         *   "externo" => "235"
         *   "nombre" => "FERNANDEZ LUIS"
         *   "social_reason" => "FERNANDEZ LUIS"
         *   "address" => "AURELIA VIERA 3016"
         *   "rut" => "0"
         *   "phone" => "51 476.02."
         *   "zona" => "no especificado"
         *   "email" => ""
         *   "departamento" => "no especificado"
         *   "source_url" => "http://gpsenorbita.sytes.net/alegases/empresas/muestroemp.php?codigo=230"
         *   ]
         * un objeto asi, esa es toda la data que tenemos ahora debes usar eso para crear un customer en nuestra BD,
         * recuerda que el customer tiene asociada una location, por tanto hay que crear la location primero, obtener el id y luego crear
         * el customer con ese Id de location
         * para crear la location recomiendo usar
         * los modelos correspondientes de Location y Customer
         * de esa forma puedes crear una nueva location $location = new Location(); luego llevarle todos los valores
         * y despues hacer el $location->save() para que se guarde en la DB, una vez guardada podes acceder al $location->id que vas a usar
         * para crear el customer.
         * Buena suerte con el mismo principio haz el otro seeder de productos.
         *
         */
        return $customersData;
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
            $urls[] = 'http://gpsenorbita.sytes.net/alegases/empresas/muestroemp.php?codigo=' . $number;
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
