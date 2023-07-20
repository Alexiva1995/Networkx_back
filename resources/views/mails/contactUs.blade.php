@extends('mails.layout')

@section('message-content')
    <tr>
        <td bgcolor="#ffffff" style="padding: 10px 40px; text-align:start;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                <tr>
                    <td>
                        <h1 style="font-size: 18px; margin-bottom: 20px;">Contact us message</strong></h1>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span style="font-size: 14px; margin-bottom: 10px;">Name: <strong>{{ ucwords(strtolower($data['name'])) }}</strong></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span style="font-size: 14px;">Email: <strong>{{ strtolower($data['email']) }}</strong></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h4 style="font-size: 16px; margin: 20px 0;">{{ ucfirst(strtolower($data['subject'])) }}</h4>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="font-size: 12px;">
                            {{ $data['message'] }}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection