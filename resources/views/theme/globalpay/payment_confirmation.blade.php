<?php 

    define ('HMAC_SHA256', 'sha256');
	define ('SECRET_KEY', '18e445967c6b4a43896738dd7c77f461d7ecb48c77d947c7a05de785bd305ffa909a4be35bbe47a6876570e6dcd1b90957ee034ee9e24c75897dd0837a854e069be0863156dc4db59c53ad2cb127f47d843485b2158642bc86dd08f1c096cc835cd2f9f2fbf74f4fa6bc789e49806602f26c8c4c68694bb588c1915bf43cc57d');

    function sign ($params) {
      return signData(buildDataToSign($params), SECRET_KEY);
    }

    function signData($data, $secretKey) {
        return base64_encode(hash_hmac('sha256', $data, $secretKey, true));
    }

    function buildDataToSign($params) {
            $signedFieldNames = explode(",",$params["signed_field_names"]);
            foreach ($signedFieldNames as $field) {
               $dataToSign[] = $field . "=" . $params[$field];
            }
            return commaSeparate($dataToSign);
    }

    function commaSeparate ($dataToSign) {
        return implode(",",$dataToSign);
    }

    $apiFields['access_key'] = 'e913d4f812053133b5f8352d75183514';
	$apiFields['profile_id'] = 'F8483792-EA7A-436A-A67C-694200D49D55';
	$apiFields['transaction_uuid'] = $uniqID;
	$apiFields['signed_field_names'] = 'access_key,profile_id,transaction_uuid,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,reference_number,amount,currency';
	$apiFields['unsigned_field_names'] = 'bill_to_forename,bill_to_surname,bill_to_email,bill_to_address_line1,bill_to_address_line2,bill_to_address_city,bill_to_address_country,bill_to_address_state,bill_to_address_postal_code';
	$apiFields['bill_to_forename'] = $order['firstname'];
	$apiFields['bill_to_surname'] = $order['lastname'];
	$apiFields['bill_to_email'] = $order['email'];

	$apiFields['bill_to_address_line1'] = $address_line1;
	$apiFields['bill_to_address_line2'] = $address_line2;
	$apiFields['bill_to_address_city'] = $city;
	$apiFields['bill_to_address_country'] = 'PH';
	$apiFields['bill_to_address_state'] = $province;
	$apiFields['bill_to_address_postal_code'] = $zipcode;

	$apiFields['signed_date_time'] = gmdate("Y-m-d\TH:i:s\Z");
	$apiFields['locale'] = 'en';
	$apiFields['transaction_type'] = 'authorization';
	$apiFields['reference_number'] = time();
	$apiFields['amount'] = $order['net_amount'];
	$apiFields['currency'] = 'PHP';

?>

<html>
<head>
    <title>Secure Acceptance - Payment Form</title>
</head>
<body>
	<form id="globalpayForm" action="https://testsecureacceptance.cybersource.com/pay" method="post" style="display: none;">
		<input type="text" name="access_key" value="e913d4f812053133b5f8352d75183514">
    	<input type="text" name="profile_id" value="F8483792-EA7A-436A-A67C-694200D49D55">
	    <input type="text" name="transaction_uuid" value="<?php echo $uniqID; ?>">
	    <input type="text" name="signed_field_names" value="access_key,profile_id,transaction_uuid,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,reference_number,amount,currency">
	    <input type="text" name="unsigned_field_names" value="bill_to_forename,bill_to_surname,bill_to_email,bill_to_address_line1,bill_to_address_line2,bill_to_address_city,bill_to_address_country,bill_to_address_state,bill_to_address_postal_code">

	    <input type="text" name="bill_to_forename" value="Ryan">
	    <input type="text" name="bill_to_surname" value="Nolasco">
	    <input type="text" name="bill_to_email" value="<?php echo $order['email']; ?>">
	    <input type="text" name="bill_to_address_line1" value="<?php echo $address_line1; ?>">
	    <input type="text" name="bill_to_address_line2" value="<?php echo $address_line2; ?>">
	    <input type="text" name="bill_to_address_city" value="<?php echo $city; ?>">
	    <input type="text" name="bill_to_address_country" value="PH">
	    <input type="text" name="bill_to_address_state" value="<?php echo $province; ?>">
	    <input type="text" name="bill_to_address_postal_code" value="<?php echo $zipcode; ?>">
	    <input type="text" name="signed_date_time" value="<?php echo gmdate("Y-m-d\TH:i:s\Z"); ?>">
	    <input type="text" name="locale" value="en">

		<input type="text" name="transaction_type" value="authorization">
		<input type="text" name="reference_number" value="<?php echo time(); ?>">
		<input type="text" name="amount" value="<?php echo $order['net_amount']; ?>">
		<input type="text" name="currency" value="PHP">
		<input type="text" id="signature" name="signature" value="<?php echo sign($apiFields); ?>">
	</form>

	<script type="text/javascript">
		window.onload = function(){
		  document.getElementById('globalpayForm').submit();
		}
	    
	</script>

</body>
</html>
