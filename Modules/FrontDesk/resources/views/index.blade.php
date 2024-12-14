@extends('frontdesk::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('frontdesk.name') !!}</p>
@endsection
