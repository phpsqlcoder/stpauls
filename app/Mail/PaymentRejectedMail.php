<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $setting;
    public $customer;
    public $token;

    /**
     * Create a new message instance.
     *
     * @param $setting
     * @param $customer
     * @param $token
     */
    public function __construct($setting, $customer, $token)
    {
        $this->setting = $setting;
        $this->customer = $customer;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.payment.payment-rejected')
            ->text('mail.payment.payment-rejected-plain')
            ->subject('Payment Rejected');
    }
}
