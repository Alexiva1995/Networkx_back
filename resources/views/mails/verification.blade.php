@extends('mails.layout')

@section('message-content')
    <tr>
        <td bgcolor="#ffffff" style="padding: 10px 30px; text-align:center;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                <tr>
                    <td>
                        <h1 style="font-size: 20px; margin-bottom: 10px;">Your verification code is:</h1>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="text-align: center; margin-top: 20px">
                            <h2 style="font-size: 18px;">{{ $data['user']->code_security }}</h2>
                            <br>
                            <a href="{{ config("services.url_front.url")."mail-verify" }}" target="_blank"
                                style="border-radius: 10px; color: #fff; display: inline-block; text-decoration: none;
                                background-color: #078AF0; margin: 25px 0 0; padding: 10px 28px;">Verify email</a>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection
