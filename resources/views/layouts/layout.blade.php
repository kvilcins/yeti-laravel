<!DOCTYPE html>
<html lang="ru">

@include('components.partials.head')

<body>

    @include('components.partials.header', [
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'user_avatar' => $user_avatar
    ])

    @yield('content')
    
    @include('components.partials.footer')

</body>
</html>
