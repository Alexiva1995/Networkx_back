@extends('mails.layout')

@section('message-content')
    <tr>
        <td bgcolor="#ffffff" style="padding: 10px 40px 5px 40px; text-align:center;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                <tr>
                <div style="margin-bottom: 10px; text-align: start;">
                    <h1 style="font-size: 18px">WELCOME TO BUSINESS TO BUSINESS (B2B)</h1>
                    <p style="font-size: 14px;">
                        Hello Dear {{ $data['user'] ?? 'user' }}, receive a warm greeting.
                    </p>
                    <p style="font-size: 14px;">
                        Congratulations on making the best decision and becoming
                        a part of team.
                    </p>
                    <p style="font-size: 14px;">We hope you achieve the best performance with us.</p>
                    <p style="font-size: 14px;">Thank you for your attention.</p>
                </div>
                </tr>
            </table>
        </td>
    </tr>
@endsection
