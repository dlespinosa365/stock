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

    <!-- datepicker -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
        integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css"
        integrity="sha512-42kB9yDlYiCEfx2xVwq0q7hT4uf26FUgSIZBK8uiaEnTdShXjwr8Ip1V4xGJMg3mHkUt9nNuTDxunHF0/EgxLQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="font-sans antialiased bg-light">

    @livewire('navigation-menu')

    <!-- Page Heading -->
    <header class="d-flex py-3 bg-white shadow-sm border-bottom">
        <div class="container">
            <h3>{{ $header }}</h3>
        </div>
    </header>

    <!-- Page Content -->
    <main class="container my-5">
        {{ $slot }}
    </main>

    @stack('modals')

    @livewireScripts

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"
        integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"
        integrity="sha512-bUg5gaqBVaXIJNuebamJ6uex//mjxPk8kljQTdM1SwkNrQD7pjS+PerntUSD+QRWPNJ0tq54/x4zRV8bLrLhZg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Custom script -->
    <script>
        window.addEventListener('close-modal', event => {
            $('#' + event.detail.id).modal('hide')
        })
        window.addEventListener('log', event => {
            console.log(event.detail.title, event.detail.message)
        })
        window.addEventListener('collapse-open', event => {
            const bsCollapse = new bootstrap.Collapse(event.detail.id, {
                show: true
            })
        })
        window.addEventListener('collapse-close', event => {
            const bsCollapse = new bootstrap.Collapse(event.detail.id, {
                hide: true
            })
        })
        window.addEventListener('collapse-toggle', event => {
            const bsCollapse = new bootstrap.Collapse(event.detail.id, {
                toggle: true
            })
            console.log('triggering collapse-toggle')
        })
        $('input[datepicker="true"]').datepicker({
            format: 'dd/mm/yyyy',
            language: 'es'
        })

        window.addEventListener('DOMContentLoaded', () => {
            this.livewire.hook('message.sent', () => {
                NProgress.start();
                // window.dispatchEvent(
                //     new CustomEvent('loading', { detail: { loading: true }})
                // );
            } )
            this.livewire.hook('message.processed', (message, component) => {
                NProgress.done();
                // window.dispatchEvent(
                //     new CustomEvent('loading', { detail: { loading: false }})
                // );
            })
        });
    </script>
</body>

</html>
