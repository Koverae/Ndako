<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>@yield('page_title') | {{ config('app.name') }}</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{asset('assets/images/logo/favicon.ico')}}" />
    <!-- CSS files -->
    <link href="{{ asset('assets/css/koverae.css?'.time())}}" rel="stylesheet"/>
    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
      	--tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      }
      body {
      	font-feature-settings: "cv03", "cv04", "cv11";
      }
    </style>
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css" /> --}}
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js?render={{ env('GOOGLE_RECAPTCHA_KEY') }}"></script>

    <script src="{{ asset('assets/js/koverae.js?'.time())}}" data-navigate-track></script>

    <!-- Scripts -->
    @livewireStyles
  </head>
  <body  class="d-flex flex-column">
    <script src="{{ asset('assets/js/demo-theme.min.js?'.time())}}"></script>

    @yield('page_content')

    <!-- Libs JS -->
    @yield('scripts')
    @livewireScripts
    <!-- Koverae Core -->
  </body>
</html>
