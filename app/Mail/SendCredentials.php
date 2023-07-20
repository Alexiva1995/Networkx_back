<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendCredentials extends Mailable
{
    use Queueable, SerializesModels;
    protected $data1;
    protected $data2;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data1, $data2)
    {
        $this->data1 = $data1;
        $this->data2 = $data2;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'))
                    ->view('mails.sendCredentials')
                    ->subject('Send Credentials Email')
                    ->with([
                        'email' => $this->data1,
                        'token' => $this->data2
                    ]);
    }
}
