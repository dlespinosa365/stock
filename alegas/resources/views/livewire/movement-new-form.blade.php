<div wire:ignore.self class="modal fade" id="createMovement" tabindex="-1" role="dialog"
    aria-labelledby="createMovementLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createMovementLabel">Nuevo Movimiento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="resetAddForm">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <form wire:submit.prevent="store" onkeydown="return event.key != 'Enter';">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="serial_number" class="form-label">Numero(s) de Serie</label>
                        <input type="text" class="form-control" id="serial_number_to_add" placeholder=""
                            wire:model="serial_number_to_add" wire:keydown.enter="addSerialToList">
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
                        <label for="location_id_to_add" class="form-label">Nueva Ubicacion</label>
                        <select class="form-select" aria-label="Unicacion" wire:model="location_id_to_add">
                            <option value="">BAJA</option>
                            @foreach ($intern_locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->location->id }}">[{{ $customer->external_number }}] - {{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="date_to_add" class="form-label">Fecha</label>
                        <input type="date" wire:model="date_to_add" class="form-control" id="date_to_add" name="date_to_add">
                    </div>
                    <div class="mb-3">
                        <label for="description_to_add" class="form-label">Descripcion</label>
                        <textarea class="form-control" id="description_to_add" rows="3" wire:model="description_to_add"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"
                        wire:click="resetAddForm"> Cancelar</button>
                    <button type="submit" class="btn btn-outline-success">Crear</button>
                </div>
            </form>
        </div>
    </div>
</div>
