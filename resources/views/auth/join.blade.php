@extends('layouts.auth')

@section('page_title', 'Join '. $invitation->company->name)

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
        <h2 class="mb-4 text-center h2">Join {{ $invitation->company->name }}</h2>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('company.invitations.accept.post', $invitation->token) }}">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="name">Full Name</label>
                <input type="text" class="form-control" placeholder="eg. Brian Mwangi" id="name" name="name" value="{{ old('name') }}" required>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="email">Email address</label>
                <input type="email" class="form-control" placeholder="eg. brianmwangi@company.com" id="email" name="email" value="{{ $invitation->email }}" disabled>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="phone">Phone Number</label>
                <input type="tel" class="form-control" placeholder="eg. +254 712 345 678" id="phone" name="phone" value="{{ old('phone') }}" required>
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>
            <div class="mb-2">
                <label class="form-label" for="password">
                Password
                </label>
                <div class="input-group input-group-flat">
                <input type="password" class="form-control"  placeholder="Your password" id="password" name="password"  autocomplete="off">
                <span class="input-group-text">
                    <span  onclick="togglePassword()" class="link-secondary" title="Show password" data-bs-toggle="tooltip"><!-- Download SVG icon from http://koverae-icons.io/i/eye -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                    </span>
                </span>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="phone">Role</label>
                <select name="role" id="" class="form-control" disabled>
                    <option value="">{{__('--- Choose ---')}}</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ $invitation->role == $role->name ? 'selected' : '' }}>{{ inverseSlug($role->name) }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>
            <div class="mb-1">
                <label class="text-muted fs-5" for="remember_me">
                    <span class="form-check-label">By joining <strong>{{ $invitation->company->name }}</strong>, you accept our <a href="https:ndako.koverae.com/terms" target="_blank">terms and conditions</a></span>
                </label>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100 text-uppercase">Accept Invitation</button>
            </div>
            <div class="p-3 card-body">
                <div class="mt-2 text-center text-secondary">
                    Already have an account? <a href="{{ route('login') }}" tabindex="-1">Sign in</a>
                </div>
            </div>
        </form>
      </div>
    </div>

  </div>
</div>
@endsection
