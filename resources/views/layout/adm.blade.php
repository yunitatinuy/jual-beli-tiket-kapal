<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inria+Serif:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Inter:wght@100..900&family=Marko+One&display=swap" rel="stylesheet">
    <livewire:styles />
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <title>Dashboard Admin</title>

</head>

<body class="bg-white text-gray-900">
    <div class="mx-auto max-w-full">
        @include('partials.admnav')

        @include('partials.sidebar')

        @yield('container')

    </div>
</body>

</html>