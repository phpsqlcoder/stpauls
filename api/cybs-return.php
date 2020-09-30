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

    function getProductImageMaxWidth($id){

        //pdo connection
        global $pdo;

        //query
        $sql = $pdo->prepare("SELECT `path` FROM product_photos WHERE is_primary = 1 AND product_id=:id");
        $sql->execute(array(':id' => $id));

        //do the loop and return
        foreach($sql as $sql => $rs)
            return !empty($rs->thumb) ? '<img src="' .$livesitePath . '/products/' . $rs['path'] . '" style="max-width:100%;" />' : '';

    }

    $transaction = '';
    //transaction details
    $sqlTra = $pdo->prepare("SELECT * FROM ecommerce_sales_headers WHERE order_number=:order_number");
    $sqlTra->execute(array(':order_number' => $tn));

    if($transaction['branch' == '']){
        $branch = '';
    } else {
        $branch = ': '.$transaction['branch'];
    }

    $transaction = $sqlTra->fetch();

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
        <table style="width:580px;margin:auto;background:#fff;border:1px solid #dddddd;padding:1em;-webkit-border-radius:5px;border-radius:5px;font-size:12px;">
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
                    Your transaction number is ' . $tn . '.
                    <br />
                    <br />
                    Please find the details of your order below, or view it in your <a href="' . $livesitePath . '/account/my-orders">account</a>.
                    <br />
                    <br />
                    <table width="100%">
                        <thead style="background:#DCEFF5;">
                            <tr>
                                <th style="padding:.5em;">Item(s)</th>
                                <th style="padding:.5em; text-align:center;">Weight (g)</th>
                                <th style="padding:.5em; text-align:center;">Price (₱)</th>
                                <th style="padding:.5em; text-align:center;">Quantity</th>
                                <th style="padding:.5em; text-align:center;">Total Weight (g)</th>
                                <th style="padding:.5em; text-align:center;">Total (₱)</th>
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
                                <td style="padding:.5em;">
                                    <div style="width:15%; float:left;">' . getProductImageMaxWidth($rs['product_id']) . '</div>
                                    <div style="width:80%; float:right;">
                                        ' . $rs['product_name'] . '
                                        ' . (!empty($options) ? '<small><table>' . $options . '</table></small>' : '' ) . '
                                    </div>
                                </td>
                                <td style="padding:.5em; text-align:center;">' . $product['weight'] . '</td>
                                <td style="padding:.5em; text-align:center;">' . number_format($rs['price'], 2) . '</td>
                                <td style="padding:.5em; text-align:center;">' . number_format($rs['qty']) . '</td>
                                <td style="padding:.5em; text-align:center;">' . number_format($product['weight'] * $rs['qty']) . '</td>
                                <td style="padding:.5em; text-align:right;">' . number_format($rs['price'] * $rs['qty'], 2) . '</td>
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
                                <td colspan="3" style="text-align:right;"><strong>Total Weight</strong></td>
                                <td colspan="3" style="text-align:right;">' . $totalWeight . ' kg</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align:right;"><strong>Subtotal</strong></td>
                                <td colspan="3" style="text-align:right;">₱ ' . number_format($total, 2) . '</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align:right;"><strong>Loyalty Discount</strong></td>
                                <td colspan="3" style="text-align:right;">₱ - ' . number_format($transaction['discount_amount'], 2) . '</td>
                            </tr>
        ';

    $msg .= '
                            <tr>
                                <td colspan="3" style="text-align:right;"><strong>Shipping Rate</strong></td>
                                <td colspan="3" style="text-align:right;">₱ ' . number_format($transaction['delivery_fee_amount'], 2) . '</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align:right;"><strong>Service Fee</strong></td>
                                <td colspan="3" style="text-align:right;">₱ ' . number_format($transaction['service_fee'], 2) . '</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align:right;"><strong>Grand Total</strong></td>
                                <td colspan="3" style="text-align:right;">₱ ' . number_format($transaction['net_amount'], 2) . '</td>
                            </tr>
                        </tfoot>
                    </table>
                    <br />
                    <br />
    ';

    $msg .= '
                    <strong>Your shipping details are as follows:</strong><br />
                    <table width="100%">
                        <tr>
                            <td>
                                <strong>BILLING INFORMATION</strong><br />
                                ' . $transaction['customer_name'] . '<br />
                                ' . $transaction['customer_delivery_adress'] . '
                            </td>
                            <td>
                                <strong>DELIVERY INFORMATION</strong><br />
                                ' . $transaction['delivery_type'] .$branch.'<br />
                                ' . $transaction['customer_delivery_adress'] . '
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>PAYMENT METHOD</strong><br />
                                BPI
                            </td>
                        </tr>
                    </table>
                    <br />
                    <br />
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
    //mail($apiRespone['req_bill_to_email'],$subject1,$msg,$headers);
    //mail($apiRespone['req_bill_to_email'],"Order for Pickup. Transaction #:".$apiRespone['req_transaction_uuid'],$msg,$headers);

    //delete unsuccessfull transactions
    // $sql = $pdo->prepare("SELECT transaction_number FROM tbl_transactions WHERE active=0 AND member_id=:member_id");
    // $sql->execute(array(':member_id' => $profile['id']));
    // foreach($sql as $sql => $rs){
    //     $pdo->query("DELETE FROM tbl_delivery_address WHERE transaction_number='".$rs->transaction_number."'");
    //     $pdo->query("DELETE FROM tbl_transaction_cart WHERE transaction_number='".$rs->transaction_number."'");
    //     $pdo->query("DELETE FROM tbl_transactions WHERE transaction_number='".$rs->transaction_number."'");
    // }


    // $rawTotal = ($transaction['total'] - $transaction['shippingrate']) + $transaction['discount'];
    // $rewardSql = $pdo->prepare("Select * From tbl_reward_points order by minimum_purchased desc");
    // $rewardSql->execute();
    // foreach($rewardSql as $rewardSql => $rdata){
    //     if($rdata->minimum_purchased <= $rawTotal){
    //         $points = $rdata->points;

    //         break;
    //     }
    // }

    //update reward
    // $sql = $pdo->prepare("UPDATE tbl_member SET rewards = rewards + :rewards WHERE id=:id");
    // $sql->execute(array(
    //     ':rewards'	=> (int)$points,
    //     ':id'		=> $profile['id']
    // ));

    //update coupon code
    // $sql = $pdo->prepare("UPDATE tbl_eproduct_coupons SET quantity=quantity-1 WHERE coupon=(SELECT coupon FROM tbl_transactions WHERE transaction_number=:transaction_number AND active=1)");
    // $sql->execute(array(':transaction_number' => $transaction['transaction_number']));
    // unset($_SESSION['mj_'.md5($siteName).'_couponCode']);
    // unset($_SESSION['mj_'.md5($siteName).'_discount']);

    //empty cart
    // $pdo->query("DELETE FROM tbl_cart WHERE user_id=".$transaction['member_id']);

    //updateInventoryWeb($transaction['transaction_number']);

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