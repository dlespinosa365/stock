<div>
    <div class="container">
        <div class="col-md-12">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Productos</th>
                        <th>Desde</th>
                        <th>Hasta</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Tipo de movimiento</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($movementLists as $movementList)
                        <tr>
                            <td>{{ $movementList->id }}</td>
                            <td>{{ $movementList->name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No se encontraron resultados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div>
                {{ $movementLists->links() }}
            </div>
        </div>

    </div>
</div>
