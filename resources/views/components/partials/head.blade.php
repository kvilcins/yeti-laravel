<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <!-- Подключение Normalize CSS -->
    @vite('resources/css/normalize.min.scss') <!-- Подключение normalize -->
    <!-- Подключение скомпилированных стилей -->
    @vite('resources/css/app.scss') <!-- Подключение app.scss -->
    <!-- Подключение скомпилированных JavaScript файлов -->
    @vite('resources/js/app.js') <!-- Подключение app.js -->
    @isset($lot)
        <meta name="lot-id" content="{{ $lot->id }}">
    @endisset
</head>
