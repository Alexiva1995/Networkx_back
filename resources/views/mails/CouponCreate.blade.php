@extends('mails.layout')

@section('message-content')
<tr>
    <td bgcolor="#ffffff" style="padding: 30px; text-align:center;">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
            <tr>
                <td>
                    <h1 style="font-size: 20px; margin-bottom: 15px;">Coupon code</h1>  
                    <span style="font-size: 18px;">
                        {{ $code }}      
                </td>
            </tr>
        </table>
    </td>
</tr>
@endsection