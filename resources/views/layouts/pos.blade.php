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

    <!-- Leaflet.js CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha384-kr7knlC+7+03I2GzYDBHmxOStG8VIEyq6whWqn2oBoo1ddubZe6UjI+P5bn/f8O5" data-navigate-track/>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha384-kgpA7T5GkjxAeLPMFlGLlQQGqMAwq8ciFnjsbPvZaFUHZvbRYPftvBcRea/Gozbo" data-navigate-track></script>
    <!-- Leaflet.js CSS -->

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
        <nav class="navbar navbar-expand-md w-100 navbar-light d-block d-print-none k-sticky">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Logo -->
                <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href="">
                        <img src="{{asset('assets/images/logo/logo-black.png')}}" alt="Ndako Logo" class="navbar-brand-image">
                    </a>
                </h1>
                <!-- Logo End -->

                <!-- Navbar Buttons -->
                <div class="flex-row navbar-nav order-md-last">
                    <div class="d-md-flex d-flex">
                        <!-- Translate -->
                        <div class="nav-item dropdown d-md-flex me-3">
                            <a href="#" class="px-0 nav-link" data-bs-toggle="dropdown" id="dropdownMenuButton" title="Translate" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                <i class="bi bi-translate" style="font-size: 16px;"></i>
                            </a>
                        </div>
                        <!-- Translate End -->

                        <!-- User's Avatar -->
                        <div class="nav-item dropdown">
                            <a href="#" class="p-0 nav-link d-flex lh-1 text-reset" data-bs-toggle="dropdown" aria-label="Open user menu">
                                <span class="avatar avatar-sm" style="background-image: url({{ Storage::url('avatars/' . auth()->user()->avatar) }})"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <a href="https://docs.koverae.com/ndako" target="__blank" class="dropdown-item kover-navlink">Documentation</a>
                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout')}}">
                                    @csrf
                                    <span  onclick="event.preventDefault(); this.closest('form').submit();" class="cursor-pointer kover-navlink dropdown-item">
                                        Log Out
                                    </span>
                                </form>
                                <!-- Authentication End -->
                            </div>
                        </div>
                        <!-- User's Avatar End -->
                    </div>
                </div>
                <!-- Navbar Buttons End -->

                <!-- Navbar Menu -->
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                        <ul class="navbar-nav">
                            <!-- Navbar Menu -->
                            <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">

                                <li class="nav-item" data-turbolinks>
                                    <a class="nav-link kover-navlink" href="{{ route('dashboard') }}" style="margin-right: 5px;">
                                    <span class="nav-link-title">
                                        {{ __('Dashboard') }}
                                    </span>
                                    </a>
                                </li>

                            </div>
                            <!-- Navbar Menu -->
                        </ul>
                    </div>
                </div>
                <!-- Navbar Menu End -->

            </div>

        </nav>

        <!-- Navbar End -->

        <!-- Page Content -->
        @yield('content')
        <!-- Page Content End -->

    </main>

    @livewire('wire-elements-modal')
    <!-- Custom JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts') <!-- This is where scripts pushed with @push('scripts') will be loaded -->
    <!-- Custom JS -->
</body>

</html>
