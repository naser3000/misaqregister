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

if($user->isLoggedIn() || !$user->isLoggedIn() && !checkMenu(2,$user->data()->id)){
	if (($settings->site_offline==1) && (!in_array($user->data()->id, $master_account)) && ($currentPage != 'login.php') && ($currentPage != 'maintenance.php')){
		$user->logout();
		Redirect::to($us_url_root.'users/maintenance.php');
	}
}
$grav = get_gravatar(strtolower(trim($user->data()->email)));
$get_info_id = $user->data()->id;
//There is a lot of commented out code for a future release of sign ups with payments
$form_method = 'POST';
$form_action = 'data_completion.php';

$form_valid=FALSE;


$token = Input::get('csrf');
if(Input::exists()){
	if(!Token::check($token)){
		die('Token doesn\'t match!');
	}
}

$reCaptchaValid=FALSE;

if(Input::exists()){
	$fname = Input::get('fname');
	$lname = Input::get('lname');
	$phnumber = Input::get('phnumber');
	$icode = Input::get('icode');
	$status = Input::get('status');
	$std_number = Input::get('std_number');
	$emp_number = Input::get('emp_number');
	$gender = Input::get('gender');


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
	));


	if($validation->passed()){

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
					
			$data_completion = array(
				'fname' => Input::get('fname'),
				'lname' => Input::get('lname'),
				'icode' => Input::get('icode'),
				'phnumber' => Input::get('phnumber'),
				'status' => Input::get('status'),
				'std_number' => Input::get('std_number'),
				'major' => Input::get('major'),
				'dorms' => Input::get('dorms'),
				'emp_number' => Input::get('emp_number'),
				'yinter' => Input::get('std_number')/1000000,
				'grade' => $grade,
				'gender' => Input::get('gender'),
				'interested' => Input::get('interested'),
				'data_completion' => 1,
			);
			$db->update('users', $get_info_id, $data_completion);
			Redirect::to($us_url_root.'users/account.php');
		} catch (Exception $e) {
			die($e->getMessage());
		}

	} //Validation checbox
} //Input exists

?>

<div id="page-wrapper">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<?php
				if (!$form_valid && Input::exists()){
					echo display_errors($validation->errors());
				}
				?>

				<form class="form-signup" action="<?=$form_action;?>" method="<?=$form_method;?>" id="payment-form">

					<div class="col-md-6 pull-right">
						<div class=" panel panel-default ">
							<div class="panel-heading">اطلاعات فردی </div>
							<div class="panel-body">
								<div class="form-group">
									<label for="fname">نام*</label>
									<input type="text" class="form-control" id="fname" name="fname" placeholder="نام" value="<?php if (!$form_valid && !empty($_POST)){ echo $fname;} ?>" required>
								</div>
								<div class="form-group">
									<label for="lname">نام خانوادگی*</label>
									<input type="text" class="form-control" id="lname" name="lname" placeholder="نام خانوادگی" value="<?php if (!$form_valid && !empty($_POST)){ echo $lname;} ?>" required>
								</div>
								<div class="form-group">
									<label for="icode">کد ملی*</label>
									<input type="text" class="form-control" id="icode" name="icode" placeholder="کد ملی" value="<?php if (!$form_valid && !empty($_POST)){ echo $icode;} ?>" required>
								</div>
								<div class="form-group">
									<label for="phnumber">شماره تماس*</label>
									<input type="text" class="form-control" id="phnumber" name="phnumber" placeholder="شماره تماس" value="<?php if (!$form_valid && !empty($_POST)){ echo $phnumber;} ?>" required><br>
								</div>
								<div class="col-md-6">
									<label for="gender">جنسیت*</label><br>
									<input type="radio" class="form-contro" id="gender" name="gender" value="آقا" required>آقا<br>
									<input type="radio" class="form-contro" id="gender" name="gender" value="خانم" required>خانم<br>
								</div>
								
								<div class="col-md-6">
									<label for="interested">علاقه مند به همکاری</label><br>
									<input type="radio" class="form-contro" id="interested" name="interested" value="بله" required>بله<br>
									<input type="radio" class="form-contro" id="interested" name="interested" value="خیر" required>خیر<br>
								</div>
								
							</div>
						</div><!--END OF panel-default  -->
					</div><!--END OF col  -->

					<div class="col-md-6 pull-right">
						<div class=" panel panel-default">
							<div class="panel-heading">اطلاعات تحصیلی</div>
							<div class="panel-body">
								<div class="form-group">
									<label for="status">وضعیت*</label><br>
									<select name="status" id = "status" class="form-control" onchange="disableInput()">
										<option value="فارغ التحصیل">فارغ التحصیل</option>
										<option value="دانشجو" >دانشجو</option>
										<option value="کارمند">کارمند</option>
										<option value="استاد">استاد</option>
										<option value="آزاد">آزاد</option>
									</select>
								</div>
								<div class="form-group">
									<label for="std_number">شماره دانشجویی*</label>
									<input type="text" class="form-control" id="std_number" name="std_number" readonly="" placeholder="شماره دانشجویی" value =''>
								</div>
								<div class="form-group">
									<label for="major">رشته تحصیلی</label>
									<input type="text" class="form-control" id="major" name="major" placeholder="رشته تحصیلی">
								</div>
								<div class="form-group">
									<label for="dorms">خوابگاه</label><br>
									<select name="dorms" id = "dorms" class="form-control" disabled="disabled" >
										<option></option>
										<option value="تهرانی">تهرانی</option>
										<option value="طرشت 3" >طرشت 3</option>
										<option value="احمدی روشن">احمدی روشن</option>
										<option value="طرشت 2">طرشت 2</option>
										<option value="آزادی">آزادی</option>
										<option value="وزوایی">وزوایی</option>
										<option value="شادمان">شادمان</option>
										<option value="صادقی">صادقی</option>
										<option value="متأهلی">متأهلی</option>
										<option value="شوریده">شوریده</option>
										<option value="ولیعصر">ولیعصر</option>
										<option value="12 واحدی">12 واحدی</option>
										<option value="حیدرتاش">حیدرتاش</option>
										<option value="مصلی نژاد">مصلی نژاد</option>
									</select>
								</div>
								<div class="form-group">
									<label for="emp_number">کد کارمندی*</label>
									<input type="text" class="form-control" id="emp_number" name="emp_number" readonly="" placeholder="کد کارمندی" value =''>
								</div>
							</div>
						</div><!--END OF panel-default  -->
					</div><!--END OF col  -->

					<input type="hidden" value="<?=Token::generate();?>" name="csrf">
					<button class="submit btn btn-primary " type="submit" id="next_button"><i class="fa fa-plus-square"></i>تکمیل ثبت نام</button>
					<br><br>
				</form>
			</div>
		</div>
	</div>
</div>
