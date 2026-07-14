<!DOCTYPE html>
<html>
<head>
    <title>Money Notebook</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Font Awesome & MDI for Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Pure Moden Bootstrap 5 Core -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Our Custom Premium Override -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom-premium.css') }}">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />

</head>

<body>
    
    <div class="container-scroller">
        @include('components.sidebar')

        <div class="page-body-wrapper">
            @include('components.topnav')

            @yield('content')
        </div>
    </div>

    <!-- Pure Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Page Plugin JS -->
    <script src="{{ asset('assets/vendors/chart.js/chart.umd.js') }}"></script>
    
    <!-- Optional jQuery (if old views need it, but keep it minimal) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Custom Page JS (Dashboard Charts) -->
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    
    <!-- SweetAlert2 for Popups -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Access Denied',
                text: '{{ session('error') }}',
                confirmButtonColor: '#0f172a'
            });
            @endif

            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
            @endif
        });
    </script>
@stack('scripts')

</body>
</html>
