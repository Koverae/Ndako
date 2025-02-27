@extends('layouts.auth')

@section('page_title', 'Join '. current_company()->name)

@section('page_content')
<div class="page page-center">
  <div class="container py-4 container-tight">
    <div class="card card-md">
      <div class="card-body">
        <div class="mt-0 mb-2 text-center">
          <a href="#" class="navbar-brand navbar-brand-autodark">
            <img src="{{ asset('assets/images/logo/logo-black.png') }}" width="130" height="52" alt="Tabler" class="navbar-brand-image">
          </a>
        </div>
        <h2 class="mb-4 text-center h2">Login to your account</h2>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />
        
        <form method="POST" action="{{ route('company.invitations.accept.post', $invitation->token) }}">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="email">Email address</label>
                <input type="email" class="form-control" placeholder="eg. ardenbouet@koverae.com" id="email" name="email" value="{{ old('email') }}" required>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div class="mb-2">
                <label class="form-label" for="password">
                Password
                @if (Route::has('password.request'))
                <span class="form-label-description">
                    <a href="{{ route('password.request') }}">I forgot password</a>
                </span>
                @endif
                </label>
                <div class="input-group input-group-flat">
                <input type="password" class="form-control"  placeholder="Your password" id="password" name="password"  autocomplete="off">
                <span class="input-group-text">
                    <span  onclick="togglePassword()" class="link-secondary" title="Show password" data-bs-toggle="tooltip"><!-- Download SVG icon from http://koverae-icons.io/i/eye -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                    </span>
                </span>
                </div>
                {{-- <x-input-error :messages="$errors->get('password')" class="mt-2" /> --}}
            </div>
            <div class="mb-2">
                <label class="form-check" for="remember_me">
                <input type="checkbox" id="remember_me" name="remember" class="form-check-input"/>
                <span class="form-check-label">Remember me on this device</span>
                </label>
            </div>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">Sign in</button>
            </div>
        </form>
      </div>
      <div class="hr-text">or</div>
      <div class="p-3 card-body">
        <div class="row">
            <div class="mt-2 col-md-6 col-12">
                <a href="{{ route('auth.social.redirect', ['provider' => 'google']) }}" class="btn w-100">
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-google"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20.945 11a9 9 0 1 1 -3.284 -5.997l-2.655 2.392a5.5 5.5 0 1 0 2.119 6.605h-4.125v-3h7.945z" /></svg>
                    {{ __('Login with Google') }}
                </a>
            </div>
            <div class="mt-2 col-md-6 col-12">
                <a href="{{ route('auth.social.redirect', ['provider' => 'koverae']) }}" class="btn w-100">
                    <img src="{{ asset('assets/images/logo/favicon.ico') }}" width="20px" height="20px" alt="">
                    {{ __('Login with Koverae') }}
                </a>
            </div>
            <div class="mt-2 col-md-6 col-12">
                <a href="{{ route('auth.social.redirect', ['provider' => 'linkedin']) }}" class="btn w-100">
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-linkedin"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M8 11l0 5" /><path d="M8 8l0 .01" /><path d="M12 16l0 -5" /><path d="M16 16v-3a2 2 0 0 0 -4 0" /></svg>
                    {{ __('Login with LinkedIn') }}
                </a>
            </div>
            <div class="mt-2 col-md-6 col-12">
                <a href="{{ route('auth.social.redirect', ['provider' => 'facebook']) }}" class="btn w-100">
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-meta"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 10.174c1.766 -2.784 3.315 -4.174 4.648 -4.174c2 0 3.263 2.213 4 5.217c.704 2.869 .5 6.783 -2 6.783c-1.114 0 -2.648 -1.565 -4.148 -3.652a27.627 27.627 0 0 1 -2.5 -4.174z" /><path d="M12 10.174c-1.766 -2.784 -3.315 -4.174 -4.648 -4.174c-2 0 -3.263 2.213 -4 5.217c-.704 2.869 -.5 6.783 2 6.783c1.114 0 2.648 -1.565 4.148 -3.652c1 -1.391 1.833 -2.783 2.5 -4.174z" /></svg>
                    {{ __('Login with Facebook') }}
                </a>
            </div>
        </div>
        @if (Route::has('register'))
        <div class="mt-4 text-center text-secondary">
          Don't have account yet? <a href="{{ route('register') }}" tabindex="-1">Sign up</a>
        </div>
        @endif
      </div>
    </div>

  </div>
</div>
@endsection
