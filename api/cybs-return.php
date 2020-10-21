<?php

session_start();

include('config.php');


$apiRespone = [];
foreach($_REQUEST as $name => $value) {
    $apiRespone[$name] = $value;
}

if(isset($apiRespone['decision']) && $apiRespone['decision'] == 'ACCEPT') {

    $livesitePath = 'https://beta.stpauls.ph/public';
    $tn = $apiRespone['req_transaction_uuid'];

    $transaction = '';
    //transaction details
    $sqlTra = $pdo->prepare("SELECT * FROM ecommerce_sales_headers WHERE order_number=:order_number");
    $sqlTra->execute(array(':order_number' => $tn));

    $transaction = $sqlTra->fetch();


    $sql = $pdo->prepare("UPDATE ecommerce_sales_headers SET response_code=:responsecode, payment_status='PAID', delivery_status='Scheduled for Processing', is_approve=1 WHERE order_number=:order_number");
    $sql->execute(array(':order_number' => $tn, ':responsecode' => $apiRespone['reason_code']));

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

    header('location:'.$livesitePath.'/order-received/'.$tn);

} else {


    $tn = $apiRespone['req_transaction_uuid'];
    $sql = $pdo->prepare("UPDATE ecommerce_sales_headers SET response_code=:responsecode WHERE order_number=:order_number");
    $sql->execute(array(':order_number' => $tn, ':responsecode' => $apiRespone['reason_code']));

    header('location:'.$livesitePath.'/payment-failed/'.$tn.'/'.$apiRespone['reason_code']);

}
?>