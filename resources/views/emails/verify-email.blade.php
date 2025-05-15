@extends('layouts.email')

@section('title', 'Verify Your Email Address')

@section('preview')
    Verify Your Email
@endsection

@section('content')
<tr>
    <td class="pb-0 content" align="center">
        <table class="icon icon-lg bg-green" cellspacing="0" cellpadding="0">
            <tr>
                <td valign="middle" align="center">
                    <img src="{{ public_path('assets/images/email/icons-white-check.png')}}" class=" va-middle" width="40" height="40" alt="check" />
                </td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td class="text-center content">
        <p class="h1">We're glad to have you here, Arden!</p>
        <p>Thanks for registering and your willingness to try {{ config('app.name') }} out. To authenticate your email address, please click on below button.</p>
    </td>
</tr>
<tr>
    <td class="pt-0 text-center content pb-xl">
        <table cellspacing="0" cellpadding="0">
            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" border="0" class="w-auto rounded bg-green">
                        <tr>
                            <td align="center" valign="top" class="lh-1">
                                <a href="{{ $verificationUrl }}" class="btn bg-green border-green">
                                    <span class="btn-span">Confirm&nbsp;your&nbsp;email&nbsp;address</span>
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>
@endsection
