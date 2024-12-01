@extends('app::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('app.name') !!}</p>
@endsection
