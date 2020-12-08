<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCODApprovalEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $setting;
    public $sales;

    /**
     * Create a new message instance.
     *
     * @param $setting
     * @param $customer
     * @param $token
     */
    public function __construct($setting,$sales)
    {
        $this->setting  = $setting;
        $this->sales    = $sales;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.main.cod-approval-notification')
            ->text('mail.main.cod-approval-notification-plain')
            ->subject('COD request for approval');
    }
}
