@extends('layouts.auth')

@section('page_title', 'Forgot Password')

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
        
        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>
        
        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 text-sm font-medium text-green-600 dark:text-green-400">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif
        <!-- Session Status -->
        <x-auth-session-status class="mb-4 text-green-50" :status="session('status')" />
        
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <div class="form-footer">
              <div class="btn-list flex-nowrap">
                <button type="button" class="btn w-100">
                  {{ __('Log Out') }}
                </button>
                <button type="submit" class="capitalize btn btn-primary w-100 text-capitalize">
                  {{ __('Resend Verification Email') }}
                </button>
              </div>
            </div>
        </form>
      </div>
    </div>

  </div>
</div>
@endsection
