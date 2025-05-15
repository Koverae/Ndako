@extends('layouts.email')

@section('title', 'OTP Code')

@section('preview')
    Your OTP code
@endsection

@section('content')
<tr>
    <td class="content">
        <p>Hi, <strong>{{ $user->name }}</strong>!</p>
        <p>It looks like you are trying to log in to Tabler Website using your username and password. As an additional security measure you are requested to enter the OTP code (one-time password) provided in this email.</p>
        <p>If you did not intend to log in to Tabler Website, please ignore this email.</p>
        <p class="mb-0">The OTP code is:</p>
        <table>
            <tr>
                <td class="py-lg">
                    <table cellspacing="0" cellpadding="0" class="w-auto" align="center">
                        <tr>
                            <td class="text-center border-dashed rounded border-wide border-dark px-lg py-md">
                                <div class="m-0 h1 font-strong">{{ $two_factor_code }}</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <p>If you enable two-factor authentication (2FA) with a trusted device you will not be asked for an OTP over email anymore.</p>
        <p>2FA is an extra layer of security used when logging into websites or apps. With 2FA, you have to log in with your username and password and provide another form of authentication that only you know or have access to. 2FA also protects package publishing. When you publish a package from the command line you will be prompted to provide a generated token in order to do so. Don't worry, if you want to automate publishing in CI/CD you can use an <a href="">Automation Token</a> to allow for publishing via token only without 2FA.</p>
        <p>
            To enable 2FA, please follow the instructions found <a href="">here</a>.<br />
            To create an automation token, please follow the instructions found <a href="">here</a>.<br />
            If you have any questions or concerns please feel free to <a href="">reach out to the Tabler Website support team</a>.
        </p>
    </td>
</tr>
@endsection