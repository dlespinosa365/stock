
<!-- create Modal -->
<div wire:ignore.self class="modal fade" id="createProvider" tabindex="-1" role="dialog" aria-labelledby="createProviderModelLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createProviderModelLabel">Crear nuevo Proveedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="resetForm">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <form wire:submit.prevent="store">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="name" placeholder="" wire:model="name">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Descripcion</label>
                        <input type="text" class="form-control" id="name" placeholder="" wire:model="description">
                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Telefono</label>
                        <input type="number" class="form-control" id="phone" placeholder="" wire:model="phone">
                        @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
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
<div wire:ignore.self class="modal fade" id="updateProvider" tabindex="-1" role="dialog" aria-labelledby="updateProviderModelLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateProviderModelLabel">Editar Proveedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="resetForm">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <form wire:submit.prevent="update">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="name" placeholder="" wire:model="name">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Descripcion</label>
                        <input type="text" class="form-control" id="name" placeholder="" wire:model="description">
                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Telefono</label>
                        <input type="number" class="form-control" id="phone" placeholder="" wire:model="phone">
                        @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
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
<div wire:ignore.self class="modal fade" id="deleteProvider" tabindex="-1" aria-labelledby="deleteProviderModelLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProviderModelLabel">Eliminar</h5>
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
