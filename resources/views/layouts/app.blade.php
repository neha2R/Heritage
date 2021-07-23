<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">


    <!-- Styles -->
    @guest
<!-- Fonts -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">

  <link rel="stylesheet" href="{{ asset('/assets/css/main.css') }}" type="text/css">

    @else
  <!-- Fonts -->
  <link rel="stylesheet" href="{{ asset('/assets/css/main.css') }}" type="text/css">

  @endguest
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" type="text/css">
  <link rel="stylesheet" href="{{ asset('/assets/css/developer.css') }}" type="text/css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.css">

  @yield('css')

</head>
<body class="bg-default">
       @guest

       @else

       <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
       @include('layouts.topnav')
         <div class="app-main">
          @include('layouts.leftnav')
            @endguest
            @yield('content')


          </div>
       </div>

       </div>
       </div>
 <footer class="py-5" id="footer-main">
    <div class="container">
      <!-- <div class="row align-items-center justify-content-xl-between">
        <div class="col-xl-6">
          <div class="copyright text-center text-xl-left text-muted">
            &copy; 2020 <a href="https://www.creative-tim.com" class="font-weight-bold ml-1" target="_blank">Creative Tim</a>
          </div>
        </div>
        <div class="col-xl-6">
          <ul class="nav nav-footer justify-content-center justify-content-xl-end">
            <li class="nav-item">
              <a href="https://www.creative-tim.com" class="nav-link" target="_blank">Creative Tim</a>
            </li>
            <li class="nav-item">
              <a href="https://www.creative-tim.com/presentation" class="nav-link" target="_blank">About Us</a>
            </li>
            <li class="nav-item">
              <a href="http://blog.creative-tim.com" class="nav-link" target="_blank">Blog</a>
            </li>
            <li class="nav-item">
              <a href="https://github.com/creativetimofficial/argon-dashboard/blob/master/LICENSE.md" class="nav-link" target="_blank">MIT License</a>
            </li>
          </ul>
        </div>
      </div> -->
    </div>
  </footer>

      @guest

       @else

       @endguest

       <script src="https://code.jquery.com/jquery-2.2.4.min.js"  crossorigin="anonymous"></script>

       <script type="text/javascript" src="{{ asset('/assets/scripts/main.js') }}"></script>

       <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>


       <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>






       @yield('js')

       @yield('model')

      </body>
</html>
