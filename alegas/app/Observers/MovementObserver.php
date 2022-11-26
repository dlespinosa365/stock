<?php

namespace App\Observers;

use App\Models\Movement;
use App\Models\MovementType;
use App\Models\Product;
use App\Models\ProductMaintenance;
use Illuminate\Support\Carbon;

class MovementObserver
{
    /**
     * Handle the Movement "created" event.
     *
     * @param  \App\Models\Movement  $movement
     * @return void
     */
    public function created(Movement $movement)
    {
        $product = Product::find($movement->product_id);
        $product->current_location_id = $movement->location_to_id;
        $product->save();

        if ($movement->movement_type_id === MovementType::$SERVICE) {
            $this->findAndRemoveProductMaintenance($movement);
            $this->createNewProductMaintenance($movement);
        }
        if ($movement->movement_type_id === MovementType::$CLIENT_OUT) {
            $this->findAndRemoveProductMaintenance($movement);
        }
    }
    public function createNewProductMaintenance(Movement $movement) {
        $productMaintenance = new ProductMaintenance();
        $productMaintenance->product_id = $movement->product_id;
        $productMaintenance->location_id = $movement->location_to_id;
        $productMaintenance->save();
    }

    public function findAndRemoveProductMaintenance(Movement $movement) {
        // dd($movement);
        ProductMaintenance::
        where([
            'product_id' => $movement->product_id,
            'location_id' => $movement->location_from_id,
            'is_sended' => false
        ])
        ->whereDate('trigger_date', '>=', Carbon::now()->toDateString()) // cuando no se ha triguereado aun
        ->delete();
    }

}
