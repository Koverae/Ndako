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
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4 text-green-50" :status="session('status')" />
        
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="email">Email address</label>
                <input type="email" class="form-control" placeholder="eg. ardenbouet@koverae.com" id="email" name="email" value="{{ old('email') }}" required>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">Email Password Reset Link</button>
            </div>
        </form>
      </div>
    </div>

  </div>
</div>
@endsection

