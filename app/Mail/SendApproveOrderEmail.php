<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendApproveOrderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $setting;
    public $sales;
    public $date;

    /**
     * Create a new message instance.
     *
     * @param $setting
     * @param $customer
     * @param $token
     */
    public function __construct($setting,$sales,$date)
    {
        $this->setting  = $setting;
        $this->sales    = $sales;
        $this->date     = $date;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.main.order-approved-notification')
            ->text('mail.main.order-approved-notification-plain')
            ->subject('Order #'.$this->sales->order_number.' from '.$this->sales->customer_name.' has been approved');
    }
}
