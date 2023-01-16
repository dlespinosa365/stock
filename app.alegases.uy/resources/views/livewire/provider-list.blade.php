<div>
    @include('livewire.provider-forms')

    <div class="container">
        <div class="col-md-12 text-end">
            <input type="search" wire:model="search" class="form-control float-end mx-2" placeholder="Buscar..."
                style="width: 230px" />
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                data-bs-target="#createProvider">Nuevo</button>
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
                        <th>Description</th>
                        <th>Phone</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($providers as $provider)
                        <tr>
                            <td>{{ $provider->id }}</td>
                            <td>{{ $provider->name }}</td>
                            <td>{{ $provider->description }}</td>
                            <td>{{ $provider->phone }}</td>
                            <td>
                                <button type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#updateProvider"
                                    class="btn btn-outline-primary"
                                    wire:click="edit({{ $provider->id }})">Editar</button>
                                <button type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteProvider"
                                    class="btn btn-outline-danger"
                                    wire:click="delete({{ $provider->id }})">Eliminar</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No se encontraron resultados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div>
                {{ $providers->links() }}
            </div>
        </div>

    </div>
</div>
