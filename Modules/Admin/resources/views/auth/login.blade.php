@extends('layouts.auth')

@section('page_title', 'Sign In Admin')

@section('page_content')
<div class="overflow-x-hidden page page-center">
    <div class="row align-items-center g-4">
        <div class="col-lg">
            <div class="container py-4 container-tight">
                <div class="card card-md">
                    <div class="card-body">
                        <div class="mt-0 mb-2 text-center">
                            <a href="#" class="navbar-brand navbar-brand-autodark">
                                <img src="{{ asset('assets/images/logo/koverae.png') }}" width="130" height="52" alt="Tabler" class="navbar-brand-image">
                            </a>
                        </div>
                        <h2 class="mb-4 text-center h2">Login to your account</h2>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('admin.login') }}" id="login">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label" for="email">Email address</label>
                                <input type="email" class="form-control" placeholder="eg. ardenbouet@koverae.com" id="email" name="email" value="{{ old('email') }}" required>
                            </div>

                            <div class="mb-2">
                                <label class="form-label" for="password">
                                    Password
                                    @if (Route::has('password.request') && env('APP_DISTRIBUTION') === "production")
                                    <span class="form-label-description">
                                        <a href="{{ route('password.request') }}">I forgot password</a>
                                    </span>
                                    @endif
                                </label>
                                <div class="input-group input-group-flat">
                                    <input type="password" class="form-control" placeholder="Your password" id="password" name="password" autocomplete="off">
                                    <span class="input-group-text">
                                        <span onclick="togglePassword()" class="link-secondary" title="Show password" data-bs-toggle="tooltip">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                            </svg>
                                        </span>
                                    </span>
                                </div>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $('#login').submit(function(event) {
        event.preventDefault();

        grecaptcha.ready(function() {
            grecaptcha.execute("{{ env('GOOGLE_RECAPTCHA_KEY') }}", {action: 'subscribe_newsletter'}).then(function(token) {
                $('#login').prepend('<input type="hidden" name="g-recaptcha-response" value="' + token + '">');
                $('#login').unbind('submit').submit();
            });
        });
    });
</script>
@endsection
