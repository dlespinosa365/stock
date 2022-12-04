<div>
    @include('livewire.customer-forms')

    <div class="container">
        <div class="col-md-12 text-end">
            <input type="search" wire:model="search" class="form-control float-end mx-2" placeholder="Buscar..."
                style="width: 230px" />
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                data-bs-target="#createCustomer">Nuevo</button>
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
                        <th>RUT</th>
                        <th>email</th>
                        <th>Razon S.</th>
                        <th>Dir.</th>
                        <th>Telefono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                        <tr>
                            <td>{{ $customer->external_number }}</td>
                            <td>{{ $customer->rut }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->social_reason }}</td>
                            <td>{{ $customer->location->address }}</td>
                            <td>{{ $customer->location->phone }}</td>
                            <td>
                                <button type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#updateCustomer"
                                    class="btn btn-outline-primary"
                                    wire:click="edit({{ $customer->id }})">Editar</button>
                                <button type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteCustomer"
                                    class="btn btn-outline-danger"
                                    wire:click="delete({{ $customer->id }})">Eliminar</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No se encontraron resultados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div>
                {{ $customers->links() }}
            </div>
        </div>

    </div>
</div>
