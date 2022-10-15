<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class Location extends Model
{
    use HasFactory;

    public static $LOCATION_TYPE_CUSTOMER = 1;

    public static $LOCATION_TYPE_TRUCK = 2;
    public static $LOCATION_TYPE_INTERN = 3;

    protected $fillable = ['name', 'address', 'phone', 'location_type'];

}
