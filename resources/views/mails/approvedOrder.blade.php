@extends('mails.layout')

@section('message-content')
    <tr>
        <td bgcolor="#ffffff" style="padding: 10px 40px 5px 40px; text-align:center;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                <tr>
                <div style="margin-bottom: 10px; text-align: start;"> 
                    <h1 style="font-size: 18px; text-align: center;">CONGRATULATIONS! Your order has been approved</h1>
                    <p style="font-size: 14px;">Hello {{ $data['user'] }},</p>
                    <p style="font-size: 14px;"> 
                        Congratulations, The purchase order for program {{$data['program']}} has been successfully approved
                    </p>
                    <p style="font-size: 14px;">
                        We hope that you will continue to uphold your record and make even 
                        greater strides towards improvement. Best of luck to you!
                    </p>
                </div>
                </tr>
            </table>
        </td>
    </tr>
@endsection
