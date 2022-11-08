<?php

namespace App\Observers;

use App\Models\Movement;
use App\Models\Product;

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
    }
/**
     * Handle the Movement "updated" event.
     *
     * @param  \App\Models\Movement  $movement
     * @return void
     */
    public function updated(Movement $movement)
    {
        $product = Product::find($movement->product_id);
        $product->current_location_id = $movement->location_to_id;
        $product->save();
    }
}
