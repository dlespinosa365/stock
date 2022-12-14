<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ProductType;
use Livewire\WithPagination; // para paginar



class ProductTypeComponent extends CustomMasterComponent
{
    public $name;
    public $productTypeId;
    public $search = '';

    public function render()
    {
        $productTypes = ProductType::where('name', 'like', '%' . $this->search . '%')->orderBy('id', 'DESC')->paginate(10);
        return view('livewire.product-type-list', [
            'productTypes' => $productTypes
        ])
            ->layout(
                'layouts.app',
                [
                    'header' => 'Listado de tipos de producto'
                ]
            );
    }

    public function resetForm()
    {
        $this->name = '';
        $this->closeModal('deleteProductType');
        $this->closeModal('createProductType');
        $this->closeModal('updateProductType');
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
        ProductType::create($validatedData);
        session()->flash('message', 'Tipo de producto creado.');
        $this->resetForm();

    }

    public function update()
    {
        $validatedData = $this->validate();
        ProductType::where('id', $this->productTypeId)->update([
            'name' => $validatedData['name'],
        ]);
        session()->flash('message', 'Tipo de producto actualizado.');
        $this->resetForm();

    }

    public function edit(int $id)
    {
        $productType = ProductType::find($id);
        if ($productType) {
            $this->productTypeId = $productType->id;
            $this->name = $productType->name;
        } else {
            return redirect()->to('/tipo-de-producto');
        }
    }

    public function delete(int $id)
    {
        $this->productTypeId = $id;
    }

    public function remove()
    {
        ProductType::find($this->productTypeId)->delete();
        session()->flash('message', 'Tipo de producto eliminado.');
        $this->resetForm();
    }
}
