<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Taekwondo Đồng Phú')</title>
    <meta name="description" content="@yield('description', 'Taekwondo Đồng Phú')">
    <link rel="icon" href="{{ asset('client/images/favicon.png') }}" type="image/png" sizes="16x16">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('client/css/bootstrap.min.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('client/css/style.css') }}?v={{ filemtime(public_path('client/css/style.css')) }}">
    <link rel="stylesheet" href="{{ asset('client/css/all.min.css') }}">
    @stack('css')
</head>
<body>
    @yield('content')
    
    <!-- Bootstrap JS -->
    <script src="{{ asset('client/js/bootstrap.bundle.min.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('client/js/jquery.min.js') }}?v={{ time() }}"></script>
    @stack('js')
</body>
</html>

