<!-- create Modal -->
<div wire:ignore.self class="modal fade" id="createProduct" tabindex="-1" role="dialog"
    aria-labelledby="createProductModelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createProductModelLabel">Nuevo Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="resetForm">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <form wire:submit.prevent="store" onkeydown="return event.key != 'Enter';">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="serial_number" class="form-label">Numero(s) de Serie</label>
                        <input type="text" class="form-control" id="serial_number" placeholder=""
                            wire:model="serial_number" wire:keydown.enter="addSerialToList">
                        @error('serial_number')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        @if($show_error_missing_serials)
                            <span class="text-danger">No se ha agregado nigun numero de serie</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        @foreach ($serials as $serial)
                            <strong>
                                <span class="badge bg-secondary"
                                    wire:click="removeSerialFromList( {{ $serial }} )">{{ $serial }}</span>
                            </strong>
                        @endforeach
                    </div>
                    <div class="mb-3">
                        <label for="product_type_id" class="form-label">Tipo</label>
                        <select class="form-select" aria-label="Tipo de producto" wire:model="product_type_id">
                            <option value="">Seleccione un Tipo de Producto</option>
                            @foreach ($productTypes as $productType)
                                <option value="{{ $productType->id }}">{{ $productType->name }}</option>
                            @endforeach
                        </select>
                        @error('product_type_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="provider_id" class="form-label">Proveedor</label>
                        <select class="form-select" aria-label="Proveedor" wire:model="provider_id">
                            <option value="">Seleccione un Proveedor</option>
                            @foreach ($providers as $provider)
                                <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                            @endforeach
                        </select>
                        @error('provider_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="location_id" class="form-label">Ubicacion</label>
                        <select class="form-select" aria-label="Unicacion" wire:model="location_id">
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                        @error('location_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal"
                        wire:click="resetForm"> Cerrar</button>
                    <button type="submit" class="btn btn-sucess close-modal">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- edit Modal -->
<div wire:ignore.self class="modal fade" id="updateProduct" tabindex="-1" role="dialog"
    aria-labelledby="updateProductModelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateProductModelLabel">Editar Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="resetForm">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <form wire:submit.prevent="update">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="serial_number" class="form-label">Numero(s) de Serie</label>
                        <input type="text" class="form-control" id="serial_number" placeholder=""
                            wire:model="serial_number">
                        @error('serial_number')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="product_type_id" class="form-label">Tipo</label>
                        <select class="form-select" aria-label="Tipo de producto" wire:model="product_type_id">
                            <option value="">Seleccione un Tipo de Producto</option>
                            @foreach ($productTypes as $productType)
                                <option value="{{ $productType->id }}">{{ $productType->name }}</option>
                            @endforeach
                        </select>
                        @error('product_type_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="provider_id" class="form-label">Proveedor</label>
                        <select class="form-select" aria-label="Proveedor" wire:model="provider_id">
                            <option value="">Seleccione un Proveedor</option>
                            @foreach ($providers as $provider)
                                <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                            @endforeach
                        </select>
                        @error('provider_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal"
                        wire:click="resetForm"> Cerrar</button>
                    <button type="submit" class="btn btn-sucess close-modal">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- markAsOut Modal -->
<div wire:ignore.self class="modal fade" id="markAsOutProduct" tabindex="-1" aria-labelledby="markAsOutProductLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="markAsOutProductLabel">Eliminar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="resetForm"
                    aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="markAsOut">
                <div class="modal-body">
                    <div class="mb-3">
                        <p>Estas seguro que desea dar de baja el producto?</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="resetForm"
                        data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Si</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div wire:ignore.self class="modal fade" id="prepareMoveToCustomer" tabindex="-1" aria-labelledby="prepareMoveToCustomerLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="prepareMoveToCustomerLabel">Movimiento del producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="resetForm"
                    aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="moveToCustomer">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="product_type_id" class="form-label">Nueva ubicacion</label>
                        <select class="form-select" aria-label="Unicacion" wire:model="location_for_movement_id">
                            @foreach ($locations_customer as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="resetForm"
                        data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Si</button>
                </div>
            </form>
        </div>
    </div>
</div>
