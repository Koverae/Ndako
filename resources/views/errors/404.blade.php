@extends('layouts.error')

@section('title', __('Page Not Found'))

@section('code', '404')

@section('image')
    <img src="{{ asset('assets/images/illustrations/errors/404.svg') }}" height="350px" alt="">
@endsection

@section('message', __("Oops! The page you’re looking for doesn’t exist."))

