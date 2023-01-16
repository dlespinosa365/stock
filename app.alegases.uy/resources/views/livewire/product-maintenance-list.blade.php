<div>
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12 mb5">
                <h3 class="h3 my-4">Listado de productos por mantenimientos</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Serie</th>
                            <th>Ubicacion</th>
                            <th>Fecha de notificaion</th>
                            <th>Notificacion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($productMaintenances as $productMaintenance)
                            <tr>
                                <td>{{ $productMaintenance->product->serial_number }}</td>
                                <td>{{ $productMaintenance->location->name }}</td>
                                <td>{{ $productMaintenance->trigger_date->diffForHumans() }}</td>
                                <td>
                                    @if ($productMaintenance->is_sended)
                                        <span class="badge text-bg-success">Si</span>
                                    @else
                                        <span class="badge text-bg-secondary">No</span>
                                    @endif
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
                    {{ $productMaintenances->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
