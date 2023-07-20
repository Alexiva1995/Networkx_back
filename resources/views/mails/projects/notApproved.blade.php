@extends('mails.layout')

@section('message-content')
<tr>
    <td bgcolor="#ffffff" style="padding: 30px; text-align:center;">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
            <tr>
                <td>
                    <h1 style="font-size: 20px; margin-bottom: 20px;">Hello, {{ $data['user']->name }}!</h1>
                    <p style="">We regret to inform you that your account {{ $data['package'] }} - {{ $data['account'] }} has been declined, please renew.</p>
                </td>
            </tr>
        </table>
    </td>
</tr>
@endsection
