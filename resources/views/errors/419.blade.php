@extends('layouts.error')

@section('title', __('Page Expired'))

@section('code', '419')

@section('image')
    <img src="{{ asset('assets/images/illustrations/errors/419.svg') }}" height="350px" alt="">
@endsection

@section('message', __("Your session has expired. Please refresh and try again."))

