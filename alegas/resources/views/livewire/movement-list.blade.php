<div>
    <div class="card">
        <div class="card-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <input type="search" wire:model="serial_number" class="form-control mx-2"
                            placeholder="Numero de serie" style="width: 230px" />
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" aria-label="Hacia la ubicacion" wire:model="location_from_id">
                            <option selected>Hacia la ubicacion</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" aria-label="Desde la ubicacion" wire:model="location_to_id">
                            <option selected>Desde la ubicacion</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" aria-label="Tipo de movimiento" wire:model="movement_type_id">
                            <option selected>Tipo</option>
                            @foreach ($MovementTypes as $MovementType)
                                <option value="{{ $MovementType->id }}">{{ $MovementType->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <br>

    <div class="card">
        <div class="card-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <label for="date" class="col-form-label col-sm-2">Desde: </label>
                        <input type="date" wire:model="date_from" class="form-control" id="from" name="from">
                    </div>
                    <div class="col-md-3">
                        <label for="date"class="col-form-label col-sm-2">Hasta: </label>
                        <input type="date" wire:model="date_to" class="form-control" id="to" name="to">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="container">
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
                        <th>Producto</th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>Desde</th>
                        <th>Hacia</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($movements as $movement)
                        <tr>
                            <td>{{ $movement->id }}</td>
                            <td>{{ $movement->product->serial_number }}</td>
                            <td>{{ $movement->movementType->name }}</td>
                            <td>{{ $movement->created_at->diffForHumans() }}</td>
                            <td>{{ $movement->locationFrom?->name }}</td>
                            <td>{{ $movement->locationTo?->name }}</td>
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
