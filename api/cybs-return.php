<?php

session_start();

include('config.php');


$apiRespone = [];
foreach($_REQUEST as $name => $value) {
    $apiRespone[$name] = $value;
}

if(isset($apiRespone['decision']) && $apiRespone['decision'] == 'ACCEPT') {
    echo "success";

    $siteName = 'ST PAULS Online | Catholic Online Bookstore in Philippines';
    $livesitePath = 'http://localhost:8000';
    $tn = $apiRespone['req_transaction_uuid'];

    // function getProductImageMaxWidth($id){

    //     //pdo connection
    //     global $pdo;

    //     //query
    //     $sql = $pdo->prepare("SELECT `path` FROM product_photos WHERE is_primary = 1 AND product_id=:id");
    //     $sql->execute(array(':id' => $id));

    //     //do the loop and return
    //     foreach($sql as $sql => $rs)
    //         return !empty($rs->thumb) ? '<img src="' .$livesitePath . '/products/' . $rs['path'] . '" style="max-width:100%;" />' : '';

    // }

    $transaction = '';
    //transaction details
    $sqlTra = $pdo->prepare("SELECT * FROM ecommerce_sales_headers WHERE order_number=:order_number");
    $sqlTra->execute(array(':order_number' => $tn));

    $transaction = $sqlTra->fetch();

    if($transaction['branch'] == ''){
        $branch = '';
    } else {
        $branch = ': '.$transaction['branch'];
    }

    if($transaction['payment_method'] == 0){
        $paymenttype = 'Cash';
    } elseif($transaction['payment_method'] == 1) {
        $paymenttype = 'Card Payment';
    } else {
        $paymenttype = $sales->payment_option;
    }


    $user = '';
    //transaction details
    $sqlUser = $pdo->prepare("SELECT * FROM users WHERE id=:customerid");
    $sqlUser->execute(array(':customerid' => $transaction['customer_id']));

    $user = $sqlUser->fetch();


    $settings = '';
    $sqlSetting = $pdo->prepare("SELECT * FROM settings WHERE id=:id");
    $sqlSetting->execute(array(':id' => 1));

    $settings = $sqlSetting->fetch();


    //member information
    $profile = '';
    $sqlMem = $pdo->prepare("SELECT * FROM customers WHERE customer_id=:id");
    $sqlMem->execute(array(':id' => $transaction['customer_id']));

    $profile = $sqlMem->fetch();


    //activate transaction
    $payment = $pdo->prepare("INSERT INTO ecommerce_sales_payments (sales_header_id, payment_type, amount, status, payment_date, receipt_number, created_by, created_at, is_verify) VALUES (:header_id, :payment_type, :amount, :status, :payment_date, :receipt_number, :created_by, :created_at, :isverify)");
    $payment->execute([
        'header_id' => $transaction['id'],
        'payment_type' => 'Card',
        'amount' => $transaction['net_amount'],
        'status' => 'PAID',
        'payment_date' => date('Y-m-d'),
        'receipt_number' => $apiRespone['transaction_id'],
        'created_by' => $transaction['customer_id'],
        'created_at' => date('Y-m-d H:i:s'),
        'isverify' => 1
    ]);

    $sql = $pdo->prepare("UPDATE ecommerce_sales_headers SET payment_status='PAID', delivery_status='Scheduled for Processing', status='PAID', is_approve=1 WHERE order_number=:order_number");
    $sql->execute(array(':order_number' => $tn));

    //e-mail sending
    $msg = '
        <html>
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>' . $siteName . '</title>
        </head>
        <body style="background:#f4f4f4;font-family:arial;">
        <p>&nbsp;</p>
        <table style="width:750px;margin:auto;background:#fff;border:1px solid #dddddd;padding:1em;-webkit-border-radius:5px;border-radius:5px;font-size:12px;">
            <tr>
                <td><a href="'. $livesitePath .'"><img src="' . $livesitePath . '/storage/logos/' . $settings['company_logo'] . '" /></a></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                    Dear ' . $profile['firstname'] . ' ' . $profile['lastname'] . '
                    <br />
                    <br />
                    Thank you for shopping at ' . $siteName . '. We are glad that you found what you were looking for.
                    <br />
                    <br />
                    Your order number is ' . $tn . '.
                    <br />
                    <br />
                    Please find the details of your order below, or view it in your <a href="' . $livesitePath . '/account/my-orders">account</a>.
                    
                    <div style="overflow:auto;margin-bottom:20px;">
                        <div>
                        </div>

                        <div id="invoice-1">
                            <small>Order Number</small><br/><span style="color:#b82e24;font-size: 2rem;">'.$tn.'</span>
                        </div>
                    </div>


                    <div style="margin-bottom: 200px;">
                        <div id="customer" style="flex: 0 0 40%;max-width: 100%;">
                            <label style="display: inline-block;margin-bottom: 0.5rem; font-family: -apple-system, BlinkMacSystemFont, 'Inter UI', Roboto, sans-serif;font-weight: 500;letter-spacing: 0.5px;color: #8392a5;">Billing Details</label>
                            <h2 class="name">'.$transaction['customer_name'].'</h2>
                            '.$transaction['customer_delivery_adress'].'<br/>
                            '.$transaction['customer_contact_number'].'<br/>
                            <a href="mailto:'.$user['email'].'">'.$user['email'].'</a><br/><br/>
                            Remarks : '.$transaction['remarks'].'
                        </div>

                        <div id="invoice" style="flex: 0 0 60%;max-width: 100%;">
                            <label style="display: inline-block;margin-bottom: 0.5rem; font-family: -apple-system, BlinkMacSystemFont, 'Inter UI', Roboto, sans-serif;font-weight: 500;letter-spacing: 0.5px;color: #8392a5;">Order Details</label><br/>
                            Order Date <span style="float: right;">'.date('m/d/Y h:i A',strtotime($transaction['created_at'])).'</span><br/>
                            Payment Method <span style="float: right;">'.$paymenttype.'</span><br/>
                            Payment Status <span style="float: right;color:#10b759;font-weight: 600;">'.$transaction['payment_status'].'</span><br/>
                            <hr>
                            Delivery Type <span style="float: right;text-transform: uppercase;">'.$transaction['delivery_type'].'</span><br/>
                            Branch <span style="float: right;">'.($transaction['branch'] == '') ? 'N/A' : $transaction['branch'].'</span><br/>
                            Delivery Status <span style="float: right;color:#10b759;font-weight: 600;text-transform: uppercase;">'.$transaction['delivery_status'].'</span>
                        </div>
                    </div>




                    <table border="0" cellspacing="0" cellpadding="0">
                        <thead style="background:#b81600;">
                            <tr style="color:white;">
                                <th width="30%" style="text-align: left;">Item(s)</th>
                                <th width="10%" style="text-align: right;">Weight (kg)</th>
                                <th width="10%" style="text-align: right;">Price (₱)</th>
                                <th width="10%" style="text-align: right;">Quantity</th>
                                <th width="20%" style="text-align: right;">Total Weight (g)</th>
                                <th width="20%" style="text-align: right;">Total (₱)</th>
                            </tr>
                        </thead>
                        <tbody>
    ';

    				$totalWeight = 0;
    				$total = 0;
                    $sql = $pdo->prepare("SELECT * FROM ecommerce_sales_details WHERE sales_header_id=:header_id");
                    $sql->execute(array(':header_id' => $transaction['id']));
                    foreach($sql as $sql => $rs){

                    	$product = '';
					    $sqlProduct = $pdo->prepare("SELECT * FROM products WHERE id=:id");
					    $sqlProduct->execute(array(':id' => $rs['product_id']));

					    $product = $sqlProduct->fetch();

                        $msg .= '
                            <tr>
                                <td>' . $rs['product_name'] . '</td>
                                <td style="text-align: right;">' . ($product['weight']/1000) . '</td>
                                <td style="text-align: right;">' . number_format($rs['price'], 2) . '</td>
                                <td style="text-align: right;">' . number_format($rs['qty']) . '</td>
                                <td style="text-align: right;">' . number_format(($product['weight'] * $rs['qty'])/1000) . '</td>
                                <td style="text-align: right;">' . number_format($rs['price'] * $rs['qty'], 2) . '</td>
                            </tr>
                        ';

                            $totalWeight += $product['weight'] * $rs['qty'];
                            $total += $rs['price'] * $rs['qty'];


                    }

                    $totalWeight = round($totalWeight / 1000, 1);
                    if(is_float($totalWeight)){
                        $x = explode('.', $totalWeight);
                        if($x[1]>5)
                            $totalWeight = $x[0] + 1;
                        elseif($x[1]>0 && $x[1]<5)
                            $totalWeight = $x[0] + .5;
                    }

    $msg .= '
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" rowspan="3"></td>
                                <td>Total Weight</td>
                                <td style="text-align: right;">' . $totalWeight . ' kg</td>
                            </tr>
                            <tr>
                                <td>Subtotal</td>
                                <td style="text-align: right;">' . number_format($total, 2) . '</td>
                            </tr>
                            <tr>
                                <td>Shipping Fee</td>
                                <td style="text-align: right;">'.number_format($transaction['delivery_fee_amount'],2).'</td>
                            </tr>
                            <tr>
                                <td colspan="4" rowspan="3">
                                    <div class="col-sm-12 col-lg-8 order-2 order-sm-0 mg-t-40 mg-sm-t-0">
                                        <div class="gap-30"></div>
                                        <label style="display: inline-block;margin-bottom: 0.5rem; font-family: -apple-system, BlinkMacSystemFont, 'Inter UI', Roboto, sans-serif;font-weight: 500;letter-spacing: 0.5px;color: #8392a5;">Other Instructions</label>
                                        <p>'.($transaction['other_instruction'] == '') ? 'N/A' : $transaction['other_instruction'].'</p>
                                    </div>
                                </td>
                                <td>Service Fee</td>
                                <td style="text-align: right;">'.number_format($transaction['service_fee'],2).'</td>
                            </tr>
                            <tr>
                                <td>Loyalty Discount</td>
                                <td style="text-align: right;">'.number_format($transaction['discount_amount'],0).'%</td>
                            </tr>
                            <tr>
                                <td><span style="color:#10b759;font-size: 1.09375rem;">Grand Total</span></td>
                                <td style="text-align: right;"><span style="font-size: 1.09375rem;">'.number_format($transaction['net_amount'],2).'</span></td>
                            </tr>
                        </tfoot>
                    </table>
                    <br />
                    <br />
        ';

    $msg .= '
                    Respectfully yours,<br />
                    Your ' . $siteName . ' family
                    <br />
                    <br />
                    <small style="color:red">This is an auto-generated registration notification, please do not reply. This communication is intended solely for the use of the addressee and authorized recipients. It may contain confidential or legally privileged information and is subject to the conditions in <a href="' . $livesitePath . '">' . $livesitePath . '</a></small>
                </td>
            </tr>
        </table>
        <p style="text-align:center;font-size:11px;color:#999999;">Copyright &copy; '.date('Y').' <a href="'. $livesitePath .'">'.$siteName.'</a>. All rights reserved.</p>
        <p>&nbsp;</p>
        </body>
        </html>
    ';

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
    $subject = "Confirmation of order from ".$siteName;
    $subject1 = "Copy of Confirmation Order. Transaction #:".$apiRespone['req_transaction_uuid'];
    $headers .= "From: " . $siteName . " <no-reply@" . $_SERVER['HTTP_HOST'] . ">\r\n";

    mail($apiRespone['req_bill_to_email'],$subject,$msg,$headers);

    $_SESSION['order_success'] = "You've successfully placed the order # ".$apiRespone['req_transaction_uuid']."";

    header('location:'.$livesitePath.'/account/my-orders');

} else {
    echo "failed";

    // $bankError = [201, 203, 204, 205, 208, 210, 211];
    // if(isset($apiRespone['decision']) && $apiRespone['decision'] == 'CANCEL') {
    //     $responseMessage = 'Transaction (ID: '. $apiRespone['req_transaction_uuid'] .') was cancelled.';
    // } else if(isset($apiRespone['reason_code']) && in_array($apiRespone['reason_code'], $bankError)) {
    //     $responseMessage = 'Transaction (ID: '. $apiRespone['req_transaction_uuid'] .') rejected, please contact your bank.';
    // } else {
    //     $responseMessage = 'Transaction (ID: '. $apiRespone['req_transaction_uuid'] .') unsuccessful, please try again.';
    // }

    // $_SESSION['errmsg'] = $responseMessage;
    // header('location:'.$livesitePath.'cart/checkout/failed/');

}
?>