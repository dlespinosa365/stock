<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MovementType;

class MovemenTypeComponent extends Component
{


    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name;
    public $movementTypeId;
    public $search = '';

    public function render()
    {
        $movementTypes = MovementType::where('name', 'like', '%' . $this->search . '%')->orderBy('id', 'DESC')->paginate(10);
        return view('livewire.movement-type-list', [
               'movementTypes' => $movementTypes
            ])
            ->layout('layouts.app',
                [
                    'header' => 'Listado de tipos de movimiento'
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
        ];
    }
    public function updated($fields)
    {
        $this->validateOnly($fields);
    }
    
    public function store()
    {
        $validatedData = $this->validate();
        MovementType::create($validatedData);
        session()->flash('message', 'Tipo de movimiento creado.');
        $this->resetForm();

        $this->dispatchBrowserEvent('close-modal', ['id' => 'createMovementType']);
    }
    public function update()
    {
        $validatedData = $this->validate();
        MovementType::where('id', $this->movementTypeId)->update([
            'name' => $validatedData['name'],
        ]);
        session()->flash('message', 'Tipo de movimiento actualizado.');
        $this->resetForm();
        $this->dispatchBrowserEvent('close-modal', ['id' => 'updateMovementType']);
    }
    public function edit(int $id)
    {
        $movementType = MovementType::find($id);
        if ($movementType) {
            $this->movementTypeId = $movementType->id;
            $this->name = $movementType->name;
        }
        else {
            return redirect()->to('/tipo-de-movimiento');
        }
    }
    public function delete(int $id)
    {
        $this->movementTypeId = $id;
    }

    public function remove()
    {
        MovementType::find($this->movementTypeId)->delete();
        session()->flash('message', 'Tipo de movimiento eliminado.');
        $this->dispatchBrowserEvent('close-modal', ['id' => 'deleteMovementType']);
    }
}