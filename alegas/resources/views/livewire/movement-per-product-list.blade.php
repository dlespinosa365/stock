<div>
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12 mb5">
                <h3 class="h3 my-4">Historico de movimientos por productos</h3>
            </div>
            <div class="col-md-6">
                <input type="search" wire:model="serialNumber" class="form-control" placeholder="Numero de serie" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Serie</th>
                            <th>Desde</th>
                            <th>Hasta</th>
                            <th>Tipo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($movements as $movement)
                            <tr>
                                <td>{{ $movement->created_at->diffForHumans() }}</td>
                                <td>{{ $movement->product->serial_number }}</td>
                                <td>{{ $movement->locationTo?->name }}</td>
                                <td>{{ $movement->locationFrom?->name }}</td>
                                <td>{{ $movement->movementType?->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No se encontraron resultados</td>
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
</div>
