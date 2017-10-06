<?php require_once 'init.php'; ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/header.php'; ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; ?>

<?php if (!securePage($_SERVER['PHP_SELF'])){die();}?>
<?php

//dealing with if the user is logged in
///echo "6666666666666666666666666666666666666666666666666";
if($user->isLoggedIn() || !$user->isLoggedIn() && !checkMenu(2,$user->data()->id)){
	if (($settings->site_offline==1) && (!in_array($user->data()->id, $master_account)) && ($currentPage != 'login.php') && ($currentPage != 'maintenance.php')){
		$user->logout();
		Redirect::to($us_url_root.'users/maintenance.php');
	}
}
///echo "55555555555555555555555555555555555555555555555555";
$grav = get_gravatar(strtolower(trim($user->data()->email)));
$get_info_id = $user->data()->id;
// $groupname = ucfirst($loggedInUser->title);
$raw = date_parse($user->data()->join_date);
$signupdate = $raw['month']."/".$raw['day']."/".$raw['year'];
$userdetails = fetchUserDetails(NULL, NULL, $get_info_id); //Fetch user details
$plansData = fetchAllPlans(); //Fetch information for all plans
///echo "44444444444444444444444444444444444444444444";
 ?>

 <?php
// REGISTER IN PLAN
$successes=[];
$form_valid = TRUE;
 //Forms posted
