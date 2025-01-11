<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('assets/images/logo/favicon.ico')}}">
    <title>{{ current_company()->name }} - @yield('title')</title>

    <!-- CSS -->
    <link href="{{asset('assets/css/koverae.css?'.time())}}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/demo.min.css?'.time())}}" rel="stylesheet"/>
    <!-- CSS -->

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->

    <!-- Leaflet.js CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha384-kr7knlC+7+03I2GzYDBHmxOStG8VIEyq6whWqn2oBoo1ddubZe6UjI+P5bn/f8O5" data-navigate-track/>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha384-kgpA7T5GkjxAeLPMFlGLlQQGqMAwq8ciFnjsbPvZaFUHZvbRYPftvBcRea/Gozbo" data-navigate-track></script>
    <!-- Leaflet.js CSS -->

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/de3e85d402.js" crossorigin="anonymous"></script>
    <!-- Font Awesome -->

    <!-- Libs JS -->
    <script src="{{asset('assets/libs/list.js/dist/list.min.js')}}" data-navigate-track ></script>
    <script src="{{asset('assets/libs/apexcharts/dist/apexcharts.min.js')}}" data-navigate-track ></script>
    <!-- Libs JS -->

    <!-- Scripts -->
    <script src="{{ asset('assets/js/koverae.js?'.time())}}" data-navigate-track></script>
    {{-- <script src="{{ asset('assets/js/demo.min.js?'.time())}}" defer></script> --}}
    <!-- Scripts -->
    @livewireStyles
    @livewireScripts
</head>
<body>
    <script src="{{asset('assets/js/demo-theme.min.js')}}" data-navigate-track></script>
    <main class="page">
        <!-- Navbar -->
        @include('layouts.navigation')
        <!-- Navbar End -->

        <!-- Page Content -->
        @yield('content')
        <!-- Page Content End -->

    </main>

    @livewire('wire-elements-modal')
    <!-- Custom JS -->
    <!-- Custom JS -->

</body>
</html>
