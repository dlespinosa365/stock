<?php

namespace App\Http\Livewire;
use App\Models\Location;

class LocationComponent extends CustomMasterComponent
{
    public $search = '';
    public $name;
    public $address;
    public $phone;
    public $location_type;
    public $locationTypeId;



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
        $this->closeModal('createLocation');
        $this->closeModal('updateLocation');
        $this->closeModal('deleteLocation');

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
    }
    public function update()
    {
        $validatedData = $this->validate();
        Location::where('id', $this->locationTypeId)->update([
            'name' => $validatedData['name'],
            'address' => $validatedData['address'],
            'phone' => $validatedData['phone'],
            'location_type' => $validatedData['location_type'],
        ]);
        session()->flash('message', 'Localización actualizada.');
        $this->resetForm();
    }
    public function edit(int $id)
    {
        $location = Location::find($id);
        if ($location) {
            $this->locationTypeId = $location->id;
            $this->name = $location->name;
            $this->address = $location->address;
            $this->phone = $location->phone;
        }
        else {
            return redirect()->to('/locaciones');
        }
    }
    public function delete(int $id)
    {
        $this->locationTypeId = $id;
    }

    public function remove()
    {
        Location::find($this->locationTypeId)->delete();
        session()->flash('message', 'Localización eliminada.');
        $this->closeModal('deleteLocation');
    }
}
