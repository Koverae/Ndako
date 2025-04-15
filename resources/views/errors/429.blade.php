@extends('layouts.error')

@section('title', __('Slow Down'))

@section('code', '429')

@section('image')
    <img src="{{ asset('assets/images/illustrations/errors/429.svg') }}" height="350px" alt="">
@endsection

@section('message', __("You’re sending too many requests. Please take a short break and try again."))

