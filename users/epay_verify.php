<?php
require_once 'init.php';
require_once $abs_us_root.$us_url_root.'users/lib/nusoap.php';
require_once $abs_us_root.$us_url_root.'users/includes/header.php';

//dealing with if the user is logged in
if($user->isLoggedIn() || !$user->isLoggedIn() && !checkMenu(2,$user->data()->id)){
	if (($settings->site_offline==1) && (!in_array($user->data()->id, $master_account)) && ($currentPage != 'login.php') && ($currentPage != 'maintenance.php')){
		$user->logout();
		Redirect::to($us_url_root.'users/maintenance.php');
	}
}
$get_info_id = $user->data()->id;
$userdetails = fetchUserDetails(NULL, NULL, $get_info_id); //Fetch user details

if(isset($_POST['State'])) {
	$soapclient = new nusoap_client('https://acquirer.samanepay.com/payments/referencepayment.asmx?WSDL','wsdl');
	// $soapclient->debug_flag=true;
	$soapProxy = $soapclient->getProxy() ;
	// if( $err = $soapclient->getError() )
	// 	echo $err ;
	// echo $soapclient->debug_str;
	$res = $soapProxy->VerifyTransaction($_POST['RefNum'], $_POST['MID']);
	if( $res <= 0 ) {
		echo "Error ".$res."<br>";
		echo "Error: ".$_POST['State']."<br>";
		echo "Error: ".$_POST['StateCode']."<br>";
		echo "Error: ".$_POST['ResNum']."<br>";
		echo "Error: ".$_POST['SecurePan']."<br>";
		echo "Error: ".$_POST['CID']."<br>";

		echo "<script>alert(\"خطا: پرداخت با موفقیت انجام نشد!\");</script>";
		echo "<script>setTimeout(\"location.href = 'http://localhost".$us_url_root."users/account.php'\",3000);</script>";
	} else {
		echo "The transaction was successful";
		//Update account charge
		$new_account_charge = $userdetails->account_charge + ($res/10); 
		$fields=array('account_charge'=>$new_account_charge);

		$db->update('users',$userId,$fields);
		echo "<script>alert(\"خطا: پرداخت با موفقیت انجام شد!\");</script>";
		echo "<script>setTimeout(\"location.href = 'http://localhost".$us_url_root."users/account.php'\",1000);</script>";
	}
}
?>