@extends('mails.layout')

@section('message-content')
  <tr>
    <td bgcolor="#ffffff" style="padding: 30px; text-align:center;">
      <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
        <tr>
          <td>
            <p style="text-align: start; font-weight: 600; font-size: 18px;">
              Hello, A password reset was requested for your 
              <a style="text-decoration: underline; color:#46087d;">{{ $email }}</a> account, 
              click on the button that appears below to change your password.
            </p>
          </td>
        </tr>
        <tr>
          <td>
            <p style="margin: 20px auto 10px auto; font-size: 12px; ">Verification token:</p>
            <p style="margin: 10px auto; font-size: 18px; font-weight: bold;">{{ $token }}</p>
            <a href="{{ config("services.url_front.url")."update-password" }}" target="_blank"
              style="border-radius: 10px; color: #fff; display: inline-block; text-decoration: none; 
              background-color: #46087d; padding: 10px 28px;">
              Change password
            </a>
          </td>
        </tr>
      </table>
    </td>
  </tr>
@endsection