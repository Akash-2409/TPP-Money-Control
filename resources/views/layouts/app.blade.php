<!DOCTYPE html>
<html>
<head>
    <title>Money Notebook</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">

    <!-- Page Plugin CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/jvectormap/jquery-jvectormap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/flag-icon-css/css/flag-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/owl-carousel-2/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/owl-carousel-2/owl.theme.default.min.css') }}">

    <!-- Layout Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />

</head>

<body>

    <!-- {{-- Sidebar --}}
    @include('components.sidebar')
    
    {{-- Main Content --}}
    <div class="container-fluid page-body-wrapper">
        {{-- Top Navigation --}}
        @include('components.topnav')

        @yield('content')
    </div> -->
    <div class="container-scroller">

    @include('components.sidebar')

    <div class="container-fluid page-body-wrapper">

        @include('components.topnav')

        @yield('content')

    </div>
</div>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('assets/js/chart.js') }}"></script> -->


    <!-- Core JS -->
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>

    <!-- Page Plugin JS -->
    <script src="{{ asset('assets/vendors/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/vendors/progressbar.js/progressbar.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jvectormap/jquery-jvectormap.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('assets/vendors/owl-carousel-2/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.cookie.js') }}" type="text/javascript"></script>

    <!-- Injected JS -->
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/misc.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>

    <!-- Custom Page JS -->
    <script src="{{ asset('assets/js/proBanner.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
@stack('scripts')

</body>
</html>
