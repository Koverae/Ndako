<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('assets/images/logo/favicon.ico')}}">
    <title>{{ current_company()->name }} - @yield('title')</title>

    <!-- CSS -->
    <link href="{{asset('assets/css/koverae.css?'.time())}}" rel="stylesheet"/>
    <link href="{{asset('assets/css/koverae-flags.min.css?'.time())}}" rel="stylesheet"/>
    {{-- <link href="{{ asset('assets/css/demo.min.css?'.time())}}" rel="stylesheet"/> --}}
    <!-- CSS -->

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/de3e85d402.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!-- Font Awesome -->

    <!-- Libs JS -->
    <script src="{{asset('assets/libs/list.js/dist/list.min.js')}}" data-navigate-track ></script>
    <script src="{{asset('assets/libs/apexcharts/dist/apexcharts.min.js')}}" data-navigate-track ></script>
    <!-- Libs JS -->
    @yield('styles')
    <!-- Scripts -->

    <script src="{{ asset('assets/js/koverae.js?'.time())}}" data-navigate-track></script>

    <!-- FullCalendar CSS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.17/index.global.min.js"></script> --}}

    <!-- Scripts -->
    @livewireStyles
    @livewireScripts

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

    @yield('scripts')


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

        <livewire:app::components.notification-bell />

    </main>

    @livewire('wire-elements-modal')
    <!-- Custom JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts') <!-- This is where scripts pushed with @push('scripts') will be loaded -->
    <!-- Custom JS -->
</body>

</html>
