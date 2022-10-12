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
        $movementTypes = MovementType::where('name', 'like', '%' . $this->search . '%')->orderBy('id', 'DESC')->paginate(2);
        return view('livewire.movement-type-list', [
               'productTypes' => $movementTypes
            ])
            ->layout('layouts.app',
                [
                    'header' => 'Listado de tipos de movimiento'
                ]
            );
    }
}
