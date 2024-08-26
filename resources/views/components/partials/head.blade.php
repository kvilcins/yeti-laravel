<meta charset="UTF-8">
<title>@yield('title')</title>
<link href="{{ asset('css/normalize.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
<script src="{{ asset('js/form-validation.js') }}"></script>
<script src="{{ asset('js/viewed_lots.js') }}"></script>

@isset($id)
    <meta name="lot-id" content="{{ $id }}">
@endisset
