<?php

namespace App\Http\Controllers\EcommerceControllers;

use App\EcommerceModel\PaymentAPILogs;
use App\EcommerceModel\SalesHeader;
use App\EcommerceModel\SalesPayment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentAPIController extends Controller
{
    /**
     * @param Request $request
     */
    public function merchant_post(Request $request)
    {
        $apiLogs = [];
        $apiLogs['website_transaction_id'] = $request->has('req_transaction_uuid') ? $request->req_transaction_uuid : '';
        $apiLogs['merchant_transaction_id'] = $request->has('transaction_id') ? $request->transaction_id : '';
        $apiLogs['reference_number'] = $request->has('req_reference_number') ? $request->req_reference_number : '';
        $apiLogs['reason_code'] = $request->has('reason_code') ? $request->reason_code : '';
        $apiLogs['customer_name'] = $request->has('req_bill_to_forename') ? $request->req_bill_to_forename : '';
        $apiLogs['customer_name'] = $request->has('req_bill_to_surname') ? $apiLogs['customer_name'].' '.$request->req_bill_to_surname : $apiLogs['customer_name'];
        $apiLogs['customer_email'] = $request->has('req_bill_to_email') ? $request->req_bill_to_email : '';
        $apiLogs['amount'] = $request->has('auth_amount') ? $request->auth_amount : '';
        $apiLogs['response_data'] = json_encode($request->all());

        if($request->has('req_transaction_uuid')) {
            $salesHeader = SalesHeader::where('order_number', $request->req_transaction_uuid)->first();
            $apiLogs['website_transaction_id_exists'] = !empty($salesHeader);

            if($request->has('decision') && $request->decision == 'ACCEPT') {
                if ($salesHeader) {
                    $salesHeader->update([
                        'response_code' => $apiLogs['reason_code'],
                        'payment_status' => 'PAID',
                        'delivery_status' => 'WAITING FOR VALIDATION',
                        'is_approve' => null
                    ]);

                    SalesPayment::create([
                        'sales_header_id'=> $salesHeader->id,
                        'payment_type'=> 'Card',
                        'amount'=> $salesHeader->net_amount,
                        'status'=> 'PAID',
                        'payment_date'=> date('Y-m-d'),
                        'receipt_number'=> $apiLogs['merchant_transaction_id'],
                        'created_by'=> $salesHeader->customer_id,
                        'created_at'=> date('Y-m-d H:i:s'),
                        'is_verify'=> null
                    ]);
                }
            } else {
                if ($salesHeader) {
                    $salesHeader->update(['response_code' => $apiResponse['reason_code']]);
                }
            }
        } else {
            $apiLogs['website_transaction_id_exists'] = 0;
        }

        PaymentAPILogs::create($apiLogs);
    }

    /**
     * @param Request $request
     */
    public function landing_page(Request $request)
    {
        $redirectPath = '/';

        if($request->has('decision') && $request->decision == 'ACCEPT') {
            $transactionId = $request->has('req_transaction_uuid') ? $request->req_transaction_uuid : '/';
            $redirectPath = '/order-received/'.$transactionId;
        } else {
            $transactionId = $request->has('req_transaction_uuid') ? $request->req_transaction_uuid : '/';
            $reasonCode = $request->has('reason_code') ? '/'.$request->reason_code : '';
            $redirectPath = '/payment-failed/'.$transactionId.$reasonCode;
        }

        return redirect()->to(url('/').$redirectPath);
    }
}
