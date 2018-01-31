<?php
if(isset($_POST['State']) && $_POST['State'] == "OK") {

	$soapclient = new soapclient('https://acquirer.samanepay.com/payments/referencepayment.asmx?WSDL');
	$res = $soapclient->VerifyTransaction($_POST['RefNum'], $_POST['MID']);

	if( $res <= 0 ) {
		echo "Error ". $res;
		exit;
	} else {
		echo "The transaction was successful";
		exit;
	}

}
?>