if(!empty($_POST)) {
	///echo "2222222222222222222222222222222222222222";
    $token = $_POST['csrf'];
    if(!Token::check($token)){
    	///print_r($token);
    	///echo "1111111111111111111111111111111111111";
      //die('Token doesn\'t match!');
    }
    //else
    {
    	///echo "0000000000000000000000000000000000000000000";

/*
?><div style="height: 300px;"><?php   
		echo "**************************************************";
    	print_r('=================');
?></div><?php  
*/


    	$user_id = Input::get('user_id');
    	$plan_id = Input::get('plan_id');
    	$capacity_id = Input::get('capacity_id');

    	$total_cost_str = Input::get('total_cost');
    	$total_cost_str = explode(": ", $total_cost_str)[1];
    	$total_cost = explode(" تومان", $total_cost_str)[0];


    	$participant_select1 = Input::get('participant_choise1');
    	$participant_name1 = Input::get('participant_name1');
    	$participant_code1 = Input::get('participant_code1');
    	$participant_gender1 = Input::get('participant_gender1');

    	$participant_select2 = Input::get('participant_choise2');
    	$participant_name2 = Input::get('participant_name2');
    	$participant_code2 = Input::get('participant_code2');
    	$participant_gender2 = Input::get('participant_gender2');

    	$participant_select3 = Input::get('participant_choise3');
    	$participant_name3 = Input::get('participant_name3');
    	$participant_code3 = Input::get('participant_code3');
    	$participant_gender3 = Input::get('participant_gender3');


    	$participant_requirement1 = false;
    	$participant_requirement2 = false;
    	$participant_requirement3 = false;
    	if ($participant_select1 == "1")
    		$participant_requirement1 = true;
    	if ($participant_select2 == "1")
    		$participant_requirement2 = true;
    	if ($participant_select3 == "1")
    		$participant_requirement3 = true;

   		$form_valid=FALSE; // assume the worst
    	$validation = new Validate();
    	$validation->check($_POST,array(
    		'participant_name1' => array(
    		'display' => 'نام همراه 1',
    		'required' => $participant_requirement1,
    		'min'=> 2,
    		'max' => 35,
    		),
      		'participant_code1' => array(
      		'display' => 'کدملی همراه 1',
      		'required' => $participant_requirement1,
      		'exact' => 10,
      		),
      		'participant_gender1' => array(
      		'display' => 'جنسیت همراه 1',
      		'required' => $participant_requirement1,
      		),
      		'participant_name2' => array(
    		'display' => 'نام همراه 2',
    		'required' => $participant_requirement2,
    		'min'=> 2,
    		'max' => 35,
    		),
      		'participant_code2' => array(
      		'display' => 'کدملی همراه 2',
      		'required' => $participant_requirement2,
      		'exact' => 10,
      		),
      		'participant_gender2' => array(
      		'display' => 'جنسیت همراه 2',
      		'required' => $participant_requirement2,
      		),
      		'participant_name3' => array(
    		'display' => 'نام همراه 3',
    		'required' => $participant_requirement3,
    		'min'=> 2,
    		'max' => 35,
    		),
      		'participant_code3' => array(
      		'display' => 'کدملی همراه 3',
      		'required' => $participant_requirement3,
      		'exact' => 10,
      		),
      		'participant_gender3' => array(
      		'display' => 'جنسیت همراه 3',
      		'required' => $participant_requirement3,
      		),
    	));
    	if($validation->passed()) {
    		$form_valid=TRUE;
     		try {
        		// echo "Trying to create user";
        		$p0 = $p1 = $p2 = $p3 = 0;
        		$user_capacity = 1;
     			if ($participant_select1 == "1")
    				$p1 = 1;
    			if ($participant_select2 == "1")
    				$p2 = 1;
    			if ($participant_select3 == "1")
    				$p3 = 1;

        		$plan_capacity = fetchCapacityDetails($capacity_id);
        		$blank_space = $plan_capacity->capacity_number - $plan_capacity->registered;
        		$reserved_number = 0;
        		$reserved_number1 = 0;
        		$reserved_number2 = 0;
        		$reserved_number3 = 0;

        		if ( $blank_space >= $user_capacity ){
        			$register_status = "ثبت نام";
        		}
        		else{
        			$register_status = "رزرو";
        			if ($blank_space == 0){
        				$p0 = 1;
    					$reserved_number = $plan_capacity->reserved+1;
    					$reserved_number1 = ($reserved_number+1)*$p1;
        				$reserved_number2 = ($reserved_number+2)*$p2;
        				$reserved_number3 = ($reserved_number+3)*$p3;
        			}
        			if ($blank_space == 1){
    					$reserved_number = 0;
    					$reserved_number1 = 1*$p1;
        				$reserved_number2 = 2*$p2;
        				$reserved_number3 = 3*$p3;
        			}
    				if ($blank_space == 2){
    					$reserved_number = 0;
    					$reserved_number1 = 0;
        				$reserved_number2 = 1*$p2;
        				$reserved_number3 = 2*$p3;
    				}
    				if ($blank_space == 3){
    					$reserved_number = 0;
    					$reserved_number1 = 0;
        				$reserved_number2 = 0;
        				$reserved_number3 = 1*$p3;
    				}
        			
        		}

        		$plan_register_fields=array(
        			'user_id' => Input::get('user_id'),
        			'plan_id' => Input::get('plan_id'),
        			'capacity_id' => Input::get('capacity_id'),
        			'status' => $register_status,
        			'reserved_number' => $reserved_number,
        			'reserved_number1' => $reserved_number1,
        			'reserved_number2' => $reserved_number2,
        			'reserved_number3' => $reserved_number3,
        			'participant_name1' => Input::get('participant_name1'),
        			'participant_code1' => Input::get('participant_code1'),
        			'participant_gender1' => Input::get('participant_gender1'),
        			'participant_name2' => Input::get('participant_name2'),
        			'participant_code2' => Input::get('participant_code2'),
        			'participant_gender2' => Input::get('participant_gender2'),
        			'participant_name3' => Input::get('participant_name3'),
        			'participant_code3' => Input::get('participant_code3'),
        			'participant_gender3' => Input::get('participant_gender3'),
        		);
       			$db->insert('plan_register',$plan_register_fields);

       			// update user account_charge
				//$userdetails = fetchUserDetails($user_id);
				$charge_fields=array('account_charge'=> ($userdetails->account_charge-$total_cost) );
				$db->update('users',$user_id,$charge_fields);

				// update capacity_number
				if ($register_status == "ثبت نام") {
					$fields=array('registered'=> ($plan_capacity->registered+$user_capacity) );
					$db->update('capacity',$capacity_id,$fields);
					$successes[] = lang("PLAN_REGISTER");
				}elseif ($register_status == "رزرو") {
					$fields=array('reserved'=> ($plan_capacity->reserved + $p0 + $p1 + $p2 + $p3) );
					$db->update('capacity',$capacity_id,$fields);
					$successes[] = lang("PLAN_RESERVE");
				}
				
        		
	
      			} catch (Exception $e) {
        		die($e->getMessage());
      		}
    	}// passed validate
    }
}

 ?>

