<!-- create Modal -->
<div wire:ignore.self class="modal fade" id="createLocation" tabindex="-1" role="dialog"
    aria-labelledby="createLocationLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createLocationLabel">Crear nueva locacion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="resetForm">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <form wire:submit.prevent="store">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="name" placeholder="" wire:model="name">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="address" placeholder="" wire:model="address">
                        @error('address')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="phone" placeholder="" wire:model="phone">
                        @error('phone')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Ubicación</label>
                        <select class="form-select" aria-label="Seleccione una opcion" wire:model="location_type">
                            <option selected>Seleccione una opcion</option>
                            <option value="{{ \App\Models\Location::$LOCATION_TYPE_INTERN }}">Interno</option>
                            <option value="{{ \App\Models\Location::$LOCATION_TYPE_TRUCK }}">Camion</option>
                        </select>
                        @error('location_type')
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

<!-- update Modal -->
<div wire:ignore.self class="modal fade" id="updateLocation" tabindex="-1" role="dialog"
    aria-labelledby="updateLocation" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar locaciones</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="resetForm">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <form wire:submit.prevent="update">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="name" placeholder="" wire:model="name">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="address" placeholder="" wire:model="address">
                        @error('address')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="phone" placeholder="" wire:model="phone">
                        @error('phone')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                    <label for="location_type" class="form-label">Ubicación</label>
                        <select class="form-select" aria-label="Seleccione una opcion" wire:model="location_type">
                            <option selected>Seleccione una opcion</option>
                            <option value="{{ \App\Models\Location::$LOCATION_TYPE_INTERN }}">Interno</option>
                            <option value="{{ \App\Models\Location::$LOCATION_TYPE_TRUCK }}">Camion</option>
                        </select>
                        @error('location_type')
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


<!-- delete Modal -->
<div wire:ignore.self class="modal fade" id="deleteLocation" tabindex="-1" aria-labelledby="deleteLocationLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteLocationLabel">Eliminar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="resetForm"
                    aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="remove">
                <div class="modal-body">
                    <div class="mb-3">
                        <p>Estas seguro que desea eliminar?</p>
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
