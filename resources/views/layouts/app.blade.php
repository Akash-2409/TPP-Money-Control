<!DOCTYPE html>
<html>
<head>
    <title>Money Notebook</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: #f2f2f2;
        }
    </style>
</head>

<body>

    {{-- Sidebar --}}
    @include('components.sidebar')

    {{-- Top Navigation --}}
    @include('components.topnav')

    {{-- Main Content --}}
    <div class="container" style="margin-left: 260px; margin-top: 80px;">
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>
</html>
