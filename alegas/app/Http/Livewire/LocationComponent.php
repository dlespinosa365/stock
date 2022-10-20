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
    public $adress;
    public $phone;
    public $LocationType;


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
    }
    protected function rules()
    {
        return [
            'name' => 'required|string',
            'adress' => 'required|string',
            'phone' => 'required|integer',
        ];
    }
    public function updated($fields)
    {
        $this->validateOnly($fields);
    }
    public function store()
    {
        $validatedData = $this->validate();
        Location::create($validatedData);
        session()->flash('message', 'Locacion creada.');
        $this->resetForm();
        $this->dispatchBrowserEvent('close-modal', ['id' => 'createLocationType']);
    }
}
