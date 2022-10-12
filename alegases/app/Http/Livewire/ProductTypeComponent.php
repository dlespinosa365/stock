<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ProductType;

class ProductTypeComponent extends Component
{

    public $productTypes;
    public $name;
    public $productTypeId;
    public $isModalOpen = false;

    public function render()
    {
        $this->productTypes = ProductType::all();
        return view('livewire.product-type-list');
    }

    public function create()
    {
        $this->resetCreateForm();
        $this->openModalPopover();
    }

    public function openModalPopover()
    {
        $this->isModalOpen = true;
    }
    public function closeModalPopover()
    {
        $this->isModalOpen = false;
    }

    private function resetCreateForm(){
        $this->name = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
        ]);

        ProductType::updateOrCreate(['id' => $this->productTypeId], [
            'name' => $this->name,
        ]);
        session()->flash('message', $this->productTypeId ? 'Tipo de producto actualizado.' : 'Tipo de producto creado.');
        $this->closeModalPopover();
        $this->resetCreateForm();
    }

    public function edit($id)
    {
        $student = ProductType::findOrFail($id);
        $this->productTypeId = $id;
        $this->name = $student->name;
        $this->openModalPopover();
    }

    public function delete($id)
    {
        ProductType::find($id)->delete();
        session()->flash('message', 'Tipo de producto eliminado.');
    }
}
