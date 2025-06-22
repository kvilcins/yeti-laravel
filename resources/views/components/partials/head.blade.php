<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title')</title>

    <!-- Include Normalize CSS -->
    @vite('resources/css/normalize.min.scss')
    <!-- Include compiled styles -->
    @vite('resources/css/app.scss') <!-- Include app.scss -->
    <!-- Include compiled JavaScript files -->
    @vite('resources/js/app.js')

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">

    @isset($lot)
        <meta name="lot-id" content="{{ $lot->id }}">
    @endisset

    @if(session('success'))
        <meta name="flash-success" content="{{ session('success') }}">
    @endif

    @if(session('error'))
        <meta name="flash-error" content="{{ session('error') }}">
    @endif
</head>
