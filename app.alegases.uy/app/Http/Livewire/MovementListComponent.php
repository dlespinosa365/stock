<?php

namespace App\Http\Livewire;


use App\Models\Customer;
use App\Models\Location;
use App\Models\Movement;
use App\Models\MovementType;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class MovementListComponent extends CustomMasterComponent
{
    public $show_error_missing_serials = false;
    public $serials = [];
    public $location_id_to_add;
    public $erros_to_add = [];
    public $messages_to_add = [];

    public $description_to_add = '';
    public $date_to_add = '';


    public $filters_is_open;

    public $serial_number_to_add;

    public $serial_number;

    public $movement_type_id;

    public $date_from;

    public $date_to;
    public $location_from_id;
    public $location_to_id;


    public function render()
    {
        $intern_locations = Location::where('location_type', Location::$LOCATION_TYPE_INTERN)
        ->orWhere('location_type', Location::$LOCATION_TYPE_TRUCK)->get();
        $customers = Customer::with('location')->orderBy('external_number')->get();
        $MovementTypes = MovementType::all();
        $serial_number = $this->serial_number;
        $dateFrom = $this->date_from ? (new Carbon($this->date_from))->toDateString() : null;
        $dateTo = $this->date_to ? (new Carbon($this->date_to))->toDateString(): null;
        $movements = Movement::
        with(['product', 'movementType', 'locationFrom', 'locationTo'])
            ->whereHas('product', function (Builder $query) use ($serial_number) {
                $query->where('serial_number', 'like', '%' . $serial_number . '%');
            })
            ->movementType($this->movement_type_id)
            ->dateFrom($dateFrom)
            ->dateTo($dateTo)
            ->fromLocation($this->location_from_id)
            ->toLocation($this->location_to_id)
            ->orderBy('id', 'DESC')

            ->paginate(10);
        $this->log('$this->filters_is_open', $this->filters_is_open);
        return view('livewire.movement-list', [
            'movements' => $movements,
            'customers' => $customers,
            'intern_locations' =>$intern_locations,
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
        $this->filters_is_open = false;
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
                if ($product->is_out && !$this->location_id_to_add) {
                    // verificamos que no se este haciendo una baja de un producto que ya esta de baja
                    $erros_to_add[] = 'No se puede dar de baja el producto '.$serial.' porque ya esta en ese estado.';
                } else {
                    if (!$this->location_id_to_add) {
                        // si es una baja hay que poner el producto en isout true y sin current location
                        $product->is_out = true;
                        $product->current_location_id = null;
                        $product->save();
                    } else {
                        // si no es una baja de productos verificamos que este no este dado de baja ya
                        // asi creamos el movimiento de ingreso automatico
                        $this->addMovementIfProductIsOut($product);
                    }
                    $movement = new Movement();
                    $movement->product_id = $product->id;
                    $movement->location_from_id = $product->currentLocation?->id;
                    $movement->location_to_id = $this->location_id_to_add ? $this->location_id_to_add : null;
                    $movement->description = $this->description_to_add;
                    $movement->created_at = $this->date_to_add ?  Carbon::parse($this->date_to_add)->toDateString() : Carbon::now()->toDateString();
                    $movement->save();
                    $mensages[] = 'Se movio correctamente el producto '. $serial .'.';
                }

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
    public function addMovementIfProductIsOut($product)
    {
        if ($product->is_out) {
            $product->is_out = false;
            $movement = new Movement();
            $movement->product_id = $product->id;
            $movement->description = 'Ingreso automatico';
            $movement->created_at = $this->date_to_add ?  Carbon::parse($this->date_to_add)->toDateString() : Carbon::now()->toDateString();
            $movement->location_to_id = Location::$LOCATION_INTERN_ID;
            $product->save();
            $movement->save();
        }
    }
     public function resetAddForm() {
        $this->serials = [];
        $this->show_error_missing_serials = false;
        $this->location_id_to_add = '';
        $this->date_to_add = '';
        $this->closeModal('createMovement');
     }

     public function toogleFilters() {
        $this->filters_is_open = !$this->filters_is_open;
     }

}
