<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderReceivedEmail extends Mailable
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
        return $this->view('mail.main.order-received-notification')
            ->text('mail.main.order-received-notification-plain')
            ->subject('Order Received from '.$this->sales->customer_name);
    }
}
