<x-mail::message>
{{-- Greeting --}}
@lang('Hello!')

@lang('Los notificamos para recordarles que el producto
de :serial_number de tipo :product_type_name debe ser devuelto por mantenimiento', [
    'serial_number' => $productMaintenance->product->serial_number,
    'product_type_name' => $productMaintenance->product->productType->name,
])

{{-- Salutation --}}
@lang('Regards'),<br>
{{ config('app.name') }}

{{-- Subcopy --}}

</x-mail::message>