<div id="page-wrapper">
<div class="container">

<div class="well">
<div class="row">
	<div class="col-xs-12 col-sm-4 col-md-3 pull-right">
		<p><img src="<?=$grav; ?>" class="img-thumbnail" alt="Generic placeholder thumbnail"></p>
		<p><a href="user_settings.php" class="btn btn-primary equal-btn">ویرایش اطلاعات</a></p>
		<p ><a class="btn btn-primary equal-btn" href="profile.php?id=<?=$get_info_id;?>" role="button">پروفایل</a></p>

	</div>
	<div class="col-xs-12 col-sm-4 col-md-3 pull-right">
		<h1><?=$userdetails->username?></h1>
		<?php 
		if ($userdetails->gender == "آقا")
			$space = "ی ";
		else
			$space = " ";
		?>
		<p><?=$userdetails->gender.$space.$userdetails->fname." ".$userdetails->lname?></p>
		
		<p>وضعیت: <?=$userdetails->status?></p>
		<p>رشته تحصیلی: <?=$userdetails->major?></p>
		<?php if ($userdetails->status == "دانشجو") {?>
			<p>مقطع: <?=$userdetails->grade?></p>
			<p>خوابگاه: <?=$userdetails->dorms?></p>
			<p>شماره دانشجویی: <?=$userdetails->std_number?></p>
		<?php } ?>
		
		<p>کد ملی: <?=$userdetails->icode?></p>

		<?php if ($userdetails->status == "کارمند") {?>
			<p>کد پرسنلی: <?=$userdetails->emp_number?></p>
		<?php } ?>

	</div>
	<div class="col-xs-12 col-sm-4 col-md-3 pull-right">
		<h1>اطلاعات حساب کاربری</h1>
		<p>شماره تماس: <?=$userdetails->phnumber?></p>
		<p>ایمیل: <?=$userdetails->email?></p>
		<p>علاقه مند به همکاری: <?=$userdetails->interested?></p>
		<p>تاریخ عضویت: <?=$signupdate?></p>
		<p>تعداد ورود: <?=$userdetails->logins?></p>


	</div>
	<div class="col-xs-12 col-sm-4 col-md-3" style="text-align: center;">
		<h1>میزان اعتبار</h1>
		<p>موجودی حساب کاربری شما</p>
		<p><?=ucfirst($user->data()->account_charge)?> ریال</p>
		<p>می باشد.</p>
		<hr>
		<p>برای افزایش اعتبار بر روی دکمه زیر کلیک کنید.</p>
		<p ><a class="btn btn-primary equal-btn" href="" role="button">افزایش اعتبار</a></p>

	</div>
