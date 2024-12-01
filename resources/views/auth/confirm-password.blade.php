@extends('layouts.auth')

@section('page_title', 'Confirm Password')

@section('page_content')
<div class="page page-center">
  <div class="container py-4 container-tight">
    <div class="card card-md">
      <div class="card-body">
        <div class="mt-0 mb-2 text-center">
          <a href="#" class="navbar-brand navbar-brand-autodark">
            <img src="{{ asset('assets/images/logo/logo-black.png') }}" width="130" height="52" alt="Koverae" class="navbar-brand-image">
          </a>
        </div>
        <div class="mb-2 text-center">
          <h2 class="card-title h2">{{ __('Restricted Area') }}</h2>
          <p class="text-secondary">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
          </p>
          <img src="{{ asset('assets/images/default/default_avatar.png') }}" width="100" height="100" alt="{{ auth()->user()->name }}" class="rounded-circle align-center">
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />
        
        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf
            <!-- Password -->
            <div class="mb-2">
                <label class="form-label" for="password">
                Password
                </label>
                <div class="input-group input-group-flat">
                <input type="password" class="form-control"  placeholder="Your password" id="password" name="password" required autocomplete="current-password" autocomplete="off">
                <span class="input-group-text">
                    <span  onclick="togglePassword()" class="link-secondary" title="Show password" data-bs-toggle="tooltip"><!-- Download SVG icon from http://koverae-icons.io/i/eye -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                    </span>
                </span>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            
            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 11m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" /><path d="M12 16m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M8 11v-5a4 4 0 0 1 8 0" /></svg>
                    {{ __('Confirm') }}
                </button>
            </div>
        </form>
      </div>
      
    </div>

  </div>
</div>
@endsection

