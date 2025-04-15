@extends('layouts.error')

@section('title', __('Access Denied'))

@section('code', '403')

@section('image')
    <img src="{{ asset('assets/images/illustrations/errors/403.svg') }}" height="350px" alt="">
@endsection

@section('message', __("You do not have permission to access this page."))

