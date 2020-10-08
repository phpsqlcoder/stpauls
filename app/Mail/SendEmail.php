<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $setting;
    public $customer;
    public $sales;
    public $template;

    /**
     * Create a new message instance.
     *
     * @param $setting
     * @param $customer
     * @param $token
     */
    public function __construct($setting, $customer, $sales, $template)
    {
        $this->setting  = $setting;
        $this->customer = $customer;
        $this->template = $template;
        $this->sales    = $sales;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.main.email-notification')
            ->subject($this->template->subject);
    }
}
