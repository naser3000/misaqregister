<?php

$user="sharif.misaaq";
$pass="3456";
$sender="30009900091105";
//------------------------------------------credit_line--------------------------------
require_once('lib/nusoap.php');

function get_sms_account_charge($user, $pass, $sender)
{
	$client = new soapclient('http://webservice.smsline.ir/');
	$err = $client->getError();
	if (!$err)
	{
		$account_charge = $client->call('CREDIT_LINESMS',
						                                 array(
						                                 $user,
						                                 $pass,
						                                 $sender)
						                             	);
		$err = $client->getError();
		unset($client);
		if ($err)
	 		return $err;
		else
		{
	    	return $account_charge;
		}
	}
}

function send_sms($user, $pass, $sender, $text, $receiver)
{
	$client = new soapclient('http://webservice.smsline.ir/');
	$err = $client->getError();
	if (!$err)
	{
		$send = $client->call('SendSMS',
		                                 array(
		                                 $user,
		                                 $pass,
		                                 $reciver,
		                                 $text,
		                                 $sender)
		                                 );

		$err = $client->getError();
		unset($client);
		if ($err)
			return $err;
		else
		    return $send;
	}
	else
		return $err;
}

function send_group_sms($user, $pass, $sender, $text, $receiver)
{
	$client = new soapclient('http://webservice.smsline.ir/');
	$err = $client->getError();
	if (!$err)
	{
		$send = $client->call('Send_GROUP_SMS',
		                                 array(
		                                 $user,
		                                 $pass,
		                                 $receiver,
		                                 $text,
		                                 $sender,
										 "2")
		                                 );
		$err = $client->getError();
		unset($client);
		if ($err)
		 	return $err;
		 else
		    return $send;
	}	
	else 
		return $err;
}

if(isset($_POST['group_sms']))
{
	send_group_sms($user, $pass, $sender, $_POST['group_sms_text'], $_POST['receivers']);
}

?>

<html dir="rtl">
	<div>شارژ: <?php echo get_sms_account_charge($user, $pass, $sender); ?> ریال</div>
	<div>
	<div>ارسال پیام:</div>
	<form method="POST" action="testsend.php">
		گیرندگان:<input type="text" name="receivers" size="20">
		<br>
		متن پیام:<textarea name="group_sms_text" id="group_sms_text" cols="50" rows="5" ></textarea>
		<span id="group_sms_chars">0</span>
		<br>
		<input type="submit" value="ارسال" name="group_sms">
	</form>
	</div>
</html>
    
<script src="http://code.jquery.com/jquery-1.11.1.js" type="text/javascript"></script>
<script type='text/javascript'>
    $('#group_sms_text').keyup(updateCount);
    $('#group_sms_text').keydown(updateCount);

    function updateCount() {
        var cs = $(this).val().length;
        $('#group_sms_chars').text(cs);
    }
</script>