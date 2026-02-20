<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="{{ app()->getLocale() }}" xml:lang="{{ app()->getLocale() }}" dir="{{ config('text_dir') }}" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{{ config('app.name') }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    {{ $head ?? '' }}
</head>
<body style="box-sizing:border-box;font-family:'Arial',sans-serif;position:relative;-webkit-text-size-adjust:none;background-color:#fff;color:#000;height:100%;line-height:1.4;margin:0;padding:0;width:100% !important;">
<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="-premailer-cellpadding:0;-premailer-cellspacing:0;-premailer-width:100%;background-color:#fff;margin:0;padding:0;width:100%;">
    <tr>
        <td align="center">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="-premailer-cellpadding:0;-premailer-cellspacing:0;-premailer-width:100%;margin:0;padding:0;width:100%;">
                <tr>
                    <td style="padding:15px 0 20px;text-align:center;">
                        <a href="https://nettoweb.ru" target="_blank" style="display:inline-block;width:150px;outline:0;">
                            @include('cms::components.icons.logo')
                        </a>
                    </td>
                </tr>
                <tr>
                    <td width="100%" cellpadding="0" cellspacing="0" style="border:hidden !important;-premailer-cellpadding:0;-premailer-cellspacing:0;-premailer-width:100%;background-color:#fff;margin:0;padding:0;width:100%;">
                        <table align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="background-color:#f0f0f0;margin:0 auto;padding:0;width:570px;-premailer-cellpadding:0;-premailer-cellspacing:0;-premailer-width:570px;">
                            <tr>
                                <td style="max-width:100vw;padding:32px;border:1px solid #e2e4ff;">
                                    @yield('content')
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="-premailer-cellpadding:0;-premailer-cellspacing:0;-premailer-width:570px;margin:0 auto;padding:0;text-align:center;width:570px;">
                            <tr>
                                <td style="box-sizing:border-box;position:relative;max-width:100vw;" align="center">
                                    <p style="font-size:12px;text-align:center;">
                                        &copy;{{ date('Y') }} <a style="color:#2a3582;word-break:break-all;" href="https://nettoweb.ru" target="_blank">nettoweb</a>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
