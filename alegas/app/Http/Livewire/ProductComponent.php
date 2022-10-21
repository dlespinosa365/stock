<?php

namespace App\Http\Livewire;

use App\Models\Location;
use App\Models\Movement;
use App\Models\MovementType;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Provider;
use Livewire\Component;
use Livewire\WithPagination;

class ProductComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search;
    public $serial_number;
    public $provider_id;
    public $product_type_id;
    public $location_id;

    public function render()
    {
        $products = Product::where('serial_number', 'like', '%' . $this->search . '%')
            ->where('is_out', false)
            ->orderBy('id', 'DESC')
            ->with('location')
            ->paginate(2);
        $productTypes = ProductType::all();
        $providers = Provider::all();
        $locations = Location::where('location_type', Location::$LOCATION_INTERN_ID)->get();

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

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function rules()
    {
        return [
            'serial_number' => 'required|alpha_num',
            'provider_id' => 'required|numeric',
            'product_type_id' => 'required|numeric',
            'location_id' => 'required|numeric'
        ];
    }

    public function resetForm()
    {
        $this->serial_number = '';
        $this->provider_id = '';
        $this->product_type_id = '';
        $this->location_id = '';
    }

    public function store()
    {
        $validateData = $this->validate();
        $product = $this->checkIfProductIsOut();
        if ($product) {
            $product->is_out = false;
            $product->product_type_id = $validateData['product_type_id'];
            $product->update();
            session()->flash('message', 'El producto ' . $this->serial_number . ' ha sido ingresado nuevamente.');
        } else {
            $product = new Product();
            $product->is_out = false;
            $product->product_type_id = $validateData['product_type_id'];
            $product->serial_number = strtoupper($validateData['serial_number']);
            session()->flash('message', 'El producto ha sido creado.');
        }
        $this->createMovementForProductRegistration($product, $validateData['location_id']);
        $this->resetForm();
        $this->dispatchBrowserEvent('close-modal', ['id' => 'createProduct']);

    }

    public function markAsOut(int $product_id) {
        $product = Product::find($product_id);
        $product->is_out = true;
        $product->save();
        session()->flash('message', 'El producto ha sido creado.');
        $this->resetForm();
        $this->dispatchBrowserEvent('close-modal', ['id' => 'markAsOutProduct']);
    }

    public function checkIfProductIsOut()
    {
        $product = Product::where('serial_number', strtoupper($this->serial_number))
            ->where('is_out', true)->first();
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
}
