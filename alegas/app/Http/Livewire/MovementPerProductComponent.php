<?php

namespace App\Http\Livewire;

use App\Models\Movement;
use Illuminate\Database\Eloquent\Builder;

class MovementPerProductComponent extends CustomMasterComponent
{
    public $serialNumber = '';
    public $lastMovements = [];
    public function render()
    {
        $serial_number = $this->serialNumber;
        $movements = Movement::with(['product','locationFrom', 'locationTo'])
            ->whereHas('product', function (Builder $query) use ($serial_number) {
                $query->where('serial_number', 'like', '%'. $serial_number. '%');
            })
            ->orderBy('id', 'DESC')
            ->paginate(10);
        return view('livewire.movement-per-product-list', [
               'movements' => $movements
        ]);
    }
}
