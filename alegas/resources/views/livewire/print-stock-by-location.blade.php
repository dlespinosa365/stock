<div>
        <h5>Stock para ubicacion: {{ $location->name }}</h5>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Producto</th>
                    <th scope="col">Numero de Serie</th>
                    <th scope="col">Ubicacion</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products_to_print as $p)
                    <tr>
                        <td>{{ $p->productType?->name }}</td>
                        <td>{{ $p->serial_number }}</td>
                        <td>{{ $p->currentLocation?->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
