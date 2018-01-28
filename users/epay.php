<?php require_once 'init.php'; ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/header.php'; ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; ?>

<?php if (!securePage($_SERVER['PHP_SELF'])){die();}?>
<?php
	if($user->isLoggedIn() || !$user->isLoggedIn() && !checkMenu(2,$user->data()->id)){
		if (($settings->site_offline==1) && (!in_array($user->data()->id, $master_account)) && ($currentPage != 'login.php') && ($currentPage != 'maintenance.php')){
			$user->logout();
			Redirect::to($us_url_root.'users/maintenance.php');
		}
	}
	$grav = get_gravatar(strtolower(trim($user->data()->email)));
	$get_info_id = $user->data()->id;
	$raw = date_parse($user->data()->join_date);
	$signupdate = $raw['month']."/".$raw['day']."/".$raw['year'];
	$userdetails = fetchUserDetails(NULL, NULL, $get_info_id); //Fetch user details
	if ($userdetails->data_completion == 0){
		Redirect::to($us_url_root.'users/data_completion.php');
	}
 ?>
<?php
$sep_MID	 		= "10548701";						// کد پذیرنده
$sep_Amount 		= "1000";							// قیمت به ریال
$sep_ResNum 		= time();							// شماره سفارش
$sep_RedirectURL 	= "http://localhost/misaqregister/users/epay_verify.php";	// لینک برگشت و برسی نتیجه تراکنش
?>
<div id="page-wrapper">
	<div class="container">

		<div class="well">
		<div class="row">
			<div class="col-md-12 text-center">
				<h1>میزان اعتبار</h1>
				<p>موجودی حساب کاربری شما</p>
				<p><?=$userdetails->account_charge?> تومان</p>
				<p>می باشد.</p>
			</div>
		</div><br><hr>
		<div class="row text-center">
			<form action="https://sep.shaparak.ir/payment.aspx" method="post">
				<div class="col-md-4"></div>
				<div class="input-group col-md-4">
	                <span class="input-group-addon" >
	                    <span class="capacity" >ریال</span>
	                </span>
	                <input type="number" id="Amount" class="form-control mac-style" name="Amount" oninput="btnActive()">
	                <span class="input-group-addon" >
	                    <span class="capacity" >تعیین مبلغ</span>
	                </span>
	            </div><br>
				<input type="hidden" name="ResNum" value="<?php echo $sep_ResNum; ?>">
				<input type="hidden" name="RedirectURL" value="<?php echo $sep_RedirectURL; ?>"/>
				<input type="hidden" name="MID" value="<?php echo $sep_MID; ?>"/>
				<input type="submit" name="submit_payment" id="paid" value="انتقال به درگاه پرداخت" class="btn btn-info" onclick="formSubmit()" disabled="disabled" />
			</form>
		</div>
		<div class="alert alert-danger hidden">
			<p>لطفاً مبلغ مورد نظر را تعیین کنید.</p>
		</div>
		</div>
	</div> <!-- /container -->
</div> <!-- /#page-wrapper -->

<!-- footers -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

<!-- Place any per-page javascript here -->

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>

<script type="text/javascript">

	function formSubmit() {
		document.forms[0].submit();
	}
	function btnActive() {
		console.log($('#Amount')[0].value.length );
		if( $('#Amount')[0].value.length < 4)
			$('#paid')[0].disabled = true;
		else
			$('#paid')[0].disabled = false;
	}
</script>