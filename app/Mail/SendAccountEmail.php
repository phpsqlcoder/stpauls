<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAccountEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $setting;
    public $customer;
    public $template;

    /**
     * Create a new message instance.
     *
     * @param $setting
     * @param $customer
     * @param $token
     */
    public function __construct($setting, $customer, $template)
    {
        $this->setting  = $setting;
        $this->customer = $customer;
        $this->template = $template;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.main.account-notification')
            ->text('mail.main.account-notification-plain')
            ->subject($this->template->subject);
    }
}
