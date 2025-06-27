@extends('layouts.auth')

@section('page_title', 'Complete Phone Number')

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
            {{ __('Kindly complete your phone number.') }}
        </div>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4 text-green-50" :status="session('status')" />
        
        <form method="POST" action="{{ route('complete-phone.submit') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="phone">Phone Number</label>
                <input type="phone" class="form-control" placeholder="eg. +254 745908026" id="phone" name="phone" value="{{ old('phone') }}" required>
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">Save</button>
            </div>
        </form>
      </div>
    </div>

  </div>
</div>
@endsection

