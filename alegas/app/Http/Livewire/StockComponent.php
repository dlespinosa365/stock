<?php

namespace App\Http\Livewire;

use App\Models\Location;
use App\Models\Movement;
use App\Models\Product;
use Livewire\Component;

class StockComponent extends CustomMasterComponent
{
    public $locationId = null;
    public $serialNumber = '';
    public $lastMovements = [];
    public $productToFind = null;
    public function render()
    {
        $locations = Location::orderBy('name')->get();
        $this->log('locationId', $this->locationId);
        $products = Product::with('productType')->byCurrentLocation($this->locationId)
            ->withSerialNumber($this->serialNumber)
            ->IsOutFalse()
            ->orderBy('id', 'DESC')
            ->paginate(10);
        return view('livewire.stock-list', [
               'products' => $products,
               'locations' => $locations
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
}
