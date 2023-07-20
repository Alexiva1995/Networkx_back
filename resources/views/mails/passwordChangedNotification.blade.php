@extends('mails.layout')

@section('message-content')
    <tr>
        <td bgcolor="#ffffff" style="padding: 0 40px 40px 30px; text-align:center;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                style="border-collapse: collapse;">
                <tr>
                    <td>
                        <div style="text-align: center; margin-top: 1em">
                            <h1 style="margin-top: 1em; text-transform: uppercase">Congratulations!</h1>
                            <p>Your password has been successfully changed!</p>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection
