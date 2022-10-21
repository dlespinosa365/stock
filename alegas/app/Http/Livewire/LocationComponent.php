<?php

namespace App\Http\Livewire;
use App\Models\Location;

use Livewire\Component;
use Livewire\WithPagination;

class LocationComponent extends Component
{
    use WithPagination;
    public $search = '';
    public $name;
    public $address;
    public $phone;
    public $location_type;


    public function render()
    {
        $locations = Location::where('name', 'like', '%' . $this->search . '%')
                              ->whereIn('location_type', [ Location::$LOCATION_TYPE_TRUCK, Location::$LOCATION_TYPE_INTERN])
                              ->orderBy('id', 'DESC')
                              ->paginate(10);
        return view('livewire.location-list', [
               'locations' => $locations
            ])
            ->layout('layouts.app',
                [
                    'header' => 'Listado de locaciones'
                ]
            );
    }
    public function resetForm()
    {
        $this->name = '';
        $this->address = '';
        $this->phone = '';
        $this->location_type = '';
    }
    protected function rules()
    {
        return [
            'name' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|integer',
            'location_type' => 'required|integer',
        ];
    }
    public function updated($fields)
    {
        $this->validateOnly($fields);
    }
    public function store()
    {
        $validatedData = $this->validate();
        $location = new Location();
        $location->name = $validatedData['name'];
        $location->address = $validatedData['address'];
        $location->phone = $validatedData['phone'];
        $location->location_type = $validatedData['location_type'];
        $location->save();
        session()->flash('message', 'Unicacion creada.');
        $this->resetForm();
        $this->dispatchBrowserEvent('close-modal', ['id' => 'createLocation']);
    }
}