</div>
</div>
	<h2>برنامه ها</h2>
	<div class="panel panel-default">
	<div class="panel-heading">برنامه های سایت فرهنگی میثاق</div>
	<div class="panel-body">
	<?php
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////  Cycle through plans  //////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////  (creating a PLAN with its moadals)  ///////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		foreach ($plansData as $pld) {
	?>
	<!--  MODAL POPUP -->
	<div class="modal" id="register_modal<?=$pld->id?>">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">

			<!--		<span class="pull-left mini-btn" id="plan_id"></span> -->
					<span class="pull-left mini-btn" >کد برنامه: <?=$pld->id?></span>
					<?php

					$related_capacity_n= 0;
					$capacities = fetchAllPlanCapacities($pld->id);
					//print_r($userdetails->yinter);

 		//////////////////////////////////  Cycle through capacity  ////////////////////////////////////////////////////////
						foreach ($capacities as $capacity) {
							
							if ( !in_array($userdetails->status, explode(',   ', $capacity->status))) {
								/*print_r($capacity->status."+++");
								for ($iii=0; $iii < count(explode(',   ', $capacity->status)); $iii++) { 
									print_r(explode(",   ", $capacity->status)[$iii]."*");
								}*/
								//print_r($capacity->status);
								continue;
							}
							if ( !in_array($userdetails->yinter, explode(', ', $capacity->yinter)) & $userdetails->status == "دانشجو") {
								//print_r($capacity->yinter);
								if ( !in_array($userdetails->grade." (".$userdetails->yinter.")", explode(', ', $capacity->yinter))) {
									continue;
								}
							}
							if ( !in_array($userdetails->gender, explode(', ', $capacity->gender))) {
								//print_r($capacity->gender);
								continue;
							}

							if ($capacity->registered == $capacity->capacity_number){
									//
							}
							$related_capacity = $capacity;
							$related_capacity_n++;
							break;
						}
		///////////////////////////////  End of Cycle through capacity  /////////////////////////////////////////////////
						if ($related_capacity_n == 0) {

							?>
								<br>
								<h2 class="modal-title">ثبت نام در <?=$pld->title?> .</h2>
							</div>
							<div class="modal-body">شما نمی توانید در این برنامه شرکت کنید.</div>
							<div class="modal-footer"></div>
						</div><!-- end .modal-content -->
					</div><!-- end .modal-dialog -->
				</div><!-- end .modal -->
				<!-- END of MODAL (can not register)-->

				<?php
						// continue;  (clean else if u want dont show plans that user cant register in it.)
						} 
						else{
						///////(can register)
					?>

					<br>
					<h2 class="modal-title">ثبت نام در <?=$pld->title?> .</h2>
					<input class="form-control" type="text" name="" id="plan_cost" readOnly="" value="هزینه <?=$related_capacity->cost?> تومان">
				</div>
				<form class="form-signup"  method="post" action="account.php" id="register-plan-form">
					<div class="modal-body">
						<div class="modal-error-feedback<?=$pld->id?>">
							<?php
							if (isset($plan_id)) {
								if($plan_id == $pld->id){
						           	if (!$form_valid && Input::exists()){
						     ?>
						                <ul class="bg-danger">
						    <?php
						                foreach($validation->errors() as $error){
						    ?>
						                    <li class="text-danger"><?=$error[0]?></li>
						    <?php
						             	}
						    ?>
						         		</ul>
						    <?php
						        	}
						        }
						    }
						    ?>
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12 pull-right">
							<label>نام</label>
							<input class="form-control" type="text" name="" id="name" readOnly="" value="<?=ucfirst($user->data()->fname)." ".ucfirst($user->data()->lname)?>">
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12 pull-right">
							<label>کدملی</label>
							<input class="form-control" type="text" name="" id="code" readOnly="" value="<?=$user->data()->status?>">
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12 pull-right">
							<label>شماره دانشجویی</label>
							<input class="form-control" type="text" name="" id="stdn" readOnly="" value="<?=$user->data()->std_number?>">
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12 pull-right">
							<label>شماره تماس</label>
							<input class="form-control" type="text" name="" id="stdn" readOnly="" value="<?=$user->data()->std_number?>">
						</div>
							
		<?php 
			$plan_register_details = fetchPlanRegisterDetails($userdetails->id, $pld->id, $related_capacity->id);
			//print_r($plan_register_details[0]);
			$rgs = isset($plan_register_details[0]);
			$i = 1;
			while ($i <= $related_capacity->participant_number) { ?>
						<div class="col-md-1 col-sm-1 col-xs-12 pull-right">
							<label>افزودن همراه</label><br>
							<input type="checkbox" name="participant_choise<?=$i?>" id="participant_choise<?=$i?>" <?php if($i>1){echo 'disabled="disabled"';} ?> onClick="participant_active(<?=$i?>)" value="1">
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12 pull-right">
							<label>نام همراه <?=$i?></label>
							<input class="form-control" type="text" name="participant_name<?=$i?>" id="participant_name<?=$i?>" readOnly="" placeholder="نام">
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12 pull-right">
							<label>کدملی همراه <?=$i?></label>
							<input class="form-control" type="text" name="participant_code<?=$i?>" id="participant_code<?=$i?>" readOnly="" placeholder="کدملی">
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12 pull-right">
							<label>جنسیت</label>
							<Select class="form-control" name="participant_gender<?=$i?>" id="participant_gender<?=$i?>" >
								<option value=""></option>
								<option value="آقا">آقا</option>
								<option value="خانم">خانم</option>
							</Select>
						</div>
						<div class="col-md-2 col-sm-2 col-xs-12 pull-right">
							<label>هزینه همراه</label>
							<input class="form-control" type="text" name="participant_cost<?=$i?>" id="participant_cost<?=$i?>" readOnly="" value="<?=$related_capacity->participant_cost?> تومان">
						</div>
			<?php $i++;} ?>
			
						
						<div class="clearfix"></div>
						<input class="form-control" type="text" name="total_cost" id="total_cost" readOnly="" value="مجموع هزینه ها: <?=$related_capacity->cost?> تومان" style="margin: 0px;">
						
					</div><!-- end .modal-body -->
					<div class="modal-footer">
						<input type="hidden" value="<?=$user->data()->id;?>" name="user_id">
						<input type="hidden" value="<?=$related_capacity->plan_id?>" name="plan_id">
						<input type="hidden" value="<?=$related_capacity->id?>" name="capacity_id">
						<input type="hidden" value="<?=Token::generate();?>" name="csrf">
						<button class="submit btn btn-success mini-btn" id="<?=$pld->id?>" type="submit">تأیید اطلاعات و پرداخت</button>
						<span class="close pull-left" data-dismiss="modal"><a href="#" class="btn btn-warning mini-btn">انصراف</a></span>
					</div>
				</form>
				
			</div><!-- end .modal-content -->
		</div><!-- end .modal-dialog -->
	</div><!-- end .modal -->
	<!-- END of MODAL -->

					<?php	
						}
						// end of ELSE (can register)
					?>
	<!--  MODAL POPUP -->
	<div class="modal" id="edit_register_modal<?=$pld->id?>">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
				</div>
				<div class="modal-body">
					<div class="modal-success-feedback<?=$pld->id?>">
					<?php
					if (isset($plan_id)) {
						if($plan_id == $pld->id){
                        	if (!$form_valid && Input::exists()){
 								
                        	}else{
                    ?>
                        		<ul class="bg-warning">
                        			<li class="text-success"><?=$successes[0]?></li>
                        		</ul>
                    <?php
                        	}
                        }
                    }
                    ?>
                    </div>
                    <?php 
                    		if ($rgs) {
                    			$rgs_status = $rgs_status1 = $rgs_status2 = $rgs_status3 = 'ثبت نام شده';
                    			if ($plan_register_details[0]->reserved_number > 0)
                    				$rgs_status = "رزرو شماره ".$plan_register_details[0]->reserved_number;
                    			if ($plan_register_details[0]->reserved_number1 > 0)
                    				$rgs_status1 = "رزرو شماره ".$plan_register_details[0]->reserved_number1;
                    			if ($plan_register_details[0]->reserved_number2 > 0)
                    				$rgs_status2 = "رزرو شماره ".$plan_register_details[0]->reserved_number2;
                    			if ($plan_register_details[0]->reserved_number3 > 0)
                    				$rgs_status3 = "رزرو شماره ".$plan_register_details[0]->reserved_number3;
                    ?>
                    <div class="allutable table-responsive">
                    	<table class='table table-hover table-list-search'>
							<thead>
								<tr>
									<th>حذف</th><th>نام و نام خانوادگی</th><th>کدملی</th><th>وضعیت</th><th>ویرایش</th><th>حذف</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><div class="form-group"><input type="checkbox" name="delete[<?=$v1->id?>]" value="<?=$v1->id?>" /></div></td>
									<td><input class="form-control" type="text" name="" value="<?=$userdetails->gender.$space.$userdetails->fname." ".$userdetails->lname?>"></td>
									<td><input class="form-control" type="number" name="" value="<?=$userdetails->icode?>"></td>
									<td><?=$rgs_status?></td>
									<td></td>
								</tr>
								<tr>
									<td><div class="form-group"><input type="checkbox" name="delete[<?=$v1->id?>]" value="<?=$v1->id?>" /></div></td>
									<td><input class="form-control" type="text" name="" value="<?=$plan_register_details[0]->participant_name1?>"></td>
									<td><input class="form-control" type="number" name="" value="<?=$plan_register_details[0]->participant_code1?>"></td>
									<td><?=$rgs_status1?></td>
									<td></td>
								</tr>
								<tr>
									<td><div class="form-group"><input type="checkbox" name="delete[<?=$v1->id?>]" value="<?=$v1->id?>" /></div></td>
									<td><input class="form-control" type="text" name="" value="<?=$plan_register_details[0]->participant_name2?>"></td>
									<td><input class="form-control" type="number" name="" value="<?=$plan_register_details[0]->participant_code2?>"></td>
									<td><?=$rgs_status2?></td>
									<td></td>
								</tr>
								<tr>
									<td><div class="form-group"><input type="checkbox" name="delete[<?=$v1->id?>]" value="<?=$v1->id?>" /></div></td>
									<td><input class="form-control" type="text" name="" value="<?=$plan_register_details[0]->participant_name3?>"></td>
									<td><input class="form-control" type="number" name="" value="<?=$plan_register_details[0]->participant_code3?>"></td>
									<td><?=$rgs_status3?></td>
									<td></td>
								</tr>
						 	</tbody>
						</table>
                    </div>

                    <?php 	} ?>
				</div>
				<div class="modal-footer">
					<span class="pull-right margin-left" data-dismiss="modal"><a class="btn btn-success mini-btn" href="account.php" data-toggle="modal" >تأیید</a></span>
				</div>
			</div><!-- end .modal-content -->
		</div><!-- end .modal-dialog -->
	</div><!-- end .modal -->
	<!-- END MODAL -->

	<!-- Plan Panel -->
	<div class="col-xs-12 col-sm-6 col-md-4 pull-right">
		<div class="panel panel-default">
			<div class="panel-heading">
				<strong><?=$pld->title?></strong>
				<span class="pull-left"><?=$pld->id?></span>
			</div>
			<div class="panel-body text-center"><div class="huge" style="font-size: 16px; text-align: justify;"><span><?=$pld->description?>	</span></div></div>	
			<div class="panel-footer">	
				<span class="pull-left" ><a class="btn btn-info mini-btn" href="user_plan.php?id=<?=$pld->id?>">بیشتر</a></span>	
				<span class="pull-right margin-left"><a class="btn btn-success mini-btn" href="#" data-toggle="modal" data-target="#register_modal<?=$pld->id?>" >ثبت نام</a></span>
				<span class="pull-right margin-left"><a class="btn btn-warning mini-btn" href="">لغو ثبت نام</a></span>
				<div class="clearfix"></div>	
			</div> <!-- /panel-footer -->
		</div><!-- /panel -->		
	</div><!-- /col -->


	<?php 
				}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////  End of Cycle through plans  ///////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	?>

	</div>
	</div>

