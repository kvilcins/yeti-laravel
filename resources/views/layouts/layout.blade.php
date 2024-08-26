<!DOCTYPE html>
<html lang="ru">
<head>
    @include('components.partials.head')
</head>
<body>

    <header>
        @include('components.partials.header', [
            'is_auth' => $is_auth,
            'user_name' => $user_name,
            'user_avatar' => $user_avatar
        ])
    </header>
    
    <nav>
        @include('components.partials.nav')
    </nav>
    
    <main class="container">
        @yield('content')
    </main>
    
    <footer class="main-footer">
        @include('components.partials.footer')
    </footer>

</body>
</html>
