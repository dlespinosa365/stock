<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Movement;

class MovementList extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

}
