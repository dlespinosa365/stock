<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Location;
use Illuminate\Support\Carbon;

class ProductMaintenance extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'trigger_date' => 'datetime:Y-m-d',
    ];


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($productMaintenance) {
            $daysToAdd = env('DAYS_FOR_MAINTENANCE') ? env('DAYS_FOR_MAINTENANCE') : 60;
            $productMaintenance->is_sended = false;
            $productMaintenance->trigger_date = Carbon::now()->addDays($daysToAdd)->toDateString();
        });
    }

    protected function scopeGetReadyToSend($query) {
        return $query
                ->with(['product', 'location'])
                ->where('is_sended', false)
                ->where('trigger_date', Carbon::now()->toDateString());
    }
}
