<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/alegas-36x36.png') }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    @livewireStyles
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        @page {
            margin: 0cm;
        }
    </style>
</head>

<body class="font-sans antialiased bg-light">
    <header>
        <div class="text-center mt-3">
            <h5 class="">{{ $header }}</h5>
        </div>
    </header>
    <!-- Page Content -->
    <main class="container">
        {{ $slot }}
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            window.print();
        });
        window.onafterprint = function() {
            window.location = window.location.origin + '/inicio'
        }
    </script>
</body>

</html>
