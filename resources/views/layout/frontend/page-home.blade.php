<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <title>Taekwondo</title>
    <link rel="icon" href="{{ asset('client/images/favicon2.png') }}" type="image/gif" sizes="16x16">
    <!-- main css-->
    <link rel="stylesheet" href="{{ asset('client/css/bootstrap.min.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('client/css/animate.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('client/css/style.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('client/slick/slick-theme.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('client/slick/slick.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('client/fonts/fontstyle.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('client/css/all.min.css') }}?v={{ time() }}">
  </head>
  <body>
    @include('layout.frontend.partials.header')

    @include('layout.frontend.partials.message')
    @yield('content')
    @include('layout.frontend.partials.footer')

    <!-- main js-->
    <script src="{{ asset('client/js/all.min.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('client/js/jquery.min.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('client/js/bootstrap.bundle.min.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('client/js/wow.min.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('client/slick/slick.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('client/js/main.js') }}?v={{ time() }}"></script>
    <script>
      function readPath(input) {
          if (input.files && input.files[0]) {
              var reader = new FileReader();
              reader.onload = function (e) {
                  $('.img-bill').attr('src', e.target.result);
              }
              reader.readAsDataURL(input.files[0]);
          }
      }
      $("#imgInp").change(function(){
          readPath(this);
      });
    </script>
  </body>
</html>
