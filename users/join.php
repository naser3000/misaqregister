<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
ini_set("allow_url_fopen", 1);
?>
<?php require_once 'init.php'; ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/header.php'; ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; ?>
<?php if (!securePage($_SERVER['PHP_SELF'])){die();} ?>
<?php
$settingsQ = $db->query("SELECT * FROM settings");
$settings = $settingsQ->first();
if($settings->recaptcha == 1 || $settings->recaptcha == 2){
	require_once("includes/recaptcha.config.php");
}
//There is a lot of commented out code for a future release of sign ups with payments
$form_method = 'POST';
$form_action = 'join.php';
$vericode = rand(100000,999999);

$form_valid=FALSE;

//Decide whether or not to use email activation
$query = $db->query("SELECT * FROM email");
$results = $query->first();
$act = $results->email_act;

//Opposite Day for Pre-Activation - Basically if you say in email
//settings that you do NOT want email activation, this lists new
//users as active in the database, otherwise they will become
//active after verifying their email.
if($act==1){
	$pre = 0;
} else {
	$pre = 1;
}

$token = Input::get('csrf');
if(Input::exists()){
	if(!Token::check($token)){
		die('Token doesn\'t match!');
	}
}

$reCaptchaValid=FALSE;

if(Input::exists()){

	$username = Input::get('username');
	$fname = Input::get('fname');
	$lname = Input::get('lname');
	$phnumber = Input::get('phnumber');
	$icode = Input::get('icode');
	$email = Input::get('email');
	$status = Input::get('status');
	$std_number = Input::get('std_number');
	$emp_number = Input::get('emp_number');
	$gender = Input::get('gender');
	$agreement_checkbox = Input::get('agreement_checkbox');

	if ($agreement_checkbox=='on'){
		$agreement_checkbox=TRUE;
	}else{
		$agreement_checkbox=FALSE;
	}

	$std_number_requirment = false;
	if ($status == 'دانشجو')
		$std_number_requirment = true;
	$emp_number_requirment = false;
	if ($status == 'کارمند')
		$emp_number_requirment = true;


	$db = DB::getInstance();
	$settingsQ = $db->query("SELECT * FROM settings");
	$settings = $settingsQ->first();
	$validation = new Validate();
	$validation->check($_POST,array(
	  'username' => array(
		'display' => 'نام کاربری',
		'required' => true,
		'min' => $settings->min_un,
		'max' => $settings->max_un,
		'unique' => 'users',
	  ),
	  'fname' => array(
		'display' => 'نام',
		'required' => true,
		'min' => 2,
		'max' => 35,
	  ),
	  'lname' => array(
		'display' => 'نام خانوادگی',
		'required' => true,
		'min' => 2,
		'max' => 35,
	  ),
	  'icode' => array(
		'display' => 'کد ملی',
		'required' => true,
		'exact' => 10,
	  ),
	  'phnumber' => array(
		'display' => 'شماره تماس',
		'required' => true,
		'exact' => 11,
	  ),
	  'email' => array(
		'display' => 'ایمیل',
		'required' => true,
		'valid_email' => true,
		'unique' => 'users',
	  ),
	  'status' => array(
		'display' => 'وضعیت',
		'required' => true,
	  ),
	  'std_number' => array(
		'display' => 'شماره دانشجویی',
		'required' => $std_number_requirment,
		'exact' => 8,
	  ),
	  'emp_number' => array(
		'display' => 'کد پرسنلی',
		'required' => $emp_number_requirment,
	  ),
	  'password' => array(
		'display' => 'رمز عبور',
		'required' => true,
		'min' => $settings->min_pw,
		'max' => $settings->max_pw,
	  ),
	  'confirm' => array(
		'display' => 'تکرار رمز عبور',
		'required' => true,
		'matches' => 'password',
	  ),
	));

	//if the agreement_checkbox is not checked, add error
	if (!$agreement_checkbox){
		$validation->addError(["لطفاً قوانین و شرایط را بخوانید و قبول کنید."]);
	}

	if($validation->passed() && $agreement_checkbox){
		//Logic if ReCAPTCHA is turned ON
	if($settings->recaptcha == 1 || $settings->recaptcha == 2){
			require_once("includes/recaptcha.config.php");
			//reCAPTCHA 2.0 check
			$response = null;

			// check secret key
			$reCaptcha = new ReCaptcha($privatekey);

			// if submitted check response
			if ($_POST["g-recaptcha-response"]) {
				$response = $reCaptcha->verifyResponse(
					$_SERVER["REMOTE_ADDR"],
					$_POST["g-recaptcha-response"]);
			}
			if ($response != null && $response->success) {
				// account creation code goes here
				$reCaptchaValid=TRUE;
				$form_valid=TRUE;
			}else{
				$reCaptchaValid=FALSE;
				$form_valid=FALSE;
				$validation->addError(["Please check the reCaptcha box."]);
			}

		} //else for recaptcha

		if($reCaptchaValid || $settings->recaptcha == 0){

			//add user to the database
			$user = new User();
			$join_date = date("Y-m-d H:i:s");
			$params = array(
				'fname' => Input::get('fname'),
				'email' => $email,
				'vericode' => $vericode,
			);

			if($act == 1) {
				//Verify email address settings
				$to = rawurlencode($email);
				$subject = 'Welcome to '.$settings->site_name;
				$body = email_body('_email_template_verify.php',$params);
				email($to,$subject,$body);
			}
			try {
				// echo "Trying to create user";
				$grade = "";
				$std_number = Input::get('std_number');
				if ( ($std_number/100000)%10 == 1 )
					$grade = "کارشناسی";
				if ( ($std_number/100000)%10 == 2 )
					$grade = "کارشناسی ارشد";
				if ( ($std_number/100000)%10 == 3 )
					$grade = "دکترا";
				$user->create(array(
					'username' => Input::get('username'),
					'fname' => Input::get('fname'),
					'lname' => Input::get('lname'),
					'icode' => Input::get('icode'),
					'phnumber' => Input::get('phnumber'),
					'email' => Input::get('email'),
					'status' => Input::get('status'),
					'std_number' => Input::get('std_number'),
					'major' => Input::get('major'),
					'dorms' => Input::get('dorms'),
					'emp_number' => Input::get('emp_number'),
					'yinter' => Input::get('std_number')/1000000,
					'grade' => $grade,
					'gender' => Input::get('gender'),
					'interested' => Input::get('interested'),
					'password' =>
					password_hash(Input::get('password'), PASSWORD_BCRYPT, array('cost' => 12)),
					'permissions' => 1,
					'account_owner' => 1,
					'stripe_cust_id' => '',
					'join_date' => $join_date,
					'company' => Input::get('company'),
					'email_verified' => $pre,
					'active' => 1,
					'vericode' => $vericode,
				));
			} catch (Exception $e) {
				die($e->getMessage());
			}
			Redirect::to($us_url_root.'users/joinThankYou.php');
		}

	} //Validation and agreement checbox
} //Input exists

?>
<?php header('X-Frame-Options: DENY'); ?>
<div id="page-wrapper">
<div class="container">
<?php
if($settings->glogin==1 && !$user->isLoggedIn()){
require_once $abs_us_root.$us_url_root.'users/includes/google_oauth_login.php';
}
if($settings->fblogin==1 && !$user->isLoggedIn()){
require_once $abs_us_root.$us_url_root.'users/includes/facebook_oauth.php';
}
require 'views/_join.php';
?>

</div>
</div>

<!-- footers -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

<?php if($settings->recaptcha == 1 || $settings->recaptcha == 2){ ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php } ?>

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
