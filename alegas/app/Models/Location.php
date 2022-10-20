<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    public static $LOCATION_TYPE_CUSTOMER = 1;

    public static $LOCATION_TYPE_TRUCK = 2;
    public static $LOCATION_TYPE_INTERN = 3;

    public static $LOCATION_INTERN_ID = 1;

    protected $fillable = ['name', 'address', 'phone', 'location_type'];

    public static function getInterLocal() {
        return Location::find(Location::$LOCATION_INTERN_ID);
    }

}
