<div>
    @include('livewire.location-forms')

    <div class="container">
        <div class="col-md-12 text-end">
            <input type="search" wire:model="search" class="form-control float-end mx-2" placeholder="Buscar..."
                style="width: 230px" />
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                data-bs-target="#createLocation">Nuevo</button>
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
                        <th>id</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Localización</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($locations as $location)
                        <tr>
                            <td>{{ $location->id }}</td>
                            <td>{{ $location->name }}</td>
                            <td>{{ $location->address }}</td>
                            <td>{{ $location->phone }}</td>
                            <td>
                                @if ($location->location_type === \App\Models\Location::$LOCATION_TYPE_INTERN)
                                    Interno
                                @endif
                                @if ($location->location_type === \App\Models\Location::$LOCATION_TYPE_TRUCK)
                                    Camion
                                @endif
                            </td>
                            <td>
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#updateLocation"
                                        class="btn btn-outline-primary"
                                        wire:click="edit({{ $location->id }})">Editar</button>
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#deleteLocation"
                                        class="btn btn-outline-danger"
                                        wire:click="delete({{ $location->id }})">Eliminar</button>
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
                {{ $locations->links() }}
            </div>
        </div>

    </div>
</div>
