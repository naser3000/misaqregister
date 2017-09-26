<style type="text/css">
    
    h2{
        font-family: 'IRANSans';
    }
    
    .container input{
        margin-left: 5px;
    }
    .container label{
        margin-top: 20px;
    }

</style>
<script language="javascript" type="text/javascript">
	/*
		// disable or enable 'std_number' input according to status selections
		function disableInput(){
			var select = document.getElementById('status');
			var input = document.getElementById('std_number');
			var input2 = document.getElementById('emp_number');
			if (select.value == 'دانشجو') {
				input.disabled = '';
			}else{
				input.value = '';
				input.disabled = 'disabled';
			}
			if (select.value == 'کارمند') {
				input2.disabled = '';
			}else{
				input.value = '';
				input2.disabled = 'disabled';
			}
		}*/
			
</script>


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

	<h2 class="form-signin-heading"> <?=lang("SIGNUP_TEXT","");?></h2>
	<div class="col-md-6">
	<div class=" panel panel-default ">
		<div class="panel-heading">اطلاعات حساب کاربری</div>
		<div class="panel-body">

			<label for="username">نام کاربری* (بین <?=$settings->max_un?>-<?=$settings->min_un?> حرف)</label>
			<input  class="form-control" type="text" name="username" id="username" placeholder="نام کاربری" value="<?php if (!$form_valid && !empty($_POST)){ echo $username;} ?>" required autofocus>
			<p class="help-block"></p>

			<label for="email">پست الکترونیک*</label>
			<input  class="form-control" type="text" name="email" id="email" placeholder="پست الکترونیک" value="<?php if (!$form_valid && !empty($_POST)){ echo $email;} ?>" required >
			<?php

		$character_range = 'بین '.$settings->min_pw . ' تا ' . $settings->max_pw;
		$character_statement = '<span id="character_range" class="gray_out_text">' . $character_range . ' حرف باشد.</span>';

if ($settings->req_cap == 1){
		$num_caps = '1'; //Password must have at least 1 capital
		if($num_caps != 1){
			$num_caps_s = 's';
		}
		$num_caps_statement = '<span id="caps" class="gray_out_text">حداقل دارای ' . $num_caps . ' حرف بزرگ باشد. </span>';
}

if ($settings->req_num == 1){
		$num_numbers = '1'; //Password must have at least 1 number
		if($num_numbers != 1){
			$num_numbers_s = 's';
		}

		$num_numbers_statement = '<span id="number" class="gray_out_text">حداقل دارای ' . $num_numbers . ' عدد باشد.</span>';
}
		$password_match_statement = '<span id="password_match" class="gray_out_text">تکرار رمز عبور صحیح باشد.</span>';


		//2.) Apply default class to gray out green check icon
		echo '
			<style>
				.gray_out_icon{
					-webkit-filter: grayscale(100%); /* Safari 6.0 - 9.0 */
					filter: grayscale(100%);
				}
				.gray_out_text{
					opacity: .5;
				}
			</style>
		';

		//3.) Javascript to check to see if user has met conditions on keyup (NOTE: It seems like we shouldn't have to include jquery here because it's already included by UserSpice, but the code doesn't work without it.)
		echo '
			<script type="text/javascript">
			$(document).ready(function(){

				$( "#password" ).keyup(function() {
					var pswd = $("#password").val();

					//validate the length
					if ( pswd.length >= ' . $settings->min_pw . ' && pswd.length <= ' . $settings->max_pw . ' ) {
						$("#character_range_icon").removeClass("gray_out_icon");
						$("#character_range").removeClass("gray_out_text");
					} else {
						$("#character_range_icon").addClass("gray_out_icon");
						$("#character_range").addClass("gray_out_text");
					}

					//validate capital letter
					if ( pswd.match(/[A-Z]/) ) {
						$("#num_caps_icon").removeClass("gray_out_icon");
						$("#caps").removeClass("gray_out_text");
					} else {
						$("#num_caps_icon").addClass("gray_out_icon");
						$("#caps").addClass("gray_out_text");
					}

					//validate number
					if ( pswd.match(/\d/) ) {
						$("#num_numbers_icon").removeClass("gray_out_icon");
						$("#number").removeClass("gray_out_text");
					} else {
						$("#num_numbers_icon").addClass("gray_out_icon");
						$("#number").addClass("gray_out_text");
					}
				});

				$( "#confirm" ).keyup(function() {
					var pswd = $("#password").val();
					var confirm_pswd = $("#confirm").val();

					//validate password_match
					if (pswd == confirm_pswd) {
						$("#password_match_icon").removeClass("gray_out_icon");
						$("#password_match").removeClass("gray_out_text");
					} else {
						$("#password_match_icon").addClass("gray_out_icon");
						$("#password_match").addClass("gray_out_text");
					}

				});
			});
			</script>
		';

