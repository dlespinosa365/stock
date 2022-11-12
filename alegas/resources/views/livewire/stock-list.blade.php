<div>
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12 mb5">
                <h5 class="card-title">Stock de productos</h5>
            </div>
            <div class="col-md-6">
                <select class="form-select" aria-label="Ubicacion" wire:model="locationId">
                    <option value="" selected>Seleccione una ubicacion</option>
                    @foreach ($locations as $location)
                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <input type="search" wire:model="serialNumber" class="form-control" placeholder="Buscar..." />
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Serial</th>
                            <th>Ubicacion</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>{{ $product->serial_number }}</td>
                                <td>{{ $product->currentLocation?->name }}</td>
                                <td>
                                    <button type="button" data-bs-toggle="modal"
                                        data-bs-target="#showThreeLastMovement" class="btn btn-outline-primary"
                                        wire:click="showThreeLastMovementFn({{ $product->id }})">
                                        Ultimos movimientos
                                    </button>
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
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="showThreeLastMovement" tabindex="-1" role="dialog"
        aria-labelledby="showThreeLastMovementLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showThreeLastMovementLabel">Ultimos Movimientos del producto
                        {{ $productToFind?->serial_number }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Origen</th>
                                <th>Destino</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse ($lastMovements as $movement)
                                <tr>
                                    <td>{{ $movement->created_at }}</td>
                                    <td>{{ $movement->locationFrom?->name }}</td>
                                    <td>{{ $movement->locationTo?->name }}</td>
                                    <td>{{ $movement->movementType?->name }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">No se encontraron resultados</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal"> Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
