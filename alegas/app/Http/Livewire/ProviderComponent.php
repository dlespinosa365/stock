<?php

namespace App\Http\Livewire;
use App\Models\Provider;

class ProviderComponent extends CustomMasterComponent
{
    public $name;
    public $description;
    public $phone;
    public $idProvider;
    public $search = '';

    public function render()
    {
        $providers = Provider::where('name', 'like', '%' . $this->search . '%')->orderBy('id', 'DESC')->paginate(10);
        return view('livewire.provider-list', [
               'providers' => $providers
            ])
            ->layout('layouts.app',
                [
                    'header' => 'Listado de proveedores'
                ]
            );
    }

    public function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->phone = '';
        $this->closeModal('deleteProvider');
        $this->closeModal('createProvider');
        $this->closeModal('updateProvider');
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:191',
            'phone' => 'nullable|numeric',
            'description' => 'nullable|string|max:191'
        ];
    }

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function store()
    {
        $validatedData = $this->validate();
        Provider::create($validatedData);
        session()->flash('message', 'Proveedor creado.');
        $this->resetForm();
    }

    public function update()
    {
        $validatedData = $this->validate();
        Provider::where('id', $this->idProvider)->update([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'phone' => $validatedData['phone'],
        ]);
        session()->flash('message', 'Proveedor actualizado.');
        $this->resetForm();
    }

    public function edit(int $id)
    {
        $provider = Provider::find($id);
        if ($provider) {
            $this->idProvider = $provider->id;
            $this->name = $provider->name;
            $this->description = $provider->description;
            $this->phone = $provider->phone;
        }
        else {
            return redirect()->to('/provedores');
        }
    }

    public function delete(int $id)
    {
        $this->idProvider = $id;
    }

    public function remove()
    {
        Provider::find($this->idProvider)->delete();
        session()->flash('message', 'Proveedor eliminado.');
    }
}
