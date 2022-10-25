<?php

namespace App\Http\Livewire;

use App\Models\Location;
use App\Models\Movement;
use App\Models\MovementType;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Provider;

class ProductComponent extends CustomMasterComponent
{
    public $search;
    public $serial_number;
    public $provider_id;
    public $product_type_id;
    public $location_id;
    public $product_id;
    public $serials = [];

    public function mount() {
        $this->location_id = Location::$LOCATION_INTERN_ID;
    }

    public function render()
    {
        $products = Product::where('serial_number', 'like', '%' . $this->search . '%')
            ->where('is_out', false)
            ->orderBy('id', 'DESC')
            ->paginate(10);
        $productTypes = ProductType::all();
        $providers = Provider::all();
        $locations = Location::where('location_type', Location::$LOCATION_TYPE_INTERN)->get();

        return view('livewire.product-list', [
            'products' => $products,
            'productTypes' => $productTypes,
            'providers' => $providers,
            'locations' => $locations
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
        $this->location_id = Location::$LOCATION_INTERN_ID;
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
        foreach ($this->serials as $serial) {
            $product = $this->checkIfProductIsOut($serial);
            if ($product && $product->is_out) {
                $product->is_out = false;
                $product->product_type_id = $validateData['product_type_id'];
                $product->provider_id = $validateData['provider_id'];
                $product->update();
                array_push($mensages, 'El producto ' . $serial . ' ha sido ingresado nuevamente.');
            } else if ($product && !$product->is_out) {
                array_push($mensages, 'El producto ' . $serial . ' no se ha podido ingresar porque ya estaba en el sistema.');
            } else {
                $product = new Product();
                $product->is_out = false;
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
        $this->dispatchBrowserEvent('close-modal', ['id' => 'createProduct']);
    }

    public function markAsOut(int $product_id) {
        $product = Product::find($product_id);
        $product->is_out = true;
        $product->save();
        $this->sendSuccessMessageToSession('El producto ha sido creado.');
        $this->resetForm();
        $this->dispatchBrowserEvent('close-modal', ['id' => 'markAsOutProduct']);
    }

    public function checkIfProductIsOut($serial)
    {
        $product = Product::where('serial_number', strtoupper($serial))
            ->first();
        return $product;
    }

    public function createMovementForProductRegistration($product, $location_id)
    {
        $movement = new Movement();
        $movement->product_id = $product->id;
        $movement->movement_type_id = MovementType::$INGRESO;
        $movement->location_to_id = $location_id;
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
        Product::where('id', $this->$this->product_id)->update([
            'serial_number' => $validatedData['serial_number'],
            'product_type_id' => $validatedData['product_type_id'],
            'provider_id' => $validatedData['provider_id'],
        ]);
        $this->sendSuccessMessageToSession('Producto '. $validatedData['serial_number'] . ' actualizado.');
        $this->resetForm();
        $this->dispatchBrowserEvent('close-modal', ['id' => 'updateProduct']);
    }

}
