<?php

namespace App\Models;

use App\Observers\MovementObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MovementType;
use App\Models\Location;
use App\Models\Product;

class Movement extends Model
{
    use HasFactory;

    protected $fillable = ['description'];

    public function movementType()
    {
        return $this->belongsTo(MovementType::class, 'movement_type_id');
    }
    public function locationFrom()
    {
        return $this->belongsTo(Location::class, 'location_from_id');
    }

    public function locationTo()
    {
        return $this->belongsTo(Location::class, 'location_to_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }


    /**
     * Scope a query to only include users of a given type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFromLocation($query, $location)
    {
        if (!$location) {
            return $query;
        }
        return $query->where('location_from_id', $location);
    }

    /**
     * Scope a query to only include users of a given type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeToLocation($query, $location)
    {
        if (!$location) {
            return $query;
        }
        return $query->where('location_to_id', $location);
    }
    /**
     * Scope a query to only include users of a given type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDateFrom($query, $date)
    {
        if (!$date) {
            return $query;
        }
        return $query->whereDate('created_at', '>=', $date);
    }
    /**
     * Scope a query to only include users of a given type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDateTo($query, $date)
    {
        if (!$date) {
            return $query;
        }
        return $query->whereDate('created_at', '<=', $date);
    }
    /**
     * Scope a query to only include users of a given type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMovementType($query, $movement_type)
    {
        if (!$movement_type) {
            return $query;
        }
        return $query->where('movement_type_id', $movement_type);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($movement) {

            if ($movement->location_from_id && !$movement->location_to_id) {
                // si tiene location from y no location to
                // aca es una baja de producto
                // puede ser baja de local o baja de cliente
                $location_from = Location::find($movement->location_from_id);
                if ($location_from->location_type === Location::$LOCATION_TYPE_CUSTOMER) {
                    // si la baja es desde un cliente
                    $movement->movement_type_id = MovementType::$CLIENT_OUT;
                } elseif ($location_from->location_type === Location::$LOCATION_TYPE_INTERN) {
                    // si la baja es desde una locacion interna
                    $movement->movement_type_id = MovementType::$LOCAL_OUT;
                }
            } else if (!$movement->location_from_id && $movement->location_to_id) {
                 // si no tiene location from y si tiene location to
                // aca es un ingreso de producto
                $movement->movement_type_id = MovementType::$INGRESS;
            } else if ($movement->location_to_id && $movement->location_from_id) {
                // si tiene location from y tiene location to
                // aca puede ser un movimiento interno o puede ser un servicio
                $location_to = Location::find($movement->location_to_id);
                if ($location_to->location_type === Location::$LOCATION_TYPE_CUSTOMER) {
                    // si va hacia una locacion de cliente es  un servicio
                    $movement->movement_type_id = MovementType::$SERVICE;
                } else if ($location_to->location_type === Location::$LOCATION_TYPE_INTERN) {
                    // si va hacia una locacion interna es in movimiento interno
                    $movement->movement_type_id = MovementType::$INTERN;
                } else if ($location_to->location_type === Location::$LOCATION_TYPE_TRUCK) {
                    // si va hacia una locacion camion es un movimiento interno
                    $movement->movement_type_id = MovementType::$INTERN;
                }
            } else {
                //si no tiene ninguna de las 2 lo consideraremos como una baja
                $movement->movement_type_id = MovementType::$LOCAL_OUT;
            }
        });
    }
}
