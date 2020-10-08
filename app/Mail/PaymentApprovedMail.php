<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $setting;
    public $customer;
    public $sales;

    /**
     * Create a new message instance.
     *
     * @param $setting
     * @param $customer
     * @param $token
     */
    public function __construct($setting, $customer, $sales)
    {
        $this->setting = $setting;
        $this->customer = $customer;
        $this->sales = $sales;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.order.email')
            ->text('mail.payment.payment-approved-plain')
            ->subject('Payment Approved');
    }
}
