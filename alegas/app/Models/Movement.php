<?php

namespace App\Models;

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

}
