@extends('mails.layout')

@section('message-content')
    <tr>
        <td bgcolor="#ffffff" style="padding: 10px 40px 5px 40px; text-align:center;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                <tr>
                <div style="font-size: 16px; margin-bottom: 10px; text-align: start;"> 
                    <p>
                        <h1 style="font-size: 18px; text-align: center;">REFUND FOR SUCCESSFUL TEST (FYT)</h1>
                    </p>
                    <p>
                        Dear {{ $data['user'] }},
                    </p>
                    <p> 
                        Your refund will be sent to the following wallet:
                    </p>

                    <tr>
                        <td>
                            <table style="padding: 5px 5px 5px; width: 500px; background-color:rgb(232,232,232)">
                                <tr>
                                    <td style="text-align: start; font-size:14px; font-weight: bold; text-transform: uppercase;">Login:</td>
                                    <td style="text-align: end; text-transform: capitalize;">{{ $data['name'] }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: start; font-size:14px; font-weight: bold; text-transform: uppercase;">Wallet:</td>
                                    <td style="text-align: end;">{{ $data['wallet'] }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: start; font-size:14px; font-weight: bold; text-transform: uppercase;">Amount:</td>
                                    <td style="text-align: end;">{{ $data['amount'] }}</td>
                                </tr>
                            </table>
                            <p style="text-align: start; font-size: 14px; padding: 10px;">
                                Don't you recognize this activity?
                            <a href="{{ config("services.url_front.url")."password-reset" }} " target="_blank" style="text-transform: capitalize; color: #078AF0;">Reset your password</a>
                            and contact
                            <a href="{{ config("services.url_front.url") }}" target="_blank" style="text-transform: uppercase; color: #078AF0;">
                                FYT support agent.</a>
                        </p>
                        </td>
                    </tr>
                </div>
                </tr>
            </table>
        </td>
    </tr>
@endsection