<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordNotification extends Mailable
{
    use Queueable, SerializesModels;
    protected $email;
    protected $token;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'))
                    ->view('mails.forgotPasswordNotification')
                    ->subject('Forgot Password Notification Email')
                    ->with([
                        'email' => $this->email,
                        'token' => $this->token
                    ]);
    }
}
