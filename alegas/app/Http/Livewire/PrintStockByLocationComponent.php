<?php

namespace App\Http\Livewire;

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
        $products = Product::with('productType')->byCurrentLocation($this->idLocation)
            ->IsOutFalse()
            ->orderBy('serial_number')
            ->get();
        return view('livewire.print-stock-by-location', [
               'products_to_print' => $products,
               'date' => $date
        ])->layout('layouts.print',
        [
            'header' => 'Listado de Productos',
        ]
    );;
    }
}
