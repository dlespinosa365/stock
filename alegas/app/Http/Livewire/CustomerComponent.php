<?php

namespace App\Http\Livewire;
use App\Models\Location;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Customer;

class CustomerComponent extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $idCustomer;
    public $social_reason;
    public $rut;
    public $email;
    public $address;
    public $phone;
    public $search = '';


    public function render()
    {
        $customers = Customer::where('social_reason', 'like', '%' . $this->search . '%')->orderBy('id', 'DESC')->with('location')->paginate(2);
        return view('livewire.customer-list', [
               'customers' => $customers
            ])
            ->layout('layouts.app',
                [
                    'header' => 'Listado de Clientes'
                ]
            );
    }

    public function resetForm()
    {
        $this->rut = '';
        $this->email = '';
        $this->social_reason = '';
        $this->address = '';
        $this->phone = '';
    }

    protected function rules()
    {
        return [
            'rut' => 'required|string|max:191',
            'email' => 'required|email',
            'social_reason' => 'nullable|string|max:191',
            'address' => 'required|max:191',
            'phone' => 'numeric'
        ];
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function store()
    {
        $validatedData = $this->validate();
        $location = Location::create([
            'location_type' => Location::$LOCATION_TYPE_CUSTOMER,
            'address' => $validatedData['address'],
            'phone' => $validatedData['phone'],
            'name' => $validatedData['social_reason']
        ]);
        $customer = Customer::create([
            'location_id' => $location->id,
            'rut' => $validatedData['rut'],
            'email' => $validatedData['email'],
            'social_reason' => $validatedData['social_reason'],
        ]);
        $customer->location_id = $location->id;
        $customer->save();
        session()->flash('message', 'Cliente creado.');
        $this->resetForm();
        $this->dispatchBrowserEvent('close-modal', ['id' => 'createCustomer']);
    }

    public function update()
    {
        $validatedData = $this->validate();

        $customer = Customer::find($this->idCustomer);
        $customer->rut = $validatedData['rut'];
        $customer->email = $validatedData['email'];
        $customer->social_reason = $validatedData['social_reason'];

        $location = Location::find( $customer->location_id);
        if ($location) {
            $location->address = $validatedData['address'];
            $location->phone = $validatedData['phone'];
            $location->name = $validatedData['social_reason'];
            $location->save();
        }
        $customer->save();
        session()->flash('message', 'Cliente actualizado.');
        $this->resetForm();
        $this->dispatchBrowserEvent('close-modal', ['id' => 'updateCustomer']);
    }

    public function edit(int $id)
    {
        $customer = Customer::find($id);
        if ($customer) {
            $this->idCustomer = $customer->id;
            $this->rut = $customer->rut;
            $this->email = $customer->email;
            $this->social_reason = $customer->social_reason;
            $this->address = $customer->location->address;
            $this->phone = $customer->location->phone;
        }
        else {
            return redirect()->to('/clientes');
        }
    }

    public function delete(int $id)
    {
        $this->idCustomer = $id;
    }

    public function remove()
    {
        $customer = Customer::find($this->idCustomer);
        Location::find($customer->location_id)->delete();
        Customer::find($this->idCustomer)->delete();
        session()->flash('message', 'Cliente eliminado.');
        $this->dispatchBrowserEvent('close-modal', ['id' => 'deleteCustomer']);
    }
}