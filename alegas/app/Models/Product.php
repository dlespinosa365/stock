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

}
