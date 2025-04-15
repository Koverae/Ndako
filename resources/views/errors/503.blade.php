@extends('layouts.error')

@section('title', __('Under Maintenance 🚧🔧🛠️'))

@section('code', '503')

@section('image')
    <img src="{{ asset('assets/images/illustrations/errors/503.svg') }}" height="350px" alt="">
@endsection

@section('message')
    <span>{{ __("We're currently performing some maintenance to keep things running smoothly") }}</span>
    <br>
    <span>{{ __('Please check back shortly. Your patience means everything 💛') }}</span>
@endsection