?>

		<div style="display: inline-block">
			<label for="password">رمز عبور* (بین <?=$settings->max_pw?>-<?=$settings->min_pw?>  حرف)</label>
			<input  class="form-control" type="password" name="password" id="password" placeholder="رمز عبور" required autocomplete="off" aria-describedby="passwordhelp">

			<label for="confirm">تکرار رمز عبور*</label>
			<input  type="password" id="confirm" name="confirm" class="form-control" placeholder="تکرار رمز عبور" required autocomplete="off" >
		</div>
		<div style="display: inline-block; padding-right: 20px">
			<strong>رمز عبور باید...</strong><br>
			<span id="character_range_icon" class="glyphicon glyphicon-ok gray_out_icon" style="color: green"></span>&nbsp;&nbsp;<?php echo $character_statement;?>
			<br>
<?php
if ($settings->req_cap == 1){ ?>
			<span id="num_caps_icon" class="glyphicon glyphicon-ok gray_out_icon" style="color: green"></span>&nbsp;&nbsp;<?php echo $num_caps_statement;?>
			<br>
<?php }

if ($settings->req_num == 1){ ?>
			<span id="num_numbers_icon" class="glyphicon glyphicon-ok gray_out_icon" style="color: green"></span>&nbsp;&nbsp;<?php echo $num_numbers_statement;?>
			<br>
<?php } ?>
			<span id="password_match_icon" class="glyphicon glyphicon-ok gray_out_icon" style="color: green"></span>&nbsp;&nbsp;<?php echo $password_match_statement;?>
		</div>

		</div>
	</div><!--END OF panel-default  -->
	</div><!--END OF col  -->

	<div class="col-md-6">
	<div class=" panel panel-default ">
		<div class="panel-heading">اطلاعات فردی </div>
		<div class="panel-body">

			<label for="fname">نام*</label>
			<input type="text" class="form-control" id="fname" name="fname" placeholder="نام" value="<?php if (!$form_valid && !empty($_POST)){ echo $fname;} ?>" required>

			<label for="lname">نام خانوادگی*</label>
			<input type="text" class="form-control" id="lname" name="lname" placeholder="نام خانوادگی" value="<?php if (!$form_valid && !empty($_POST)){ echo $lname;} ?>" required>

			<label for="icode">کد ملی*</label>
			<input type="text" class="form-control" id="icode" name="icode" placeholder="کد ملی" value="<?php if (!$form_valid && !empty($_POST)){ echo $icode;} ?>" required>

			<label for="phnumber">شماره تماس*</label>
			<input type="text" class="form-control" id="phnumber" name="phnumber" placeholder="شماره تماس" value="<?php if (!$form_valid && !empty($_POST)){ echo $phnumber;} ?>" required>

			<label for="gender">جنسیت*</label><br>
			<input type="radio" class="form-contro" id="gender" name="gender" value="آقا" required>آقا<br>
			<input type="radio" class="form-contro" id="gender" name="gender" value="خانم" required>خانم<br>
		</div>
	</div><!--END OF panel-default  -->
	</div><!--END OF col  -->

	<div class="col-md-6">
	<div class=" panel panel-default">
		<div class="panel-heading">اطلاعات تکمیلی</div>
		<div class="panel-body">

			<label for="status">وضعیت*</label><br>
			<select name="status" id = "status" class="form-control" onchange="disableInput()">
				<option value="فارغ التحصیل">فارغ التحصیل</option>
				<option value="دانشجو" >دانشجو</option>
				<option value="کارمند">کارمند</option>
				<option value="استاد">استاد</option>
				<option value="آزاد">آزاد</option>
			</select>

			<label for="std_number">شماره دانشجویی*</label>
			<input type="text" class="form-control" id="std_number" name="std_number" disabled="disabled" placeholder="شماره دانشجویی" value =''>

			<label for="emp_number">کد کارمندی*</label>
			<input type="text" class="form-control" id="emp_number" name="emp_number" disabled="disabled" placeholder="کد کارمندی" value =''>
		</div>
	</div><!--END OF panel-default  -->
	</div><!--END OF col  -->

	<div class="col-md-6">
	<div class=" panel panel-default ">
		<div class="panel-heading">قوانین و شرایط</div>
		<div class="panel-body">

			<label for="confirm">شرایط و قوانین ثبت نام کاربر</label>
			<textarea id="agreement" name="agreement" rows="5" class="form-control" disabled ><?php require $abs_us_root.$us_url_root.'usersc/includes/user_agreement.php'; ?></textarea>
			<input type="checkbox" id="agreement_checkbox" name="agreement_checkbox" class="form-controlaaaaa">
			<label for="confirm">با قوانین موافقم.</label>			
		</div>
	</div><!--END OF panel-default  -->
	</div><!--END OF col  -->

	<div class="form-group">

		<br><br>

		
		
	</div>

	<?php if($settings->recaptcha == 1|| $settings->recaptcha == 2){ ?>
	<div class="form-group" align="center">
		<div class="g-recaptcha" data-sitekey="<?=$publickey; ?>"></div>
	</div>
	<?php } ?>
	<input type="hidden" value="<?=Token::generate();?>" name="csrf">
	<button class="submit btn btn-primary " type="submit" id="next_button"><i class="fa fa-plus-square"></i> ثبت نام</button>
	<br><br>
</form>
</div>
</div>
</div>
</div>
