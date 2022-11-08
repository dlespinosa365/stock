<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Inicio') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">

                  @livewire('stock-component')
                </div>
              </div>
        </div>
    </div>


    <x-jet-welcome />
</x-app-layout>
