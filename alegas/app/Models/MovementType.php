<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class MovementType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public static $INGRESO = 1;
    public static $SERVICIO = 2;
    public static $BAJA = 3;

}
