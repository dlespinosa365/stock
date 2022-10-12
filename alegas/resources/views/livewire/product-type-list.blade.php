<div>
    @include('livewire.product-type-forms')

    <div class="container">
        <div class="col-md-12 text-end">
            <input type="search" wire:model="search" class="form-control float-end mx-2" placeholder="Buscar..."
                style="width: 230px" />
            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                data-bs-target="#createProductType">Nuevo</button>
        </div>
        @if (session()->has('message'))
            <br>
            <div class="col-md-12">
                <div class="alert alert-success" role="alert">
                    {{ session('message') }}
                </div>
            </div>
        @endif
        <div class="col-md-12">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($productTypes as $productType)
                        <tr>
                            <td>{{ $productType->id }}</td>
                            <td>{{ $productType->name }}</td>
                            <td>
                                <button type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#updateProductType"
                                    class="btn btn-outline-primary"
                                    wire:click="edit({{ $productType->id }})">Editar</button>
                                <button type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteProductType"
                                    class="btn btn-outline-danger"
                                    wire:click="delete({{ $productType->id }})">Eliminar</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No se encontraron resultados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div>
                {{ $productTypes->links() }}
            </div>
        </div>

    </div>
</div>
