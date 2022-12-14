<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use App\Models\Location;
use App\Models\Movement;
use App\Models\Product;
use Livewire\Component;

class StockComponent extends CustomMasterComponent
{
    public $locationId;
    public $serialNumber;
    public $lastMovements = [];
    public $productToFind = null;
    public function render()
    {
        $customers = Customer::with('location')->orderBy('external_number')->get();
        $intern_locations = Location::where('location_type', Location::$LOCATION_TYPE_INTERN)
                            ->orWhere('location_type', Location::$LOCATION_TYPE_TRUCK)->get();
        $this->log('locationId', $this->locationId);
        $products = Product::with('productType')->byCurrentLocation($this->locationId)
            ->withSerialNumber($this->serialNumber)
            ->IsOutFalse()
            ->orderBy('id', 'DESC')
            ->paginate(10);
        return view('livewire.stock-list', [
               'products' => $products,
               'customers' => $customers,
               'intern_locations' => $intern_locations
        ]);
    }

    public function showThreeLastMovementFn(int $productId) {
        $this->productToFind = Product::find($productId);
        $this->lastMovements = Movement::where('product_id', $productId)
            ->orderBy('created_at', 'DESC')
            ->limit(3)
            ->get();
        ;
    }

    public function closeModalshowThreeLastMovementFn() {
        $this->closeModal('showThreeLastMovement');
    }

    public function print()
    {
        if ($this->locationId) {
            return redirect()->to('/imprimir-stock-por-ubicacion/'. $this->locationId);
            $this->locationId = null;
        }
        $this->sendErrorMessageToSession('Debe seleccionar una ubicacion para imprimir');
    }
}
