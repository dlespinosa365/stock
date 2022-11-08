<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductType;
use App\Models\Provider;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['serial_number', 'is_out'];


    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function currentLocation()
    {
        return $this->belongsTo(Location::class, 'current_location_id');
    }

    public function scopeByCurrentLocation($query, $location_id) {
        if (!$location_id) {
            return $query;
        }
        return $query->where('current_location_id', $location_id);
    }

    public function scopeWithSerialNumber($query, $serial_number) {
        if (!$serial_number) {
            return $query;
        }
        return $query->where('serial_number', 'like', '%' . $serial_number . '%');
    }

    public function scopeIsOutFalse($query) {
        return $query->where('is_out', 0);
    }

}
