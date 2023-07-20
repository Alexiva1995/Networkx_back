@extends('mails.layout')

@section('message-content')
    <tr>
        <td bgcolor="#ffffff" style="padding: 10px 40px 5px 40px; text-align:center;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                <tr>
                <div style="margin-bottom: 10px; text-align: start;"> 
                    <h1 style="font-size: 18px; text-align: center;">ERROR! Automatic account creation failed</h1>
                    <p style="font-size: 14px;">User: {{ $data['user'] }},</p>
                    <p style="font-size: 14px;">Email: {{ $data['email'] }},</p>
                    <p style="font-size: 14px;"> 
                        ERROR, The purchase order for program {{$data['program']}} has been successfully approved but the account creation with balance has failed.
                    </p>
                    <p style="font-size: 14px;">
                        Check the app logs for more information.
                    </p>
                </div>
                </tr>
            </table>
        </td>
    </tr>
@endsection
