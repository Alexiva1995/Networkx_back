@extends('mails.layout')

@section('message-content')
    <tr>
        <td bgcolor="#ffffff" style="padding: 10px 40px 5px 40px; text-align:center;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                <tr>
                <div style="margin-bottom: 10px; text-align: start;"> 
                    <h1 style="font-size: 18px; text-align: center;">CONGRATULATIONS! YOU HAVE PASSED OUR EVALUATION CHALLENGE PHASE 2 (FYT)</h1>
                    <p style="font-size: 14px;">Hello {{ $data['user'] }},</p>
                    <p style="font-size: 14px;"> 
                        Congratulations on advancing to Phase 2 of this program! You have demonstrated your worth in Phase 1, and we are confident that you will continue to stand out in our team. This is a unique opportunity to showcase your skills and keep growing in this project.
                    </p>
                    <p style="font-size: 14px;">
                        We know that you are capable of facing any challenge that comes your way in Phase 2, and we are excited to see your results. We wish you the best of luck during this stage and look forward to seeing you shine once again. 
                    </p>
                    <p style="font-size: 14px;">
                        If you have any questions or concerns, please contact us via support, we are here to help you. <br>
                        Congratulations again and keep going!
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