<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Location;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['social_reason', 'rut', 'email'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
