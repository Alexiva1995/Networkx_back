@extends('mails.layout')

@section('message-content')
    <tr>
        <td bgcolor="#ffffff" style="padding: 0 40px 40px 30px; text-align:center;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                style="border-collapse: collapse;">
                <tr>
                    <td>
                        <h1 style="font-size: 18px; margin-bottom: 12px;">your withdrawal confirmation code is</h1>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="text-align: center;margin-top: 1em">
                            <h2 style="margin-top: 1em">{{ $data['user']->code_security }}</h2>
                            <a href="{{config('services.url_front.url').'mail-verify'}}" target="_blank"
                                style="font-family: Poppins, sans-serif; box-sizing: border-box; border-radius: 10px; color: #fff; display: inline-block; text-decoration: none; background-color: #078AF0; border-top: 10px solid #078AF0; border-right: 40px solid #078AF0; border-bottom: 10px solid #078AF0; border-left: 40px solid #078AF0; margin-bottom: 5px; font-weight: bold;">confirm reception</a>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection
