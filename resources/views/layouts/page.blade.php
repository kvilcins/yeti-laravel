<!DOCTYPE html>
<html lang="en">
<meta name="csrf-token" content="{{ csrf_token() }}">

@include('components.partials.head')

<body>

@include('components.partials.header', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar
])

@include('components.partials.nav')

@yield('content')

@include('components.partials.footer')

@include('modals.notification')
</body>
</html>
