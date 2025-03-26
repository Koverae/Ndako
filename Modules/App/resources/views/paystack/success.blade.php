<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('assets/images/logo/favicon.ico')}}">
    <title>Payment Successful 🎉</title>

    <!-- CSS -->
    <link href="{{asset('assets/css/koverae.css?'.time())}}" rel="stylesheet"/>
    <link href="{{asset('assets/css/koverae-flags.min.css?'.time())}}" rel="stylesheet"/>
    <!-- CSS -->

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/de3e85d402.js" crossorigin="anonymous"></script>
    <!-- Font Awesome -->

    <!-- Libs JS -->
    <script src="{{asset('assets/libs/list.js/dist/list.min.js')}}" data-navigate-track ></script>
    <!-- Libs JS -->
    @yield('styles')

</head>
<body>
    <script src="{{asset('assets/js/demo-theme.min.js')}}" data-navigate-track></script>
    <div class="page page-center">
      <div class="container py-4 container-tight">
        <div class="card card-md">
          <div class="card-body">
            <div class="mt-0 mb-2 text-center">
                <a href="#" class="navbar-brand navbar-brand-autodark">
                    <img src="{{ asset('assets/images/logo/logo-black.png') }}" width="130" height="52" alt="Tabler" class="navbar-brand-image">
                </a>
            </div>
            <h2 class="mb-3 text-center">Payment Successful 🎉</h2>
            <p class="mb-4 text-secondary fs-3">
                Thank you for your payment! Your subscription has been activated.
            </p>

            <div class="mt-4">
                <strong>Transaction ID:</strong> {{ $data['reference'] }} <br>
                <strong>Amount Paid:</strong> {{ format_currency($data['amount'] / 100, 2) }} <br>
                <strong>Status:</strong> <span class="badge bg-success">{{ ucfirst($data['status']) }}</span>
            </div>

            @if(isset($subscription))
            <div class="mt-4 mb-2">
                <h4>📅 Subscription Details</h4>
                <strong>Plan Name:</strong> {{ $subscription->plan_name }} <br>
                <strong>Next Payment Date:</strong> {{ \Carbon\Carbon::parse($subscription->next_billing_date)->format('F j, Y') }} <br>
                <strong>Billing Cycle:</strong> {{ ucfirst($subscription->billing_cycle) }}
            </div>
            @endif

            <div class="my-4">
              <a href="{{ route('dashboard') }}" class="text-white btn btn-primary text-uppercase w-100 fs-3">
                Go to Dashboard
              </a>
            </div>
            {{-- <p class="text-secondary">
                Need more time? <a href="https://ndako.koverae.com/contact-us?utm=app" class="underline " target="__blank">Contact us</a> to request a trial extension.
            </p> --}}
          </div>
        </div>
      </div>
    </div>


    <script src="{{ asset('assets/js/koverae.js?'.time())}}" data-navigate-track></script>
</body>

</html>
