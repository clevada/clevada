<?php header('Access-Control-Allow-Origin: *'); ?>

<!doctype html>
<html lang="{{ $locale }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ Auth::user()->name }} - Admin area</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    @if ($config->favicon)<link rel="shortcut icon" href="{{ image($config->favicon) }}" type="image/x-icon">@endif
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    {{-- color picker --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/spectrum/spectrum.css') }}">
    <script src="{{ asset('assets/vendors/spectrum/spectrum.js') }}"></script>
</head>

<body>
    <div id="app">

        @include('admin.sidebar')

        <div id="main" class='layout-navbar'>

            @include('admin.navigation')

            <div id="main-content">

                <div class="page-heading">

                    @include("admin.{$view_file}")

                </div>

                @include('admin.footer')

            </div>

        </div>

    </div>

    <script src="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
</body>

</html>
