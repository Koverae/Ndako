<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{{ config('app.name') }}</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta content="telephone=no" name="format-detection" />
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">

<style data-premailer="ignore">
    @media screen and (max-width: 600px) {
        u+.body {
            width: 100vw !important;
        }
    }

    a[x-apple-data-detectors] {
        color: inherit !important;
        text-decoration: none !important;
        font-size: inherit !important;
        font-family: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
    }
</style>
<!--[if mso]>
  <style type="text/css">
    body, table, td {
        font-family: Arial, Helvetica, sans-serif !important;
    }

    img {
        -ms-interpolation-mode: bicubic;
    }

    .box {
        border-color: #eee !important;
    }
  </style>
<![endif]-->
<!--[if !mso]><!-->
{{-- <link href="https://rsms.me/inter/inter.css" rel="stylesheet" type="text/css" data-premailer="ignore" /> --}}
<style type="text/css" data-premailer="ignore">
    @import url(https://rsms.me/inter/inter.css);
</style>
<!--<![endif]-->
    <link href="{{ asset('assets/css/email-theme.css?'.time())}}" rel="stylesheet"/>

</head>

<body class="bg-body">
    <center>
        <table class="main bg-body" width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td align="center" valign="top">
                    <!--[if (gte mso 9)|(IE)]>
                <table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="center" valign="top" width="640">
              <![endif]-->
                    <span class="preheader">@yield('preview')</span>
                    <table class="wrap" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="p-sm">
                                <!-- Header -->
                                <table cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td class="py-lg">
                                            <table cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td>
                                                        <a href="{{ config('app.url') }}">
                                                            <img src="{{ public_path('assets/images/logo/logo-black.png') }}" width="116" height="34" alt="" />
                                                        </a>
                                                    </td>
                                                    <td class="text-right">
                                                        <a href="{{ config('app.url') }}" class="text-muted-light">
                                                            View online
                                                        </a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <!-- Header -->
                                
                                <!-- Email Body -->
                                <div class="main-content">
                                    <table class="box" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td>
                                                @yield('content')
                                                {{-- <table cellpadding="0" cellspacing="0">
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
                                                            <p class="h1">We're glad to have you here, Bartek!</p>
                                                            <p>Thanks for registering and your willingness to try Tabler out. To authenticate your email address, please click on below button.</p>
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
                                                                                    <a href="https://tabler.io/emails?utm_source=demo" class="btn bg-green border-green">
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
                                                    <tr>
                                                        <td class="text-center content border-top">
                                                            <p>
                                                                Yours sincerely,<br>
                                                                Arden BOUET, CEO at <a href="https://koverae.com/emails?utm_source=email" class="text-default font-weight-bold">Koverae</a>
                                                            </p>
                                                            <p>
                                                                <img src="{{ public_path('assets/images/email/founder.jpg') }}" class=" avatar" width="72" height="72" alt="Arden BOUET" />
                                                            </p>
                                                        </td>
                                                    </tr>
                                                </table> --}}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <!-- Footer -->
                                <table cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="py-xl">
                                            <table class="text-center text-muted" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td align="center" class="pb-md">
                                                        <table class="w-auto" cellspacing="0" cellpadding="0">
                                                            <tr>
                                                                <td class="px-sm">
                                                                    <a href="https://tabler.io/emails?utm_source=demo">
                                                                        <img src="{{ asset('assets/images/email/icons-gray-brand-facebook.png')}}" class=" va-middle" width="24" height="24" alt="brand-facebook" />
                                                                    </a>
                                                                </td>
                                                                <td class="px-sm">
                                                                    <a href="https://tabler.io/emails?utm_source=demo">
                                                                        <img src="{{ asset('assets/images/email/icons-gray-brand-twitter.png')}}" class=" va-middle" width="24" height="24" alt="brand-twitter" />
                                                                    </a>
                                                                </td>
                                                                <td class="px-sm">
                                                                    <a href="https://tabler.io/emails?utm_source=demo">
                                                                        <img src="{{ asset('assets/images/email/icons-gray-brand-github.png')}}" class=" va-middle" width="24" height="24" alt="brand-github" />
                                                                    </a>
                                                                </td>
                                                                <td class="px-sm">
                                                                    <a href="https://tabler.io/emails?utm_source=demo">
                                                                        <img src="{{ asset('assets/images/email/icons-gray-brand-youtube.png')}}" class=" va-middle" width="24" height="24" alt="brand-youtube" />
                                                                    </a>
                                                                </td>
                                                                <td class="px-sm">
                                                                    <a href="https://tabler.io/emails?utm_source=demo">
                                                                        <img src="{{ asset('assets/images/email/icons-gray-brand-pinterest.png')}}" class=" va-middle" width="24" height="24" alt="brand-pinterest" />
                                                                    </a>
                                                                </td>
                                                                <td class="px-sm">
                                                                    <a href="https://tabler.io/emails?utm_source=demo">
                                                                        <img src="{{ public_path('assets/images/email/icons-gray-brand-instagram.png')}}" class=" va-middle" width="24" height="24" alt="brand-instagram" />
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="px-lg">
                                                        If you have any questions, feel free to message us at <a href="mailto:support@koverae.com" class="text-muted">support@koverae.com</a>.
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="pt-md">
                                                        You are receiving this email because you have bought or downloaded one of the <strong>Koverae</strong> products. <a href="https://koverae.com/emails?utm_source=demo" class="text-muted">Unsubscribe</a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <!-- Footer -->
                            </td>
                        </tr>
                    </table>
                    <!--[if (gte mso 9)|(IE)]>
              </td>
            </tr>
          </table>
              <![endif]-->
                </td>
            </tr>
        </table>
    </center>
</body>
</html>
