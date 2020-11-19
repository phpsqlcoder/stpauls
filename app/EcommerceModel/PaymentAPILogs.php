<?php

namespace App\EcommerceModel;

use Illuminate\Database\Eloquent\Model;

class PaymentAPILogs extends Model
{
    protected $table = 'payment_api_logs';

    protected $fillable = ['website_transaction_id_exists', 'website_transaction_id', 'merchant_transaction_id',
        'reference_number', 'reason_code', 'customer_name', 'customer_email', 'amount', 'response_data'];

}
