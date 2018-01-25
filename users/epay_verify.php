<?php
require_once 'init.php';
require_once $abs_us_root.$us_url_root.'users/lib/nusoap.php';
// if(isset($_POST['State']) && $_POST['State'] == "OK") {
	$soapclient = new soapclient('https://acquirer.samanepay.com/payments/referencepayment.asmx?WSDL','wsdl');
	// $soapclient = new soapclient('https://acquirer.samanepay.com/payments/referencepayment.asmx?WSDL');
	// $soapclient->debug_flag=true;
	$soapProxy = $soapclient->getProxy() ;
	// if( $err = $soapclient->getError() )
	// 	echo $err ;
	// echo $soapclient->debug_str;
	$res = $soapProxy->VerifyTransaction($_POST['RefNum'], $_POST['MID']);

	if( $res <= 0 ) {
		echo "Error ". $res;
		exit;
	} else {
		echo "The transaction was successful";
		exit;
	}
// }
?>