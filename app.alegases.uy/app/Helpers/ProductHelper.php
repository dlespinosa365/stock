<?php

namespace App\Helpers;
use App\Models\Location;
use App\Models\MovementType;


class ProductHelper
{

    static public function validateProductMovement($product, $location_from_id, $location_to_id)
    {
        $message = '';
        $location_from = $location_from_id ? Location::find($location_from_id) : null;
        $location_to = $location_to_id ? Location::find($location_to_id): null;

        if ($location_from && $location_from->location_type === Location::$LOCATION_TYPE_CUSTOMER) {
            if ($location_to !== null) {
                $message = 'No se puede mover el producto ' . $product->serial_number . ' hacia niguna locacion porque se encuentra en un cliente';
            }
        }
        return $message;
    }
}
