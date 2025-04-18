@extends('layouts.email')

@section('title', $subject)

@section('preview')
    Preview
@endsection

@section('content')
{!! $content !!}
@endsection

