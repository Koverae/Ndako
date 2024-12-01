@props(['url'])
{{-- <tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr> --}}

<table cellpadding="0" cellspacing="0">
    <tr>
        <td class="py-lg">
            <table cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <a href="{{ $url }}">
                            <img src="{{ public_path('assets/images/logo/logo-black.png') }}" width="116" height="34" alt="" />
                        </a>
                    </td>
                    {{-- <td class="text-right">
                        <a href="{{ $url }}" class="text-muted-light">
                            View online
                        </a>
                    </td> --}}
                </tr>
            </table>
        </td>
    </tr>
</table>