<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <title>Taekwondo - Office</title>
    <link rel="icon" href="{{ asset('client/images/favicon2.png') }}" type="image/gif" sizes="16x16">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('client/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('client/assets/css/style.css') }}">
    <!-- main css-->
    <link rel="stylesheet" href="{{ asset('client/css/bootstrap.min.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('client/css/animate.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('client/css/style.css') }}?v={{ time() }}">
    <!-- Main CSS from html/css folder -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('client/slick/slick-theme.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('client/slick/slick.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('client/fonts/fontstyle.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('client/css/all.min.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('client/assets/plugins/tabler-icons/tabler-icons.min.css') }}">
    @stack('css')
  </head>
  <body>
    @include('layout.frontend.partials.header')

    @include('layout.frontend.partials.message')

    <!-- Office Layout with Sidebar -->
    <div class="office-layout">
        <!-- Desktop Sidebar -->
        <aside class="office-sidebar d-none d-lg-block">
            @include('layout.frontend.partials.sidebar')
        </aside>

        <!-- Mobile Sidebar Offcanvas -->
        <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="mobileSidebarLabel">Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                @include('layout.frontend.partials.sidebar')
            </div>
        </div>

        <!-- Main Content -->
        <main class="office-content">
            @yield('content')
        </main>
    </div>

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
    @stack('scripts')
  </body>
</html>
