<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <title>Taekwondo</title>
    <link rel="icon" href="{{ asset('client/images/logo.jpg') }}" type="image/jpeg">
    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Oswald:300,400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('client/gymlife/css/bootstrap.min.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('client/gymlife/css/font-awesome.min.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('client/gymlife/css/flaticon.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('client/gymlife/css/owl.carousel.min.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('client/gymlife/css/barfiller.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('client/gymlife/css/magnific-popup.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('client/gymlife/css/slicknav.min.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('client/gymlife/css/style.css') }}?v={{ time() }}">
    <style>
      .header-section .logo img {
        width: 100px;
        height: 100px;
      }

      .footer-section .fa-logo img {
        width: 100px;
        height: 100px;
      }

      @media (max-width: 991px) {
        .header-section .logo img {
          width: 100px;
          height: 100px;
        }

        .footer-section .fa-logo img {
          width: 100px;
          height: 100px;
        }
      }
    </style>
  </head>
  <body>
    <div id="preloder">
      <div class="loader"></div>
    </div>

    @include('layout.frontend.partials.header')

    @include('layout.frontend.partials.message')
    @yield('content')
    @include('layout.frontend.partials.footer')

    <script src="{{ asset('client/gymlife/js/jquery-3.3.1.min.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('client/gymlife/js/bootstrap.min.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('client/gymlife/js/jquery.magnific-popup.min.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('client/gymlife/js/masonry.pkgd.min.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('client/gymlife/js/jquery.barfiller.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('client/gymlife/js/jquery.slicknav.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('client/gymlife/js/owl.carousel.min.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('client/gymlife/js/main.js') }}?v={{ time() }}"></script>
  </body>
</html>
