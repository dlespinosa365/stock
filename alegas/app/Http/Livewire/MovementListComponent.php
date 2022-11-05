<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\MovementList;
use Livewire\WithPagination; // para paginar

class MovementListComponent extends Component
{
    public function render()
    {
        $movementLists = MovementList::where('name', 'like', '%' . $this->search . '%')->orderBy('id', 'DESC')->paginate(10);
        return view('livewire.movement-list', [
               'movementLists' => $movementLists
            ])
            ->layout('layouts.app',
                [
                    'header' => 'Listado de movimientos'
                ]
            );
    }
}
