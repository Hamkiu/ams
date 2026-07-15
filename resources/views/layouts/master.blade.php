<!doctype html>
<html lang="en" data-bs-theme="blue-theme">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>DBSurat@mbi | @yield('title')</title>
  <!--favicon-->
  <link rel="icon" sizes="16x16" href="{{ asset('template/assets/images/logo-mbi.png') }}" type="image/png">
  <!-- loader-->
	<link href="{{ asset('template/assets/css/pace.min.css') }}" rel="stylesheet">
	<script src="{{ asset('template/assets/js/pace.min.js') }}"></script>

  <!--plugins-->
  <link href="{{ asset('template/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{{ asset('template/assets/plugins/metismenu/metisMenu.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('template/assets/plugins/metismenu/mm-vertical.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('template/assets/plugins/simplebar/css/simplebar.css') }}">
  <!--bootstrap css-->
  <link href="{{ asset('template/assets/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
  <!--main css-->
  <link href="{{ asset('template/assets/css/bootstrap-extended.css') }}" rel="stylesheet">
  <link href="{{ asset('template/sass/main.css') }}" rel="stylesheet">
  <link href="{{ asset('template/sass/dark-theme.css') }}" rel="stylesheet">
  <link href="{{ asset('template/sass/blue-theme.css') }}" rel="stylesheet">
  <link href="{{ asset('template/sass/semi-dark.css') }}" rel="stylesheet">
  <link href="{{ asset('template/sass/bordered-theme.css') }}" rel="stylesheet">
  <link href="{{ asset('template/sass/responsive.css') }}" rel="stylesheet">

</head>

<body>

  <!--start header-->
    @include('layouts.header')
  <!--end top header-->


    <!--start sidebar-->
    @include('layouts.sidebar')
    <!--end sidebar-->

  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
        <!--breadcrumb-->
        @include('layouts.breadcrumb')
	    <!--end breadcrumb-->
     
            @yield('content')



    </div>
  </main>
  <!--end main wrapper-->

  <!--start overlay-->
     <div class="overlay btn-toggle"></div>
  <!--end overlay-->

   <!--start footer-->
   <footer class="page-footer">
    <p class="mb-0">Copyright © 2025. All right reserved.</p>
  </footer>
  <!--end footer-->


  <!--start switcher-->
    @include('layouts.theme')
  <!--end switcher-->

  <!--bootstrap js-->
  <script src="{{ asset('template/assets/js/bootstrap.bundle.min.js') }}"></script>

  <!--plugins-->
  <script src="{{ asset('template/assets/js/jquery.min.js') }}"></script>
  <!--plugins-->
  <script src="{{ asset('template/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
  <script src="{{ asset('template/assets/plugins/metismenu/metisMenu.min.js') }}"></script>
  <script src="{{ asset('template/assets/plugins/apexchart/apexcharts.min.js') }}"></script>
  <script src="{{ asset('template/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
  <script src="{{ asset('template/assets/plugins/peity/jquery.peity.min.js') }}"></script>
  <script>
    $(".data-attributes span").peity("donut")
  </script>
  <script src="{{ asset('template/assets/js/main.js') }}"></script>
  <script src="{{ asset('template/assets/js/dashboard1.js') }}"></script>
  <script src="https://unpkg.com/feather-icons"></script>
  <script>
      feather.replace()
  </script>
  <script>
	   new PerfectScrollbar(".user-list")
  </script>

</body>

</html>