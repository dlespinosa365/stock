<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Location;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['social_reason', 'rut', 'email', 'external_number'];

    public function location()
    {

        return $this->belongsTo(Location::class);
    }

    /**
     * Interact with the user's address.
     *
     * @return  \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function name(): Attribute
    {
        return Attribute::make(
        get: function($value, $attributes) {
                if ($attributes['social_reason']) {
                    return $attributes['social_reason'];
                }
                if ($this->location) {
                    return $this->location->name;
                }
                if ($attributes['rut']) {
                    return $attributes['rut'];
                }
                return 'Sin nombre';
            },
        );
    }
}
