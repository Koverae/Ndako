@extends('layouts.error')

@section('title', __('Server Error'))
@section('code', '500')

@section('image')
    <img src="{{ asset('assets/images/illustrations/errors/500.svg') }}" height="350px" alt="">
@endsection

@section('message', __("Oops! Something went wrong on our side. We’re working on it."))

