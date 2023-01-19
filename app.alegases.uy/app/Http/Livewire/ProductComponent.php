<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use App\Models\Location;
use App\Models\Movement;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Provider;
use Illuminate\Support\Carbon;

class ProductComponent extends CustomMasterComponent
{
    public $search;
    public $serial_number;
    public $provider_id;
    public $product_type_id;
    public $location_id;
    public $location_for_movement_id;
    public $product_id;
    public $serials = [];
    public $date_to_add = '';
    public $date_to_delete = '';

    public $show_error_missing_serials;

    public function render()
    {
        $products = Product::where('serial_number', 'like', '%' . $this->search . '%')
            ->where('is_out', false)
            ->orderBy('id', 'DESC')
            ->paginate(10);
        $productTypes = ProductType::orderBy('name')->get();
        $providers = Provider::orderBy('name')->get();;
        $locations = Location::where('location_type', Location::$LOCATION_TYPE_INTERN)->orderBy('name')->get();
        $locations_customer = Customer::with('location')->orderBy('external_number')->get();
        if (!$this->location_id) {
            $this->location_id = $locations->get(0)->id;
        }

        return view('livewire.product-list', [
            'products' => $products,
            'productTypes' => $productTypes,
            'providers' => $providers,
            'locations' => $locations,
            'locations_customer' => $locations_customer
        ])
            ->layout('layouts.app',
                [
                    'header' => 'Listado de Productos'
                ]
            );
    }


    public function rules()
    {
        return [
            'provider_id' => 'required|numeric',
            'product_type_id' => 'required|numeric',
            'location_id' => 'required|numeric'
        ];
    }

    public function resetForm()
    {
        $this->serial_number = '';
        $this->serials = [];
        $this->provider_id = '';
        $this->product_type_id = '';
        $this->date_to_add = '';
        $this->date_to_delete = '';
        $this->location_id = Location::$LOCATION_INTERN_ID;
        $this->show_error_missing_serials = false;
        $this->closeModal('createProduct');
        $this->closeModal('markAsOutProduct');
        $this->closeModal('updateProduct');
        $this->closeModal('prepareMoveToCustomer');
    }

    public function addSerialToList() {
        array_push($this->serials, strtoupper($this->serial_number));
        $this->serials = array_unique($this->serials);
        $this->serial_number = '';
    }

    public function removeSerialFromList($serial) {
        $this->serials = array_diff($this->serials, array($serial));
    }

    public function store()
    {   //check data validation on store
        $validateData = $this->validate();
        $mensages = [];
        if (count($this->serials) === 0) {
            $this->show_error_missing_serials = true;
            return;
        }
        foreach ($this->serials as $serial) {
            $product = $this->getProdutBySerialNumber($serial);
            if ($product && $product->is_out) {
                $product->product_type_id = $validateData['product_type_id'];
                $product->provider_id = $validateData['provider_id'];
                $product->created_at =$this->date_to_add ?  Carbon::parse($this->date_to_add)->toDateString() : Carbon::now()->toDateString();
                $product->save();
                array_push($mensages, 'El producto ' . $serial . ' ha sido ingresado nuevamente.');
            } else if ($product && !$product->is_out) {
                $this->createMovementDown($product, $this->date_to_add);
                array_push($mensages, 'El producto ' . $serial . ' ha sido dado de baja e ingrrsado nuevamente.');
            } else {
                $product = new Product();
                $product->created_at = $this->date_to_add ?  Carbon::parse($this->date_to_add)->toDateString() : Carbon::now()->toDateString();
                $product->product_type_id = $validateData['product_type_id'];
                $product->serial_number = strtoupper($serial);
                $product->provider_id = $validateData['provider_id'];
                $product->save();
                array_push($mensages, 'El producto '  . $serial . ' ha sido creado.');
            }
            $this->createMovementForProductRegistration($product, $validateData['location_id']);
        }
        $this->sendSuccessMessageToSession(join('<br>' , $mensages));
        $this->resetForm();

    }

    public function createMovementDown($product, $date_to_delete)
    {
        $movement = new Movement();
        $movement->product_id = $product->id;
        $movement->location_from_id = $product->current_location_id || Location::$LOCATION_INTERN_ID;;
        $movement->created_at = $date_to_delete ?  Carbon::parse($date_to_delete)->toDateString() : Carbon::now()->toDateString();
        $movement->save();
    }

    public function markAsOut() {
        $product = Product::find($this->product_id);
        $this->createMovementDown($product, $this->date_to_delete);
        $this->sendSuccessMessageToSession('El producto ha sido dado de baja.');
        $this->resetForm();

    }

    public function prepareMarkAsOut(int $product_id) {
        $this->product_id = $product_id;
    }

    public function getProdutBySerialNumber($serial)
    {
        $product = Product::where('serial_number', strtoupper($serial))
            ->first();
        return $product;
    }

    public function createMovementForProductRegistration($product, $location_id)
    {
        $movement = new Movement();
        $movement->product_id = $product->id;
        $movement->location_to_id = $location_id;
        $movement->created_at = $this->date_to_add ?  Carbon::parse($this->date_to_add)->toDateString() : Carbon::now()->toDateString();
        $movement->save();
    }

    public function edit(int $id) {
        $product = Product::find($id);
        if ($product) {
            $this->product_id = $product->id;
            $this->serial_number = $product->serial_number;
            $this->product_type_id = $product->product_type_id;
            $this->provider_id = $product->provider_id;
        }
        else {
            $this->sendErrorMessageToSession('Producto no encontrado.');
            return redirect()->to('/productos');
        }
    }

    public function update() {
        $validatedData = $this->validate();
        Product::where('id', $this->product_id)->update([
            'serial_number' => $this->serial_number,
            'product_type_id' => $validatedData['product_type_id'],
            'provider_id' => $validatedData['provider_id'],
        ]);
        $this->sendSuccessMessageToSession('Producto '. $this->serial_number . ' actualizado.');
        $this->resetForm();

    }


    public function prepareMoveToCustomer(int $product_id) {
        $this->product_id = $product_id;
    }

    public function moveToCustomer() {
        $product = Product::find($this->product_id);
        $this->createMovementForOtherLocation($product);
        $this->sendSuccessMessageToSession('Producto movido correctamente');
        $this->resetForm();

    }

    public function createMovementForOtherLocation($product)
    {
        $locations_customer = Location::where('location_type', Location::$LOCATION_TYPE_CUSTOMER)->orderBy('name')->get();
        if (!$this->location_for_movement_id) {
            $this->location_for_movement_id = $locations_customer->get(0)->id;
        }
        $movement = new Movement();
        $movement->product_id = $product->id;
        $movement->location_from_id = $product->currentLocation?->id;
        $movement->location_to_id = $this->location_for_movement_id;
        $movement->created_at = $this->date_to_add ?  Carbon::parse($this->date_to_add)->toDateString() : Carbon::now()->toDateString();
        $movement->save();
    }

}