</div> <!-- /container -->
</div> <!-- /#page-wrapper -->

<!-- footers -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

<!-- Place any per-page javascript here -->

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>


<style type="text/css">

	hr{
		width: 100%;
		border: 0.1px solid gray;
	}
	.mini-btn{
		padding: 3px;
	}
	.margin-left{
		margin-left: 3px;
	}
	.equal-btn{
		width: 120px;
	}
	.modal-footer{
		padding: 10px;
	}
	.modal-body input{
		text-align: center;
		margin-bottom: 30px;
	}
	.modal-body table input{
		text-align: center;
		height: auto;
		margin: 0px;
		padding: 0px;
	}
	.modal-header input{
		text-align: center;
		margin-top: 30px;
	}
	.modal-body input[type="checkbox"]{
		margin-top: 20x;
		margin-bottom: 0px;
	}
	.modal-body div.pull-right{
		text-align: center;
	}
	input#total_cost, input#plan_cost{
		height: 60px;
		font-size: 25px;
	}


</style>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript">

//$("#register_modal1").modal({"backdrop": "static"});

		$(document).on('submit', '#register-plan-form', function(event){
			
			event.preventDefault();
			$form = $(this);
			var id = $(this).find('button.submit').attr('id');
			/*
			$.post(document.location.url, $(this).serialize(), function(data){
				//console.log(this);
				$feedback = $('<div>').html(data).find('.modal-feedback');
				//$feedback = $('<div>').html(data).find('#edit_register_modal');
    		  	$form.prepend($feedback);
    		  	//$('#register_modal1').modal('hide');
    		  	$('#edit_register_modal').modal('show');
			});*/




			$.ajax({
			  url: 'account.php',
			  type: "POST",
			  data: $(this).serialize(),
			  success: function(data){
			    $feedback = $('<div>').html(data).find('.modal-error-feedback' + id);
			    $feedback2 = $('<div>').html(data).find('.modal-success-feedback' + id);
			    $('.modal-error-feedback' + id).remove();
			    $form.prepend($feedback[0]);

			    if ($feedback[id-1].childElementCount == 1) {
			    	console.log("|||||||||||||||||||||||||||||||||");
			    }
			    if ($feedback2[id-1].childElementCount == 1) {
			    	$('.modal-success-feedback' + id).remove();
			    	$('#edit_register_modal'+id+' .modal-body').prepend($feedback2[0]);
			    	$('#register_modal'+id).modal('toggle');
			    	$('#edit_register_modal'+id).modal('show');
			    }
			    //$feedback = $('<div>').html(data).find('#edit_register_modal');
			    //$('#register_modal1').modal('hide');
			    //$('#edit_register_modal .modal-body').innerHTML = $form.prepend($feedback);
			    //$('#edit_register_modal .modal-body').innerHTML += "jjjjjjjjjjjjjjjjjjjjjjjj";
			    

			    //console.log(data);
			    //var x = document.querySelector('#edit_register_modal .modal-body');
			    //x.innerHTML = "";
			    //x.appendChild($feedback[0]);
			    //x.innerHTML += "";
			    //console.log(x.innerHTML);
			    console.log($feedback[0].childElementCount);
			    console.log($feedback[0]);
			    console.log($feedback2[0].childElementCount);
			    console.log($feedback2[0]);
			    //console.log($form.prepend($feedback));
			    
			  },
			  error: function(){
			    alert('failure');
			  }
			});

			console.log("+++++++++++++++++++++");
			
		});



	function participant_active(i) {

		var selector = 'participant_choise'+i;
		var participant_choise = document.getElementById(selector);

		selector = 'participant_name'+i;
		var participant_name = document.getElementById(selector);

		selector = 'participant_code'+i;
		var participant_code = document.getElementById(selector);

		selector = 'participant_gender'+i;
		var participant_gender = document.getElementById(selector);

		i+=1;
		var selector = 'participant_choise'+i;
		var participant_choise2 = document.getElementById(selector);

		

		if (participant_choise.checked) {
			participant_name.readOnly = false;
			participant_code.readOnly = false;
			participant_gender.disabled = false;
			addOneParticipantCostToTotalCost()
			if (participant_choise2 != null)
				participant_choise2.disabled = false;
		}else{
			participant_name.readOnly = true;
			participant_code.readOnly = true;
			participant_gender.disabled = true;
			removeOneParticipantCostToTotalCost();
			if (participant_choise2 != null)
				participant_choise2.disabled = true;

			participant_name.value = null;
			participant_code.value = null;
			participant_gender.value = null;
			if (participant_choise2 != null && participant_choise2.checked == true){
				participant_choise2.checked = false;
				participant_active(i);
			}
			
		}
		
	}

	function addOneParticipantCostToTotalCost() {

		var total_cost = document.getElementById('total_cost');
		var participant_cost = document.getElementById('participant_cost1');
		var i = total_cost.value.indexOf(':');
		var j = total_cost.value.indexOf('تومان');
		var total = parseInt(total_cost.value.substring(i+2, j-1));

		j = participant_cost.value.indexOf('تومان');
		total += parseInt(participant_cost.value.substring(0, j-1));

		total_cost.value = "مجموع هزینه ها: " + total +" تومان";
	}
	function removeOneParticipantCostToTotalCost() {

		var total_cost = document.getElementById('total_cost');
		var participant_cost = document.getElementById('participant_cost1');

		var i = total_cost.value.indexOf(':');
		var j = total_cost.value.indexOf('تومان');
		var total = parseInt(total_cost.value.substring(i+2, j-1));

		j = participant_cost.value.indexOf('تومان');
console.log(parseInt(participant_cost.value.substring(0, j-1)));		
		total -= parseInt(participant_cost.value.substring(0, j-1));

		total_cost.value = "مجموع هزینه ها: " + total +" تومان";
	}
</script>
