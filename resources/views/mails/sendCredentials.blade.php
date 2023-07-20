@extends('mails.layout')

@section('message-content')
    <tr>
        <td bgcolor="#ffffff" style="padding: 0 40px 40px 30px; text-align:center;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                <tr>
                    <h1 style="font-size: 18px; text-align: center;">
                        MT5 ACCOUNT ACCESS (FYT)
                    </h1>
                    <p style="text-align: start; font-size: 16px; margin-bottom: 0px; padding: 0px 0px 15px 15px;">
                        Hello {{ $data['user'] }}, We send you a friendly welcome.
                        The access details for your account are as follows:
                    </p>

                <tr>
                    <td>
                        <table style="margin: 0 auto; width: 500px; background-color:rgb(232,232,232)">
                            <tr>
                                <td style="text-align: start; font-size:14px; font-weight: bold;">Date:</td>
                                <td style="text-align: end; text-transform: capitalize;">{{ $data['date'] }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: start; font-size:14px; font-weight: bold;">Name:</td>
                                <td style="text-align: end;">{{ $data['name'] }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: start; font-size:14px; font-weight: bold;">Login ID:</td>
                                <td style="text-align: end;">{{ $data['login'] }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: start; font-size:14px; font-weight: bold;">Password:</td>
                                <td style="text-align: end; ">{{ $data['password'] }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: start; font-size:14px; font-weight: bold;">Leverage:</td>
                                <td style="text-align: end;">{{ $data['leverage'] }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: start; font-size:14px; font-weight: bold;">Balance:</td>
                                <td style="text-align: end;">{{ $data['balance'] }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: start; font-size:14px; font-weight: bold;">Server:</td>
                                <td style="text-align: end;">{{ $data['server'] }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection
