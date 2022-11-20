<?php

namespace App\Http\Livewire;


use App\Models\Location;
use App\Models\Movement;
use App\Models\MovementType;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class MovementListComponent extends CustomMasterComponent
{
    public $show_error_missing_serials = false;
    public $serials = [];
    public $location_id_to_add;
    public $erros_to_add = [];
    public $messages_to_add = [];


    public $filters_is_open = false;

    public $serial_number_to_add;

    public $serial_number;

    public $movement_type_id;

    public $date_from;

    public $date_to;
    public $location_from_id;
    public $location_to_id;


    public function render()
    {

        $locations = Location::orderBy('name', 'ASC')->get();
        $MovementTypes = MovementType::all();
        $serial_number = $this->serial_number;
        $movements = Movement::
        with(['product', 'movementType', 'locationFrom', 'locationTo'])
            ->whereHas('product', function (Builder $query) use ($serial_number) {
                $query->where('serial_number', 'like', '%' . $serial_number . '%');
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
        ]);
    }
    public function resetFiltersForm()
    {
        $this->serial_number = '';
        $this->movement_type_id = '';
        $this->date_from = '';
        $this->date_to = '';
        $this->location_from_id = '';
        $this->location_to_id = '';
    }

    public function addSerialToList() {
        array_push($this->serials, strtoupper($this->serial_number_to_add));
        $this->serials = array_unique($this->serials);
        $this->serial_number_to_add = '';
    }

    public function removeSerialFromList($serial) {
        $this->serials = array_diff($this->serials, array($serial));
    }

    public function store() {
        $mensages = [];
        $erros_to_add = [];
        if (count($this->serials) === 0) {
            $this->show_error_missing_serials = true;
            return;
        }
        foreach ($this->serials as $serial) {
            $product = Product::with('currentLocation')->withSerialNumberStrict($serial)->first();
            if ($product) {
                $movement = new Movement();
                $movement->product_id = $product->id;
                $movement->location_from_id = $product->currentLocation?->id;
                $movement->location_to_id = $this->location_id_to_add ? $this->location_id_to_add : null;
                $movement->save();
                $mensages[] = 'Se movio correctamente el producto '. $serial .'.';
            } else {
                $erros_to_add[] = 'No se pudo encontrar el producto '.$serial.'.';
            }
        }
        if (count($mensages)) {
            $this->sendSuccessMessageToSession(join('<br>' , $mensages));
        }
        if (count($erros_to_add)) {
            $this->sendErrorMessageToSeparateSession(join('<br>' , $erros_to_add));
        }
        $this->resetAddForm();
        $this->resetFiltersForm();
    }
     public function resetAddForm() {
        $this->serials = [];
        $this->show_error_missing_serials = false;
        $this->location_id_to_add = '';
        $this->closeModal('createMovement');
     }

     public function toogleFilters() {
        $this->filters_is_open = !$this->filters_is_open;
     }

}
