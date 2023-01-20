<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use App\Models\Location;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Livewire\Component;

class PrintStockByLocationComponent extends CustomMasterComponent
{

    public $idLocation;


    public function mount($idLocation)
    {
        $this->idLocation = $idLocation;
    }
    public function render()
    {
        $date = Carbon::now()->toDateString();
        $location = Location::find($this->idLocation);
        $products = Product::join('product_types', 'product_types.id', '=', 'products.product_type_id')
            ->orderBy('product_types.name')
            ->byCurrentLocation($this->idLocation)
            ->IsOutFalse()
            ->select('products.*')
            ->get();
        return view('livewire.print-stock-by-location', [
               'products_to_print' => $products,
               'date' => $date,
               'location' => $location
        ])->layout('layouts.print',
        [
            'header' => 'Listado de Productos',
        ]
    );;
    }
}
