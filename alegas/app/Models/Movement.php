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
        return $query->where('created_at', '>=', $date);
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
        return $query->where('created_at', '<=', $date);
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
            $location_to = Location::find($movement->location_to_id);
            $location_from = Location::find($movement->location_from_id);

            $isService = $location_to?->location_type === Location::$LOCATION_TYPE_CUSTOMER;

            $isNew = !$location_from &&
                            $location_to->location_type === Location::$LOCATION_TYPE_INTERN;
            $isClientOut = !$location_to &&
                                $location_from->location_type === Location::$LOCATION_TYPE_CUSTOMER;
            $isLocalOut = !$location_to &&
                            $location_from->location_type === Location::$LOCATION_TYPE_INTERN;
            $isIntern = $location_to &&
                        $location_from &&
                        $location_from->location_type === Location::$LOCATION_TYPE_INTERN &&
                        $location_to->location_type === Location::$LOCATION_TYPE_INTERN;


            if ($isService) {
                $movement->movement_type_id = MovementType::$SERVICE;
            } elseif ($isNew) {
                $movement->movement_type_id = MovementType::$INGRESS;
            } elseif($isClientOut) {
                $movement->movement_type_id = MovementType::$CLIENT_OUT;
            } elseif($isLocalOut) {
                $movement->movement_type_id = MovementType::$LOCAL_OUT;
            } else {
                $movement->movement_type_id = MovementType::$INTERN;
            }
        });
    }
}
