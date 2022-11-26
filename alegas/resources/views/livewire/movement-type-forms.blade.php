
<!-- create Modal -->
<div wire:ignore.self class="modal fade" id="createMovementType" tabindex="-1" role="dialog" aria-labelledby="createMovementTypeModelLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createMovementTypeModelLabel">Crear nuevo Tipo de Movimiento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="resetForm">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <form wire:submit.prevent="store">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tipo</label>
                        <input type="text" class="form-control" id="name" placeholder="" wire:model="name">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal" wire:click="resetForm"> Cerrar</button>
                    <button type="submit" class="btn btn-outline-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- update Modal -->
<div wire:ignore.self class="modal fade" id="updateMovementType" tabindex="-1" role="dialog" aria-labelledby="updateMovementTypeLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Tipo de Movimiento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="resetForm">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <form wire:submit.prevent="update">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tipo</label>
                        <input type="text" class="form-control" id="name" placeholder="" wire:model="name">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal" wire:click="resetForm"> Cerrar</button>
                    <button type="submit" class="btn btn-outline-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- delete Modal -->
<div wire:ignore.self class="modal fade" id="deleteMovementType" tabindex="-1" aria-labelledby="deleteMovementTypeLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteMovementTypeLabel">Eliminar</h5>
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
                    <button type="button" class="btn btn-outline-primary" wire:click="resetForm"
                        data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-outline-danger">Si</button>
                </div>
            </form>
        </div>
    </div>
</div>
