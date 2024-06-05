<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Marko+One&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inria+Serif:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Marko+One&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <livewire:styles />
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <title>Dashboard Admin</title>

</head>

<body>
    <div class="mx-auto max-w-full">
        @include('partials.admnav')

        @include('partials.sidebar')

        @yield('container')

        @livewire('kapal')

    </div>
    <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
    @push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.hook('message.sent', (message, component) => {
                // Scroll to the edited row
                let rowId = message.updates.idkapal; // Adjust this according to your Livewire component structure
                let row = document.querySelector(`tr[data-idkapal="${rowId}"]`);
                if (row) {
                    row.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center',
                        inline: 'nearest',
                    });
                }
            });
        });
    </script>
    @endpush
    
    @livewireScripts
</body>

</html>