<?php

namespace App\Http\Livewire;


use App\Models\Location;
use App\Models\Movement;
use App\Models\MovementType;
use Illuminate\Database\Eloquent\Builder;

class MovementListComponent extends CustomMasterComponent
{

    public $serial_number;

    public $movement_type_id;

    public $date_from;

    public $date_to;

    public $location_from_id;
    public $location_to_id;


    public function render()
    {

        $locations = Location::all();
        $MovementTypes = MovementType::all();
        $serial_number = $this->serial_number;
        $movements = Movement::
                    with(['product', 'movementType', 'locationFrom', 'locationTo'])
                    ->whereHas('product', function (Builder $query) use ($serial_number) {
                        $query->where('serial_number', 'like', '%'. $serial_number. '%');
                    })
                    ->movementType($this->movement_type_id)
                    ->dateFrom($this->date_from)
                    ->dateTo($this->date_to)
                    ->fromLocation($this->location_from_id)
                    ->toLocation($this->location_to_id)
                    ->orderBy('id', 'DESC')

                    ->paginate(10);

        return view('livewire.movement-list', [
               'movements' => $movements,
               'locations' => $locations,
               'MovementTypes' => $MovementTypes
            ])
            ->layout('layouts.app',
                [
                    'header' => 'Listado de movimientos'
                ]
            );
    }
    public function resetForm()
    {
        $this->product_serial = '';
        $this->movement_type_id = '';
        $this->date_from = '';
        $this->date_to = '';
        $this->location_from_id = '';
        $this->location_to_id = '';
    }
}
