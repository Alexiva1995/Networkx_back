<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Help - Email Verification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
</head>

<style>
    @media only screen and (max-device-width: 640px) {

        /* mobile-specific CSS styles go here */
        .tablescale {
            width: 440px !important;
            margin:
        }

        .imgscale {
            width: 100% !important;
        }
    }

    @media only screen and (max-device-width: 479px) {

        /* mobile-specific CSS styles go here */
        .tablescale {
            width: 325px !important;
            margin: 0 !important;
        }

        .imgscale {
            width: 100% !important;
            height: auto !important;
            margin: auto !important;
        }
    }

    body {
        font-family: 'Montserrat', sans-serif;

    }

    h1 {
        font-weight: bold;
    }
</style>

<body style="margin: 0; padding: 0;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td>
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="600"
                    style="border-collapse: collapse; border: 1px solid #e9e9e9; border-radius: 15px; box-shadow: 10px 10px 20px #00000015">
                    <tr>
                        <td align="center" bgcolor="#FFFFFF" style="background: linear-gradient(112.69deg, #2FE7FA -24.41%, #7A21C7 88.97%);">
                            @if (isset($message))
                                <img src="{{ $message->embed(public_path('/images') . '/logo/logo-b2b.png') }}"
                                    alt="B2B Logo" style="display: block; width: 100%;" />
                            @else
                                <img src="/images/logo/logo-b2b.png" alt="B2B Logo" style="display: block; width:100%;" />

                            @endif
                        </td>
                    </tr>
                    @yield('message-content')
                    <tr>
                        <td>
                            <p style="text-align: start; font-size: 12px; padding: 10px 40px; opacity: 0.5;">
                                This is an automated message, please do not respond.
                                Thank you for choosing us.
                            </p>
                            <hr style="font-size: 15px; padding: 0; margin: 10px 40px; opacity: 0.5;">
                            <p style="text-align: start; font-size: 12px; padding: 10px 40px;">
                                Note: Be careful with phishing websites and always make sure to visit the official
                                website
                                <a href="{{ config('services.url_front.url') }}" target="_blank"
                                    style="text-decoration: underline; color: #078AF0;">www.business2business.space</a>

                                when entering confidential data.
                                <a href="{{ config('app.url') . 'api/mails-terms-conditions' }}"
                                    style="text-decoration: underline; color: #46087d;">Terms and conditions.</a>
                            </p>
                        </td>
                    </tr>
                    <tr>

                        <td style="padding: 20px; background: linear-gradient(112.69deg, #2FE7FA -24.41%, #7A21C7 88.97%); ">
                            <p style="text-align: center; color: #ffffff !important; margin: 0; font-size: 12px; letter-spacing: 1px;">
                                &reg; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
