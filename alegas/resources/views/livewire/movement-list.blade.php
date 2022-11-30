<div>
    @include('livewire.movement-new-form')
    <div class="container mb-2">
        <div class="row">
            <div class="col-md-12 mb5">
                <h3 class="h3 my-4">Movimientos</h3>
            </div>
            <div class="col-md-3">
                <input type="search" wire:model="serial_number" class="form-control" placeholder="Numero de serie"
                    style="width: 230px" />
            </div>
            <div class="col-md-3">
                <select class="form-select" aria-label="Tipo de movimiento" wire:model="movement_type_id">
                    <option selected>Tipo</option>
                    @foreach ($MovementTypes as $MovementType)
                        <option value="{{ $MovementType->id }}">{{ $MovementType->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 text-end">
                <button type="button" class="btn btn-outline-primary" wire:click="toogleFilters">
                    @if($filters_is_open)
                        Ver menos filtros
                    @else
                        Ver mas filtros
                    @endif
                </button>
                <button type="button" class="btn btn-outline-primary" wire:click="resetFiltersForm">
                    Resetear Filtros
                </button>
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                data-bs-target="#createMovement" aria-expanded="true">
                    Nuevo Movimiento</button>
            </div>
        </div>

    </div>
    @if($filters_is_open)
        <div class="container accordion-collapse pt-4 pb-4 shadow-sm rounded" id="moreFilters">
            <div class="row">
                <div class="col-md-3">
                    <label for="location_from_id" class="col-form-label">Origen</label>
                    <select class="form-select" aria-label="Desde la ubicacion" wire:model="location_from_id"
                        id="location_from_id" name="location_from_id">
                        <option selected>Desde la ubicacion</option>
                        @foreach ($locations as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="location_to_id" class="col-form-label">Destino</label>
                    <select class="form-select" aria-label="Hasta ubicacion" wire:model="location_to_id" id="location_to_id"
                        name="location_to_id">
                        <option selected>Hasta ubicacion</option>
                        @foreach ($locations as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date" class="col-form-label">Desde</label>
                    <input type="date" wire:model="date_from" class="form-control" id="from" name="from">
                </div>
                <div class="col-md-3">
                    <label for="date"class="col-form-label">Hasta</label>
                    <input type="date" wire:model="date_to" class="form-control" id="to" name="to">
                </div>
            </div>
        </div>
    @endif
    <div class="container mt-2">
        @if (session()->has('message'))
            <br>
            <div class="col-md-12">
                <div class="alert alert-success" role="alert">
                    {!! session('message') !!}
                </div>
            </div>
        @endif
        @if (session()->has('error_message'))
            <br>
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    {!! session('error_message') !!}
                </div>
            </div>
        @endif
        <div class="col-md-12">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>Desde</th>
                        <th>Hacia</th>
                        <th>Notas</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($movements as $movement)
                        <tr>
                            <td>{{ $movement->product->serial_number }}</td>
                            <td>{{ $movement->movementType->name }}</td>
                            <td>{{ $movement->created_at->diffForHumans() }}</td>
                            <td>{{ $movement->locationFrom?->name }}</td>
                            <td>{{ $movement->locationTo?->name }}</td>
                            <td>{{ $movement->description }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No se encontraron resultados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div>
                {{ $movements->links() }}
            </div>
        </div>

    </div>
</div>
