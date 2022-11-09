<?php

namespace App\Http\Livewire;

use App\Models\Location;
use App\Models\Movement;
use App\Models\Product;
use Livewire\Component;

class StockComponent extends CustomMasterComponent
{
    public $location_id;
    public $serial_number;
    public $last_movements = [];
    public $product;
    public function render()
    {
        $locations = Location::all();
        $products = Product::byCurrentLocation($this->location_id)
            ->withSerialNumber($this->serial_number)
            ->IsOutFalse()
            ->orderBy('id', 'DESC')
            ->paginate(10);
        return view('livewire.stock-list', [
               'products' => $products,
               'locations' => $locations
        ]);
    }

    public function showThreeLastMovementFn(int $product_id) {
        $this->product = Product::find($product_id);
        $this->last_movements = Movement::where('product_id', $product_id)
            ->orderBy('created_at', 'DESC')
            ->limit(3)
            ->get();
        ;
    }
}
