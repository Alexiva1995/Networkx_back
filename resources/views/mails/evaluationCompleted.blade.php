@extends('mails.layout')

@section('message-content')
    <tr>
        <td bgcolor="#ffffff" style="padding: 10px 40px 5px 40px; text-align:center;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                <tr>
                <div style="margin-bottom: 10px; text-align: start;"> 
                    <h1 style="font-size: 18px; text-align: center;">CONGRATULATIONS! YOU HAVE COMPLETED OUR EVALUATION CHALLENGE (FYT)</h1>
                    <p style="font-size: 14px;">Hello {{ $data['user'] }},</p>
                    <p style="font-size: 14px;"> 
                        Congratulations on reaching the FUNDED  account in this program! You have demonstrated your worth and stood out in the phase 1 and 2 test phases, and we are excited to see you now in action in the real account.
                    </p>
                    <p style="font-size: 14px;"> 
                        We know that you are an experienced trader capable of handling any challenge that comes your way. This is the moment to put your skills to the test and continue growing in this project.
                        We wish you the best of luck and look forward to seeing your results in the FUNDED  account. If you have any questions or concerns, do not hesitate to contact us, we are here to help you.
                    </p>
                    <p style="font-size: 14px;">
                        Congratulations  FUNDED TRADER !
                    </p>
                    <p style="font-size: 14px;">
                        FUND YOUR TRADES FUND YOUR DREAMS!
                    </p>
                </div>
                </tr>
            </table>
        </td>
    </tr>
@endsection
