<?php

use App\Http\Livewire\ProductComponent;
use App\Http\Livewire\ProductTypeComponent;
use App\Http\Livewire\MovemenTypeComponent;
use App\Http\Livewire\ProviderComponent;
use App\Http\Livewire\CustomerComponent;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\LocationComponent;
use App\Http\Livewire\MovementListComponent;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/inicio', function () {
        return view('inicio');
    })->name('inicio');

    Route::get('/tipo-de-producto', ProductTypeComponent::class)->name('tipo-de-producto');
    Route::get('/tipo-de-movimiento', MovemenTypeComponent::class)->name('tipo-de-movimiento');
    Route::get('/locaciones', LocationComponent::class)->name('locaciones');
    Route::get('/provedores', ProviderComponent::class)->name('provedores');
    Route::get('/clientes', CustomerComponent::class)->name('clientes');
    Route::get('/productos', ProductComponent::class)->name('productos');
    Route::get('/lista-movimiento', MovementListComponent::class)->name('lista-movimiento');

});
