<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('assets/images/logo/favicon.ico')}}">
    <title>@yield('title')</title>

    <!-- CSS -->
    <link href="{{asset('assets/css/koverae.css?'.time())}}" rel="stylesheet"/>
    <link href="{{asset('assets/css/koverae-flags.min.css?'.time())}}" rel="stylesheet"/>
    {{-- <link href="{{ asset('assets/css/demo.min.css?'.time())}}" rel="stylesheet"/> --}}
    <!-- CSS -->

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <!-- Bootstrap Icons -->

</head>
<body>
    <script src="{{asset('assets/js/demo-theme.min.js')}}" data-navigate-track></script>
    <main class="page">

        <div class="page-body">
          <div class="container-xl d-flex flex-column justify-content-center">
            <div class="k_nocontent_help empty">
              <div class="">
                @yield('image')
              </div>
              <p class="empty-title">
                @yield('title') -  @yield('code', __('Oh no!'))
              </p>
              <p class="empty-subtitle text-muted">
                @yield('message')
              </p>
              <div class="empty-action">
                <a href="{{ route('dashboard') }}" class="gap-1 btn btn-primary font-weight-bold" style="background-color: #3d6a6b;">
                  <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                  <i class="mr-2 bi bi-house"></i>
                  <span class="ml-4">{{ __("Go to Dashboard") }}</span>
                </a>
              </div>
            </div>
          </div>
        </div>

    </main>

</body>

</html>
