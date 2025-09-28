<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.ico') }}">
  <title>{{ current_company()->name }} — {{ __('Subscription expired') }}</title>
  <meta name="description" content="{{ __('Your Ndako access is paused because the subscription ended. Renew to regain full access.') }}">

  <!-- CSS -->
  <link href="{{ asset('assets/css/koverae.css?' . time()) }}" rel="stylesheet"/>
  <link href="{{ asset('assets/css/koverae-flags.min.css?' . time()) }}" rel="stylesheet"/>

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <!-- Font Awesome (kept if used elsewhere) -->
  <script src="https://kit.fontawesome.com/de3e85d402.js" crossorigin="anonymous"></script>

  <!-- Libs JS (kept to avoid breaking deps) -->
  <script src="{{ asset('assets/libs/list.js/dist/list.min.js') }}" data-navigate-track></script>

  @yield('styles')

  <!-- View-local, minimal polish -->
  <style>
    .exp-card { border: 1px solid rgba(0,0,0,.06); }
    .exp-hero-badge{
      width:64px;height:64px;display:inline-flex;align-items:center;justify-content:center;
      border-radius:50%;
      background: radial-gradient(100% 100% at 50% 0%, rgba(0,0,0,.06) 0%, rgba(0,0,0,.03) 100%);
      box-shadow: inset 0 1px 0 rgba(255,255,255,.6), 0 1px 2px rgba(0,0,0,.08);
    }
    .exp-hero-badge i{ font-size:28px; opacity:.9; }
    .exp-list { list-style:none; padding-left:0; margin:0; }
    .exp-list li{ display:flex; gap:.5rem; align-items:flex-start; margin:.25rem 0; }
    .exp-list .bi{ margin-top:.15rem; }
    .exp-muted{ color: var(--tblr-muted, rgba(0,0,0,.6)); }
    .exp-cta { text-transform: uppercase; letter-spacing:.02em; }
    @media (max-width: 575.98px){
      .card-md { border-radius: .75rem; }
      .exp-hero-badge{ width:56px;height:56px; }
    }
  </style>
</head>
<body>
  <script src="{{ asset('assets/js/demo-theme.min.js') }}" data-navigate-track></script>

  @php
    $subscription = optional(optional(current_company()->team)->subscription('main'));
    $endsAt      = optional($subscription)->ends_at;
    $endsAtText  = $endsAt ? $endsAt->timezone(config('app.timezone'))->format('M d, Y') : __('recently');
  @endphp

  <div class="page page-center">
    <div class="container container-tight py-4">
      <div class="card card-md exp-card" role="alert" aria-live="polite">
        <div class="card-body">
          <div class="mt-0 mb-3 text-center">
            <a href="{{ url('/') }}" class="navbar-brand navbar-brand-autodark" aria-label="{{ current_company()->name }}">
              <img src="{{ asset('assets/images/logo/logo-black.png') }}"
                   width="130" height="52"
                   alt="{{ current_company()->name }} logo"
                   class="navbar-brand-image">
            </a>
          </div>

          <div class="text-center mb-3">
            <span class="exp-hero-badge">
              <i class="bi bi-credit-card-2-front"></i>
            </span>
          </div>

          <h2 class="mb-2 text-center">
            {{ __('Your free trial period has expired!') }}
          </h2>

          <p class="mb-2 fs-3">
            {{ __('Your access to Ndako has been temporarily paused because your subscription ended on') }}
            <b>{{ $endsAtText }}</b>.
          </p>

          <p class="mb-4 fs-3 exp-muted">
            {{ __('Renew now to regain full access and keep managing your properties smoothly—without interruptions.') }}
          </p>

          <!-- Small reassurance list -->
          <ul class="exp-list mb-4 fs-3">
            <li><i class="bi bi-check2-circle"></i> {{ __('Instant reactivation after payment') }}</li>
            <li><i class="bi bi-check2-circle"></i> {{ __('Your data remains safe and intact') }}</li>
            <li><i class="bi bi-check2-circle"></i> {{ __('You can switch plans anytime') }}</li>
          </ul>

          <div class="my-4">
            <a href="{{ route('subscribe', ['renew' => true]) }}"
               class="btn btn-primary w-100 fs-3 exp-cta" aria-label="{{ __('Renew subscription') }}">
              <i class="bi bi-arrow-repeat me-1"></i> {{ __('Renew Subscription') }}
            </a>
          </div>

          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <p class="m-0 exp-muted">
              {{ __('Need help renewing?') }}
              <a href="https://ndako.koverae.com/contact-us?utm=app" class="underline" target="__blank" rel="noopener">
                {{ __('Contact us') }}
              </a>.
            </p>
            <a href="{{ route('subscribe') }}" class="text-decoration-none fs-4">
              <i class="bi bi-sliders me-1"></i>{{ __('View plans') }}
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('assets/js/koverae.js?' . time()) }}" data-navigate-track></script>
</body>
</html>
