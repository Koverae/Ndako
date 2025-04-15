@extends('layouts.error')

@section('title', __('Unauthorized'))

@section('code', '401')

@section('image')
    <img src="{{ asset('assets/images/illustrations/errors/401.svg') }}" height="350px" alt="">
@endsection

@section('message', __("You do not have permission to access this page."))

