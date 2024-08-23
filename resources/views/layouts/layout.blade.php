<!DOCTYPE html>
<html lang="ru">
<head>
    @if (!empty($head))
        {!! $head !!}
    @endif
</head>
<body>
@if (!empty($header))
    <header>
        {!! $header !!}
    </header>
@endif

@if (!empty($nav))
    <nav>
        {!! $nav !!}
    </nav>
@endif

@if (!empty($content))
    <main class="container">
        {!! $content !!}
    </main>
@endif

@if (!empty($footer))
    <footer class="main-footer">
        {!! $footer !!}
    </footer>
@endif
</body>
</html>
