<div>
    @include('livewire.movement-type-forms')

    <div class="container">
        <div class="col-md-12 text-end">
            <input type="search" wire:model="search" class="form-control float-end mx-2" placeholder="Buscar..."
                style="width: 230px" />
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                data-bs-target="#createMovementType">Nuevo</button>
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
                        <th>Id</th>
                        <th>Tipo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($movementTypes as $movementType)
                        <tr>
                            <td>{{ $movementType->id }}</td>
                            <td>{{ $movementType->name }}</td>
                            <td>
                                @if ($movementType->id > 4)
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#updateMovementType"
                                        class="btn btn-outline-primary"
                                        wire:click="edit({{ $movementType->id }})">Editar</button>
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#deleteMovementType"
                                        class="btn btn-outline-danger"
                                        wire:click="delete({{ $movementType->id }})">Eliminar</button>
                                @endif
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
                {{ $movementTypes->links() }}
            </div>
        </div>

    </div>
</div>
