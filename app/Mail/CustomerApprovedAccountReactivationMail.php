<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerApprovedAccountReactivationMail extends Mailable
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
        return $this->view('mail.customer.approved-reactivate-account')
            ->text('mail.customer.approved-reactivate-account-plain')
            ->subject('Reactivation Request - Approved');
    }
}