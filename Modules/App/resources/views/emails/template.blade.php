@extends('layouts.email')

@section('title', $subject)

@section('preview')
    Preview
@endsection

@section('content')
<tr>
    <td class="pb-0 content" align="center" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 40px 48px 0;">
        <h1 class="m-0 text-center mt-md" style="font-weight: 600; color: #232b42; font-size: 28px; line-height: 130%; margin: 16px 0 0;" align="center">
            {{ $subject }}
        </h1>
    </td>
</tr>
<tr>
    <td class="content" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 40px 48px;">
        {!! $content !!}
    </td>
</tr>
@endsection

