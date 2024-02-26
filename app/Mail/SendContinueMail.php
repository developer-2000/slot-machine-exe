<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendContinueMail extends Mailable {
    use Queueable, SerializesModels;

    public $data;
    /**
     * Внести новые данные
     *
     * @return void
     */
    public function __construct($data) {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this
            ->subject('Create owner account')
            ->view('emails.send_continue_registration')
            ->with('data', $this->data);
    }
}
