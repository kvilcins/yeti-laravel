<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <!-- Подключение Normalize CSS -->
    <link href="{{ secure_asset('css/normalize.min.css') }}" rel="stylesheet">
    <!-- Подключение скомпилированных стилей -->
    <link href="{{ secure_asset('css/app.css') }}" rel="stylesheet">
    <!-- Подключение скомпилированных JavaScript файлов -->
    <script src="{{ secure_asset('js/app.js') }}" defer></script>
    @isset($id)
        <meta name="lot-id" content="{{ $id }}">
    @endisset
</head>
