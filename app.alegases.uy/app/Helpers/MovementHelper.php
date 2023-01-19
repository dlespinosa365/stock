<?php

namespace App\Helpers;
use App\Models\Location;
use App\Models\MovementType;


class MovementHelper
{

    static public function resolveMovementType($movement)
    {
        if ($movement->location_from_id && !$movement->location_to_id) {
            // si tiene location from y no location to
            // aca es una baja de producto
            // puede ser baja de local o baja de cliente
            $location_from = Location::find($movement->location_from_id);
            if ($location_from->location_type === Location::$LOCATION_TYPE_CUSTOMER) {
                // si la baja es desde un cliente
                return MovementType::$CLIENT_OUT;
            } elseif ($location_from->location_type === Location::$LOCATION_TYPE_INTERN) {
                // si la baja es desde una locacion interna
                return MovementType::$LOCAL_OUT;
            }
            elseif ($location_from->location_type === Location::$LOCATION_TYPE_TRUCK) {
                // si la baja es desde un camion
                return MovementType::$LOCAL_OUT;
            }
        } else if (!$movement->location_from_id && $movement->location_to_id) {
             // si no tiene location from y si tiene location to
             // si la locacion hacia la que va es una interna o un camion es un ingreso
             $location_to = Location::find($movement->location_to_id);
             // si la locacion hacia la que va es un cliente es un servicio
             if ($location_to->location_type === Location::$LOCATION_TYPE_CUSTOMER) {
                // si va hacia una locacion de cliente es  un servicio
                return MovementType::$SERVICE;
            } else if ($location_to->location_type === Location::$LOCATION_TYPE_INTERN) {
                // si va hacia una locacion interna es in movimiento interno
                return MovementType::$INGRESS;
            } else if ($location_to->location_type === Location::$LOCATION_TYPE_TRUCK) {
                // si va hacia una locacion camion es un movimiento interno
                return MovementType::$INGRESS;
            }
        } else if ($movement->location_to_id && $movement->location_from_id) {
            // si tiene location from y tiene location to
            // aca puede ser un movimiento interno o puede ser un servicio
            $location_to = Location::find($movement->location_to_id);
            if ($location_to->location_type === Location::$LOCATION_TYPE_CUSTOMER) {
                // si va hacia una locacion de cliente es  un servicio
                return MovementType::$SERVICE;
            } else if ($location_to->location_type === Location::$LOCATION_TYPE_INTERN) {
                // si va hacia una locacion interna es in movimiento interno
                return MovementType::$INTERN;
            } else if ($location_to->location_type === Location::$LOCATION_TYPE_TRUCK) {
                // si va hacia una locacion camion es un movimiento interno
                return MovementType::$INTERN;
            }
        } else {
            //si no tiene ninguna de las 2 lo consideraremos como una baja
            return MovementType::$LOCAL_OUT;
        }
    }
}
