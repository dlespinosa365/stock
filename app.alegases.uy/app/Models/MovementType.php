<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class MovementType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public static $INGRESS = 1;
    public static $SERVICE = 2;
    public static $CLIENT_OUT = 3;
    public static $LOCAL_OUT = 4;
    public static $INTERN = 5;

}
