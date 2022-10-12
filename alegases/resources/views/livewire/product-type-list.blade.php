<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
            @if (session()->has('message'))
                <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3"
                    role="alert">
                    <div class="flex">
                        <div>
                            <p class="text-sm">{{ session('message') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            <div class="relative w-full px-4 max-w-full flex-grow flex-1 text-right">
                <button wire:click="create()" class="bg-indigo-500 text-white active:bg-indigo-600 text-xs font-bold uppercase px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button">See all</button>
            </div>
            <br>
            @if ($isModalOpen)
                @include('livewire.product-type-create')
            @endif
            <table class="table-fixed w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 w-20">No.</th>
                        <th class="px-4 py-2">Nombre</th>
                        <th class="px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productTypes as $productType)
                        <tr>
                            <td class="border px-4 py-2">{{ $productType->id }}</td>
                            <td class="border px-4 py-2">{{ $productType->name }}</td>
                            <td class="border px-4 py-2">
                                <button wire:click="edit({{ $productType->id }})"
                                    class="flex px-4 py-2 bg-gray-500 text-gray-900 cursor-pointer">Editar</button>
                                <button wire:click="delete({{ $productType->id }})"
                                    class="flex px-4 py-2 bg-red-100 text-gray-900 cursor-pointer">Eliminar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
