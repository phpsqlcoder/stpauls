<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPaymentApprovalEmail extends Mailable
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
        return $this->view('mail.main.payment-approval-notification')
            ->text('mail.main.payment-approval-notification-plain')
            ->subject('Payment Validation from '.$this->sales->customer_name);
    }
}
