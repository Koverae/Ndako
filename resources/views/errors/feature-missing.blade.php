@extends('layouts.error')

@section('title', $title)
@section('code', '')

@section('image')
    <img src="{{ asset('assets/images/illustrations/errors/feature-missing.svg') }}" height="350px" alt="">
@endsection

@section('message', $message)

