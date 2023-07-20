@extends('mails.layout')

@section('message-content')
    <tr>
        <td bgcolor="#ffffff" style="padding: 10px 40px 5px 40px; text-align:center;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                <tr>
                <div style="font-size: 16px; margin-bottom: 10px; text-align: start;"> 
                    <h1 style="font-size: 18px; text-align: center;">UNSUCCESSFUL TEST (FYT)</h1>
                    <p style="font-size: 14px">Hello {{ $data['user'] }},</p>
                    <p style="font-size: 14px"> 
                        We are sorry to inform you that your attempt at the <span style="text-transform: uppercase">{{ $data['program'] }} CHALLENGE</span> did not meet the program's requirements. 
                        We appreciate your interest in our program and encourage you to try again in the future. 
                    </p>
                    @if ($data['description'] != '')
                    <h4 style="font-size: 14px margin-bottom:0;">Description:</h4>
                    <p style="font-size: 14px">{{ $data['description'] }}</p>
                    @endif
                    <p style="font-size: 14px">Thank you for considering our program.</p>
                </div>
                </tr>
            </table>
        </td>
    </tr>
@endsection