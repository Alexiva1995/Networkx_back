@extends('mails.layout')

@section('message-content')
    <tr>
        <td bgcolor="#ffffff" style="padding: 0 40px 40px 40px; text-align:center;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                style="border-collapse: collapse;">
                <tr>
                    <td>
                        <p style="font-size: 18px; margin-bottom: 12px; text-align: start;">FYT Pay Transaction</p>
                        <p style="font-size: 18px; margin-bottom: 12px; text-align: start;">The following payment has been made from your FYT Pay</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="margin: 20px 0; border-radius: 10px; background: #f8f8f8; padding: 10px;">
                            <table style="width: 100%;">
                                <tr>
                                    <td style="text-align: start">Date</td>
                                    <td style="text-align: end">{{ $date }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: start">Amount</td>
                                    <td style="text-align: end">{{ $amount }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: start">Wallet</td>
                                    <td style="text-align: end">{{ $wallet }}</td>
                                </tr>
                            </table>
                        </div>
                        <div>
                            <a href="{{config('services.url_front.url').'mail-verify'}}" target="_blank"
                            style="font-family: Poppins, sans-serif; box-sizing: align-items: center; border-box; border-radius: 10px; color: #fff; display: inline-block; text-decoration: none; background-color: #078AF0; border-top: 10px solid #078AF0; border-right: 40px solid #078AF0; border-bottom: 10px solid #078AF0; border-left: 40px solid #078AF0; margin-bottom: 5px; font-weight: bold;">View your transaction history in FYT</a>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection
