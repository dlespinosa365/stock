<div>
    @include('livewire.product-forms')

    <div class="container">
        <div class="col-md-12 text-end">
            <input type="search" wire:model="search" class="form-control float-end mx-2" placeholder="Buscar..."
                style="width: 230px" />
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                data-bs-target="#createProduct">Nuevo</button>
        </div>
        @if (session()->has('message'))
            <br>
            <div class="col-md-12">
                <div class="alert alert-{{ session('type') }}" role="alert">
                    {!! session('message') !!}
                </div>
            </div>
        @endif
        <div class="col-md-12">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Serie</th>
                        <th>Tipo</th>
                        <th>Proveedor</th>
                        <th>Ubicacion</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->serial_number }}</td>
                            <td>{{ $product->productType?->name }}</td>
                            <td>{{ $product->provider?->name }}</td>
                            <td>{{ $product->currentLocation?->name }}</td>
                            <td>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#updateProduct"
                                    class="btn btn-outline-primary"
                                    wire:click="edit({{ $product->id }})">Editar</button>

                                <button type="button" data-bs-toggle="modal" data-bs-target="#prepareMoveToCustomer"
                                    class="btn btn-outline-primary"
                                    wire:click="prepareMoveToCustomer({{ $product->id }})">Enviar a cliente</button>

                                <button type="button" data-bs-toggle="modal" data-bs-target="#markAsOutProduct"
                                    class="btn btn-outline-danger" wire:click="prepareMarkAsOut({{ $product->id }})">Dar de
                                    baja</button>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No se encontraron resultados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div>
                {{ $products->links() }}
            </div>
        </div>

    </div>
</div>
