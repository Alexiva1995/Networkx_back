@extends('mails.layout')

@section('message-content')
    <tr>
        <td bgcolor="#ffffff" style="padding: 10px 40px 5px 40px; text-align:center;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                <tr>
                    <td>
                        <h1 style="font-size: 20px; margin-bottom: 15px;">THIS IS YOUR SECURITY CODE</h1>
                        @if (isset($code))
                            <span style="font-size: 18px;">
                                {{ $code }}
                            @else
                                <span style="padding-bottom: 10px; font-size: 18px; color: #e64e4e;">
                                    Error when generating the code</span>
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection
