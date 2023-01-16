<?php

namespace App\Http\Livewire;


use App\Models\Location;
use App\Models\Movement;
use App\Models\MovementType;
use App\Models\ProductMaintenance;
use Illuminate\Database\Eloquent\Builder;

class ProductMaintenanceListComponent extends CustomMasterComponent
{

    public function render()
    {
        $productMaintenances = ProductMaintenance::
                    with(['product', 'location'])
                    ->orderBy('id', 'DESC')
                    ->paginate(10);

        return view('livewire.product-maintenance-list', [
               'productMaintenances' => $productMaintenances,
        ]);
    }
}
