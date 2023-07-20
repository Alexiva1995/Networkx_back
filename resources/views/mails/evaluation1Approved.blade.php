@extends('mails.layout')

@section('message-content')
    <tr>
        <td bgcolor="#ffffff" style="padding: 10px 40px 5px 40px; text-align:center;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                <tr>
                <div style="margin-bottom: 10px; text-align: start;"> 
                    <h1 style="font-size: 18px; text-align: center;">Unlock Your Potential in Phase 1: Stand Out, Shine, and Fund Your Dreams!</h1>
                    <p style="font-size: 14px;">Hello {{ $data['user'] }},</p>
                    <p style="font-size: 14px;"> 
                        This program is a unique opportunity to demonstrate your worth and stand out in our team. We know that you are capable of facing any challenge that comes your way and we are excited to see your results in this phase.
                    </p>
                    <p style="font-size: 14px;"> 
                        We wish you the best of luck during Phase 1 and look forward to seeing you shine.<br>
                        If you have any questions or concerns, please contact us via support, we are here to help you.<br>
                        Congratulations again and go ahead!
                    </p>
                    <p style="font-size: 14px;">
                        FUND YOUR TRADES FUND YOUR DREAMS
                    </p>
                </div>
                </tr>
            </table>
        </td>
    </tr>
@endsection
