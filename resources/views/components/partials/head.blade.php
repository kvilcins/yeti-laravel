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
    @isset($lot)
        <meta name="lot-id" content="{{ $lot->id }}">
    @endisset
</head>
