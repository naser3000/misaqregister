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
$grav = get_gravatar(strtolower(trim($user->data()->email)));
$get_info_id = $user->data()->id;
// $groupname = ucfirst($loggedInUser->title);
$raw = date_parse($user->data()->join_date);
$signupdate = $raw['month']."/".$raw['day']."/".$raw['year'];
$userdetails = fetchUserDetails(NULL, NULL, $get_info_id); //Fetch user details
if ($userdetails->data_completion == 0){
	Redirect::to($us_url_root.'users/data_completion.php');
}
$plansData = fetchAllPlans(); //Fetch information for all plans
///echo "44444444444444444444444444444444444444444444";
 ?>

 <?php
// REGISTER IN PLAN
$successes=[];
$successes2=[];
$data_completion_error1 = "";
$form_valid = TRUE;
$validation = new Validate();
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

		$user_id = Input::get('user_id');
    	$plan_id = Input::get('plan_id');
    	$capacity_id = Input::get('capacity_id');
		$registered_plan_details = fetchPlanRegisterDetails($user_id, $plan_id, $capacity_id);

		if(isset($registered_plan_details[0])){
		// user registered in this plan
			$RPD = $registered_plan_details[0];

			if( isset($_POST['prgs_id']) ) {
				if( isset($_POST['participant_number']) ){
					$prgs_id = Input::get('prgs_id');
					$n = Input::get('participant_number');

					// get paid_cost & capacity_id for plan
					$query = $db->query("SELECT * FROM plan_register WHERE id = $prgs_id");
					$prgs = $query->first();
					$paid_cost = $prgs->paid_cost;
					$capacity_id = $query->first()->capacity_id;

					// get participant_cost
					$participant_cost = fetchCapacityDetails($capacity_id)->participant_cost;

					// return paid cost (for participant) to user
					$charge_fields=array('account_charge'=> ($userdetails->account_charge + $participant_cost) );
					$db->update('users', $userdetails->id, $charge_fields);

					//update plan_register (remove participant and reduse paid cost)
					if($n == 1){
						$paid_fields=array(
							'paid_cost'=> ($paid_cost - $participant_cost),
							('participant_name1') => $prgs->participant_name2,
							('participant_code1') => $prgs->participant_code2,
							('participant_gender1') => $prgs->participant_gender2,
							('reserved_number1') => $prgs->reserved_number2,
							('participant_name2') => $prgs->participant_name3,
							('participant_code2') => $prgs->participant_code3,
							('participant_gender2') => $prgs->participant_gender3,
							('reserved_number2') => $prgs->reserved_number3,
							('participant_name3') => "",
							('participant_code3') => "",
							('participant_gender3') => "",
							('reserved_number3') => 0,);
					}
					if($n == 2){
						$paid_fields=array(
							'paid_cost'=> ($paid_cost - $participant_cost),
							('participant_name2') => $prgs->participant_name3,
							('participant_code2') => $prgs->participant_code3,
							('participant_gender2') => $prgs->participant_gender3,
							('reserved_number2') => $prgs->reserved_number3,
							('participant_name3') => "",
							('participant_code3') => "",
							('participant_gender3') => "",
							('reserved_number3') => 0,);
					}
					if($n == 3){
						$paid_fields=array(
							'paid_cost'=> ($paid_cost - $participant_cost),
							('participant_name3') => "",
							('participant_code3') => "",
							('participant_gender3') => "",
							('reserved_number3') => 0,);
					}
					$db->update('plan_register', $prgs_id, $paid_fields);
				}else {
					$prgs_id = Input::get('prgs_id');
					// get paid cost for plan
					$query = $db->query("SELECT * FROM plan_register WHERE id = $prgs_id");
					$paid_cost = $query->first()->paid_cost;
					// return paid cost to user
					$charge_fields=array('account_charge'=> ($userdetails->account_charge + $paid_cost) );
					$db->update('users', $userdetails->id, $charge_fields);
					//delete user register in plan
					$db->query("DELETE FROM plan_register WHERE id = $prgs_id");
				}
			}

			if ($RPD->participant_name1 == "" & 
				( strlen($_POST['participant_name1']) > 0 || strlen($_POST['participant_code1']) > 0 || strlen($_POST['participant_gender1']) > 0 ) ) {
			// participant1 DONT EXIST (add it)
				$participant_cost1 = Input::get('participant_cost1');

				$plan_capacity = fetchCapacityDetails($capacity_id);
        		$blank_space = $plan_capacity->capacity_number - $plan_capacity->registered;
        		$reserved_number1 = 0;

        		
        		$register_status = "ثبت نام";
        		if ( $blank_space < 1 ){
        			$register_status = "رزرو";
        			$reserved_number1 = $plan_capacity->reserved+1;
        		}

				$displayname = Input::get("participant_name1");
	    	  	$fields=array(
	    	  		'participant_name1'=> Input::get("participant_name1"),
	    	  		'participant_code1'=> Input::get("participant_code1"),
	    	  		'participant_gender1'=> Input::get("participant_gender1"),
	    	  		'status' => $register_status,
	    	  		'paid_cost' => ($RPD->paid_cost + $participant_cost1),
	    	  		'reserved_number1' => $reserved_number1,
	    	  		);
	    	  	$validation->check($_POST,array(
	    	    	'participant_name1' => array(
	    	      	'display' => 'نام همراه 1',
	    	      	'required' => true,
	    	      	'min' => 2,
	    	      	'max' => 35,
	    	    	),
	    	    	'participant_code1' => array(
	    	      	'display' => 'کد ملی همراه 1',
	    	      	'required' => true,
	    	      	'exact' => 10,
	    	    	),
	    	    	'participant_gender1' => array(
	    	      	'display' => 'جنسیت همراه 1',
	    	      	'required' => true,
	    	    	),
	    	  	));
	    		if($validation->passed()){
	    	 		$db->update('plan_register',$RPD->id,$fields);
	    			$successes[] = "همراه 1 به برنامه اضافه شد.";

	    			// update user charge
	    			$charge_fields=array('account_charge'=> ($userdetails->account_charge-$participant_cost1) );
					$db->update('users',$user_id,$charge_fields);

					// update capacity_number
					if ($register_status == "ثبت نام") {
						$fields=array('registered'=> ($plan_capacity->registered + 1) );
						$db->update('capacity',$capacity_id,$fields);
					}elseif ($register_status == "رزرو") {
						$fields=array('reserved'=> ($plan_capacity->reserved + 1) );
						$db->update('capacity',$capacity_id,$fields);
					}

	    		}else{
	    	      	?><div id="form-errors"><?=$validation->display_errors();?></div>
	    	        <?php
	    	  	}
			}
			else if($RPD->participant_name1 != $_POST['participant_name1'] ) {
			// participant1 EXIST (uppdate it)

				if ($RPD->participant_name1 != $_POST['participant_name1']){
			    	$displayname = Input::get("participant_name1");
		    	  	$fields=array('participant_name1'=>$displayname);
		    	  	$validation->check($_POST,array(
		    	    	'participant_name1' => array(
		    	      	'display' => 'نام همراه 1',
		    	      	'required' => true,
		    	      	'min' => 2,
		    	      	'max' => 35
		    	    	)
		    	  	));
		    		if($validation->passed()){
		    	 		$db->update('plan_register',$RPD->id,$fields);
		    			$successes2[] = "نام همراه 1 به روز رسانی شد.";
		    		}else{
		    	      	?><div id="form-errors"><?=$validation->display_errors();?></div>
		    	        <?php
		    	  	}
		    	}

		    	if ($RPD->participant_code1 != $_POST['participant_code1']){
			    	$displayname = Input::get("participant_code1");
		    	  	$fields=array('participant_code1'=>$displayname);
		    	  	$validation->check($_POST,array(
		    	    	'participant_code1' => array(
		    	      	'display' => 'کد ملی همراه 1',
		    	      	'required' => true,
		    	      	'exact' => 10,
		    	    	)
		    	  	));
		    		if($validation->passed()){
		    	 		$db->update('plan_register',$RPD->id,$fields);
		    			$successes2[] = "کد ملی همراه 1 به روز رسانی شد.";
		    		}else{
		    	      	?><div id="form-errors"><?=$validation->display_errors();?></div>
		    	        <?php
		    	  	}
		    	}

		    	if ($RPD->participant_gender1 != $_POST['participant_gender1']){
			    	$displayname = Input::get("participant_gender1");
		    	  	$fields=array('participant_gender1'=>$displayname);
		    	  	$validation->check($_POST,array(
		    	    	'participant_gender1' => array(
		    	      	'display' => 'جنسیت همراه 1',
		    	      	'required' => true,
		    	    	)
		    	  	));
		    		if($validation->passed()){
		    	 		$db->update('plan_register',$RPD->id,$fields);
		    			$successes2[] = "جنسیت همراه 1 به روز رسانی شد.";
		    		}else{
		    	      	?><div id="form-errors"><?=$validation->display_errors();?></div>
		    	        <?php
		    	  	}
		    	}
			}// END OF ELSE ---> participant1 EXIST (uppdate it)

			if ($RPD->participant_name2 == "" & 
				( strlen($_POST['participant_name2']) > 0 || strlen($_POST['participant_code2']) > 0 || strlen($_POST['participant_gender2']) > 0 ) ) {
			// participant2 DONT EXIST (add it)
				$participant_cost2 = Input::get('participant_cost2');

				$plan_capacity = fetchCapacityDetails($capacity_id);
        		$blank_space = $plan_capacity->capacity_number - $plan_capacity->registered;
        		$reserved_number2 = 0;

        		$register_status = "ثبت نام";
        		if ( $blank_space < 1 ){
        			$register_status = "رزرو";
        			$reserved_number2 = $plan_capacity->reserved+1;
        		}

				$displayname = Input::get("participant_name2");
	    	  	$fields=array(
	    	  		'participant_name2'=> Input::get("participant_name2"),
	    	  		'participant_code2'=> Input::get("participant_code2"),
	    	  		'participant_gender2'=> Input::get("participant_gender2"),
	    	  		'status' => ($register_status),
	    	  		'paid_cost' => ($RPD->paid_cost + $participant_cost2),
	    	  		'reserved_number2' => ($reserved_number2),
	    	  		);
	    	  	$validation->check($_POST,array(
	    	    	'participant_name2' => array(
	    	      	'display' => 'نام همراه 2',
	    	      	'required' => true,
	    	      	'min' => 2,
	    	      	'max' => 35,
	    	    	),
	    	    	'participant_code2' => array(
	    	      	'display' => 'کد ملی همراه 2',
	    	      	'required' => true,
	    	      	'exact' => 10,
	    	    	),
	    	    	'participant_gender2' => array(
	    	      	'display' => 'جنسیت همراه 2',
	    	      	'required' => true,
	    	    	),
	    	  	));
	    		if($validation->passed()){
	    	 		$db->update('plan_register', $RPD->id, $fields);
	    			$successes[] = "همراه 2 به برنامه اضافه شد.";

	    			// update user charge
	    			$charge_fields=array('account_charge'=> ($userdetails->account_charge-$participant_cost2) );
					$db->update('users',$user_id,$charge_fields);

					// update capacity_number
					if ($register_status == "ثبت نام") {
						$fields=array('registered'=> ($plan_capacity->registered + 1) );
						$db->update('capacity', $capacity_id, $fields);
					}elseif ($register_status == "رزرو") {
						$fields=array('reserved'=> ($plan_capacity->reserved + 1) );
						$db->update('capacity', $capacity_id, $fields);
					}

	    		}else{
	    	      	?><div id="form-errors"><?=$validation->display_errors();?></div>
	    	        <?php
	    	  	}
			}
			else if ($RPD->participant_name2 != $_POST['participant_name2'] ) {
			// participant2 EXIST (uppdate it)

				if ($RPD->participant_name2 != $_POST['participant_name2']){
			    	$displayname = Input::get("participant_name2");
		    	  	$fields=array('participant_name2'=>$displayname);
		    	  	$validation->check($_POST,array(
		    	    	'participant_name2' => array(
		    	      	'display' => 'نام همراه 2',
		    	      	'required' => true,
		    	      	'min' => 2,
		    	      	'max' => 35
		    	    	)
		    	  	));
		    		if($validation->passed()){
		    	 		$db->update('plan_register',$RPD->id,$fields);
		    			$successes2[] = "نام همراه 2 به روز رسانی شد.";
		    		}else{
		    	      	?><div id="form-errors"><?=$validation->display_errors();?></div>
		    	        <?php
		    	  	}
		    	}

		    	if ($RPD->participant_code2 != $_POST['participant_code2']){
			    	$displayname = Input::get("participant_code2");
		    	  	$fields=array('participant_code2'=>$displayname);
		    	  	$validation->check($_POST,array(
		    	    	'participant_code2' => array(
		    	      	'display' => 'کد ملی همراه 2',
		    	      	'required' => true,
		    	      	'exact' => 10,
		    	    	)
		    	  	));
		    		if($validation->passed()){
		    	 		$db->update('plan_register',$RPD->id,$fields);
		    			$successes2[] = "کد ملی همراه 2 به روز رسانی شد.";
		    		}else{
		    	      	?><div id="form-errors"><?=$validation->display_errors();?></div>
		    	        <?php
		    	  	}
		    	}

		    	if ($RPD->participant_gender2 != $_POST['participant_gender2']){
			    	$displayname = Input::get("participant_gender2");
		    	  	$fields=array('participant_gender2'=>$displayname);
		    	  	$validation->check($_POST,array(
		    	    	'participant_gender2' => array(
		    	      	'display' => 'جنسیت همراه 2',
		    	      	'required' => true,
		    	    	)
		    	  	));
		    		if($validation->passed()){
		    	 		$db->update('plan_register',$RPD->id,$fields);
		    			$successes2[] = "جنسیت همراه 2 به روز رسانی شد.";
		    		}else{
		    	      	?><div id="form-errors"><?=$validation->display_errors();?></div>
		    	        <?php
		    	  	}
		    	}
			}// END OF ELSE ---> participant2 EXIST (uppdate it)

			if ($RPD->participant_name3 == "" & 
				( strlen($_POST['participant_name3']) > 0 || strlen($_POST['participant_code3']) > 0 || strlen($_POST['participant_gender3']) > 0 ) ) {
			// participant3 DONT EXIST (add it)
				$participant_cost3 = Input::get('participant_cost3');

				$plan_capacity = fetchCapacityDetails($capacity_id);
        		$blank_space = $plan_capacity->capacity_number - $plan_capacity->registered;
        		$reserved_number3 = 0;

        		
        		$register_status = "ثبت نام";
        		if ( $blank_space < 1 ){
        			$register_status = "رزرو";
        			$reserved_number3 = $plan_capacity->reserved + 1;
        		}

				$displayname = Input::get("participant_name3");
	    	  	$fields=array(
	    	  		'participant_name3'=> Input::get("participant_name3"),
	    	  		'participant_code3'=> Input::get("participant_code3"),
	    	  		'participant_gender3'=> Input::get("participant_gender3"),
	    	  		'status' => $register_status,
	    	  		'paid_cost' => ($RPD->paid_cost + $participant_cost3),
	    	  		'reserved_number3' => $reserved_number3,
	    	  		);
	    	  	$validation->check($_POST,array(
	    	    	'participant_name3' => array(
	    	      	'display' => 'نام همراه 3',
	    	      	'required' => true,
	    	      	'min' => 2,
	    	      	'max' => 35,
	    	    	),
	    	    	'participant_code3' => array(
	    	      	'display' => 'کد ملی همراه 3',
	    	      	'required' => true,
	    	      	'exact' => 10,
	    	    	),
	    	    	'participant_gender3' => array(
	    	      	'display' => 'جنسیت همراه 3',
	    	      	'required' => true,
	    	    	),
	    	  	));
	    		if($validation->passed()){
	    	 		$db->update('plan_register',$RPD->id,$fields);
	    			$successes[] = "همراه 3 به برنامه اضافه شد.";

	    			// update user charge
	    			$charge_fields=array('account_charge'=> ($userdetails->account_charge - $participant_cost3) );
					$db->update('users',$user_id,$charge_fields);

					// update capacity_number
					if ($register_status == "ثبت نام") {
						$fields=array('registered'=> ($plan_capacity->registered + 1) );
						$db->update('capacity',$capacity_id,$fields);
					}elseif ($register_status == "رزرو") {
						$fields=array('reserved'=> ($plan_capacity->reserved + 1) );
						$db->update('capacity',$capacity_id,$fields);
					}

	    		}else{
	    	      	?><div id="form-errors"><?=$validation->display_errors();?></div>
	    	        <?php
	    	  	}
			}
			else if ( $RPD->participant_name3 != $_POST['participant_name3'] ) {
			// participant3 EXIST (uppdate it)

				if ($RPD->participant_name3 != $_POST['participant_name3']){
			    	$displayname = Input::get("participant_name3");
		    	  	$fields=array('participant_name3'=>$displayname);
		    	  	$validation->check($_POST,array(
		    	    	'participant_name3' => array(
		    	      	'display' => 'نام همراه 3',
		    	      	'required' => true,
		    	      	'min' => 2,
		    	      	'max' => 35
		    	    	)
		    	  	));
		    		if($validation->passed()){
		    	 		$db->update('plan_register',$RPD->id,$fields);
		    			$successes2[] = "نام همراه 3 به روز رسانی شد.";
		    		}else{
		    	      	?><div id="form-errors"><?=$validation->display_errors();?></div>
		    	        <?php
		    	  	}
		    	}

		    	if ($RPD->participant_code3 != $_POST['participant_code3']){
			    	$displayname = Input::get("participant_code3");
		    	  	$fields=array('participant_code3'=>$displayname);
		    	  	$validation->check($_POST,array(
		    	    	'participant_code3' => array(
		    	      	'display' => 'کد ملی همراه 3',
		    	      	'required' => true,
		    	      	'exact' => 10,
		    	    	)
		    	  	));
		    		if($validation->passed()){
		    	 		$db->update('plan_register',$RPD->id,$fields);
		    			$successes2[] = "کد ملی همراه 3 به روز رسانی شد.";
		    		}else{
		    	      	?><div id="form-errors"><?=$validation->display_errors();?></div>
		    	        <?php
		    	  	}
		    	}

		    	if ($RPD->participant_gender3 != $_POST['participant_gender3']){
			    	$displayname = Input::get("participant_gender3");
		    	  	$fields=array('participant_gender3'=>$displayname);
		    	  	$validation->check($_POST,array(
		    	    	'participant_gender3' => array(
		    	      	'display' => 'جنسیت همراه 3',
		    	      	'required' => true,
		    	    	)
		    	  	));
		    		if($validation->passed()){
		    	 		$db->update('plan_register',$RPD->id,$fields);
		    			$successes2[] = "جنسیت همراه 3 به روز رسانی شد.";
		    		}else{
		    	      	?><div id="form-errors"><?=$validation->display_errors();?></div>
		    	        <?php
		    	  	}
		    	}
			}// END OF ELSE ---> participant3 EXIST (uppdate it)


	    	
    	}
    	else
    	{
    		if($userdetails->fname == ""){
    			$data_completion_error1 = 'اطلاعات شما تکمیل نشده است.';
    			$data_completion_error2 = 'لطفاً از قسمت "ویرایش اطلاعات" پروفایل خود را تکمیل کنید.';
    			$data_completion_error3 = 'سپس برای ثبت نام اقدام نمایید.';
    			$data_completion_error4 = '*باتشکر*';
    		}else{
    			$user_id = Input::get('user_id');
		    	$plan_id = Input::get('plan_id');
		    	$capacity_id = Input::get('capacity_id');

		    	$total_cost_str = Input::get('total_cost');
		    	$total_cost_str = explode(": ", $total_cost_str)[1];
		    	$total_cost = explode(" تومان", $total_cost_str)[0];


		    	$participant_name1 = Input::get('participant_name1');
		    	$participant_code1 = Input::get('participant_code1');
		    	$participant_gender1 = Input::get('participant_gender1');

		    	$participant_name2 = Input::get('participant_name2');
		    	$participant_code2 = Input::get('participant_code2');
		    	$participant_gender2 = Input::get('participant_gender2');

		    	$participant_name3 = Input::get('participant_name3');
		    	$participant_code3 = Input::get('participant_code3');
		    	$participant_gender3 = Input::get('participant_gender3');


		    	$participant_requirement1 = false;
		    	$participant_requirement2 = false;
		    	$participant_requirement3 = false;

		    	if ($participant_name1 != "" || $participant_code1 != "" || $participant_gender1 != "" 
		    		|| $participant_name2 != "" || $participant_code2 != "" || $participant_gender2 != ""
		    		|| $participant_name3 != "" || $participant_code3 != "" || $participant_gender3 != "" ){
		    		$participant_requirement1 = true;
		    		if ($participant_name2 != "" || $participant_code2 != "" || $participant_gender2 != "" 
		    			|| $participant_name3 != "" || $participant_code3 != "" || $participant_gender3 != "" ){
		    			$participant_requirement2 = true;
		    			if ($participant_name3 != "" || $participant_code3 != "" || $participant_gender3 != "" )
		    				$participant_requirement3 = true;
		    		}
		    	}
		    	

		   		$form_valid=FALSE; // assume the worst
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
		        			'paid_cost' => $total_cost,
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
						$charge_fields=array('account_charge'=> ($userdetails->account_charge - $total_cost) );
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

    	
      	}
    	}// passed validate
    	}// END OF ELSE ---> (isset($registered_plan_details[0]))
    }//// END OF ELSE ---> (!Token::check($token))
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
		<p><?=$userdetails->account_charge?> ریال</p>
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

			<!--		<span class="pull-left btn-xs" id="plan_id"></span> -->
					<span class="pull-left btn-xs" >کد برنامه: <?=$pld->id?></span>
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

							$rgs = false;

							?>
								<br>
								<h2 class="modal-title">ثبت نام در <?=$pld->title?></h2>
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
					<h2 class="modal-title">ثبت نام در <?=$pld->title?></h2>
					<input class="form-control" type="text" name="" id="plan_cost" readOnly="" value="هزینه <?=$related_capacity->cost?> تومان">
				</div>
				<form class="form-signup"  method="post" action="account.php" id="register-plan-form">
					<div class="modal-body">
						<div class="modal-error-feedback<?=$pld->id?>">
							<?php
							if (isset($plan_id)) {
								if($plan_id == $pld->id){
						           	if (count($validation->errors()) != 0 && Input::exists()){
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
						        	if (count($successes2) != 0 && Input::exists()){
						     ?>
						                <ul class="bg-warning">
						    <?php
						                foreach($successes2 as $message){
						    ?>
						                    <li class="text-success"><?=$message?></li>
						    <?php
						             	}
						    ?>
						         		</ul>
						    <?php
						        	}
						        	if($data_completion_error1 != ""){
						    ?>
						    			<ul class="bg-danger">
						    				<li class="text-warnning"><?=$data_completion_error1?></li>
						    				<li class="text-warnning"><?=$data_completion_error2?></li>
						    				<li class="text-warnning"><?=$data_completion_error3?></li>
						    				<li class="text-warnning"><?=$data_completion_error4?></li>
						    			</ul>
						    <?php
									}
						        }
						    }
						    ?>
						</div>
						<?php
						     if($userdetails->status == "دانشجو"){
						 ?>
							<div class="col-md-3 col-sm-3 col-xs-12 pull-right form-group">
								<label>شماره دانشجویی</label>
								<input class="form-control" type="text" name="" id="stdn" readOnly="" value="<?=$userdetails->std_number?>">
							</div>
						<?php } ?>
						<?php
						     if($userdetails->status == "کارمند"){
						 ?>
							<div class="col-md-3 col-sm-3 col-xs-12 pull-right form-group">
								<label>کد پرسنلی</label>
								<input class="form-control" type="text" name="" id="sempn" readOnly="" value="<?=$userdetails->emp_number?>">
							</div>
						<?php } ?>
						<div class="col-md-3 col-sm-3 col-xs-12 pull-right form-group">
							<label>شماره تماس</label>
							<input class="form-control" type="text" name="" id="phnumber" readOnly="" value="<?=$userdetails->phnumber?>">
						</div>
							
		<?php
			$plan_register_details = fetchPlanRegisterDetails($userdetails->id, $pld->id, $related_capacity->id);
			//print_r($plan_register_details[0]);
			$rgs = isset($plan_register_details[0]);
			$prgs_id = 0;
			if ($rgs)
				$prgs_id = $plan_register_details[0]->id;
			/*
			$i = 1;
			while ($i <= $related_capacity->participant_number) { ?>
						<div class="col-md-1 col-sm-1 col-xs-12 pull-right">
							<label>افزودن همراه</label><br>
							<input type="checkbox" name="participant_choise<?=$i?>" id="participant_choise<?=$i?>" <?php if($i>1){echo 'disabled="disabled"';} ?> onClick="participant_active(<?=$i?>)" value="1">
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12 pull-right">
							<label>نام همراه <?=$i?></label>
							<input class="form-control" type="text" name="participant_name<?=$i?>" id="participant_name<?=$i?>" readOnly="" placeholder="نام" 
							<?php if($rgs){ ?> value="<?=$plan_register_details[0]->participant_name1?>" <?php } ?> >
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12 pull-right">
							<label>کدملی همراه <?=$i?></label>
							<input class="form-control" type="text" name="participant_code<?=$i?>" id="participant_code<?=$i?>" readOnly="" placeholder="کدملی" <?php if($rgs){ ?> value="<?=$plan_register_details[0]->participant_code1?>" <?php } ?> >
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
			<?php $i++;}*/ ?>
			
						
						<div class="clearfix"></div>

					<?php
							$rgs_status[] = [];
							$rgs_status[0] = $rgs_status[1] = $rgs_status[2] = $rgs_status[3] = '---';
							$showing[] = [];
							$showing[0] = $showing[1] = $showing[2] = $showing[3] = false;
							//$showing[] = $showing[1] = $showing[2] = false;
                    		if ($rgs) {
                    			$rgs_status[0] = "ثبت نام ";
                    			if ($plan_register_details[0]->reserved_number > 0)
                    					$rgs_status[0] = "رزرو (".$plan_register_details[0]->reserved_number.")";
                    			if ($plan_register_details[0]->participant_name1 != ""){
                    				$showing[1] = true;
                    				$rgs_status[1] = "ثبت نام ";
                    				if ($plan_register_details[0]->reserved_number1 > 0)
                    					$rgs_status[1] = "رزرو (".$plan_register_details[0]->reserved_number1.")";
                    			}
                    			if ($plan_register_details[0]->participant_name2 != ""){
                    				$showing[2] = true;
                    				$rgs_status[2] = "ثبت نام ";
                    				if ($plan_register_details[0]->reserved_number2 > 0)
                    					$rgs_status[2] = "رزرو (".$plan_register_details[0]->reserved_number2.")";
                    			}
                    			if ($plan_register_details[0]->participant_name3 != ""){
                    				$showing[3] = true;
                    				$rgs_status[3] = "ثبت نام ";
                    				if ($plan_register_details[0]->reserved_number3 > 0)
                    					$rgs_status[3] = "رزرو (".$plan_register_details[0]->reserved_number3.")";
                    			}
                    		}
                    ?> 
                    <div class="table-responsive">
                    	<table class='table table-hover plan<?= $pld->id ?>'>
							<thead>
								<tr>
									<th>نام و نام خانوادگی</th><th>کدملی</th><th>جنسیت</th><th>هزینه (تومان)</th><th>وضعیت</th><th>حذف</th>
								</tr>
							</thead>
							
							<tbody>
								<tr>
									<td><input class="form-control" type="text" name="" value="<?=$userdetails->fname.' '.$userdetails->lname?>" readonly=""></td>
									<td><input class="form-control" type="number" name="" value="<?=$userdetails->icode?>" readonly=""></td>
									<td><input class="form-control" type="text" name="" value="<?=$userdetails->gender?>" readonly=""></td>
									<td><input class="form-control" type="text" name="participant_cost" id="participant_cost" readOnly="" value="<?=$related_capacity->cost?>"></td>
									<td><?=$rgs_status[0]?></td>
									<td><?php if ($rgs) { ?> 
										<span class="pull-right margin-left"><a class="btn btn-danger btn-xs" data-toggle="modal" data-target="#delete_user_register<?=$pld->id?>">لغو ثبت نام</a><?php } ?>

									</td>
								</tr>
								<?php
									$i = 1;
									while($i <= $related_capacity->participant_number){ ?>


								<tr class="<?php if (!$showing[$i]){ echo "hidden"; $add_participant=true;} ?> dont-hidden">
									<td><input class="form-control" type="text" name="participant_name<?=$i?>" id="participant_name<?=$i?>" value="<?php if($rgs) {print_r($plan_register_details[0]->{"participant_name"."$i"});}?>"></td>
									<td><input class="form-control" type="number" name="participant_code<?=$i?>" id="participant_code<?=$i?>" value="<?php if($rgs) {print_r($plan_register_details[0]->{"participant_code"."$i"});}?>"></td>
									<td><Select class="form-control" name="participant_gender<?=$i?>" id="participant_gender<?=$i?>" >
											<option value=""></option>
											<option value="آقا" <?php if ($rgs) { if($plan_register_details[0]->{"participant_gender"."$i"} == "آقا"){ echo "selected";} } ?> >آقا</option>
											<option value="خانم" <?php if ($rgs) { if($plan_register_details[0]->{"participant_gender"."$i"} == "خانم"){ echo "selected";} } ?> >خانم</option>
										</Select>
									</td>
									<td><input class="form-control" type="text" name="participant_cost<?=$i?>" id="participant_cost<?=$i?>" readOnly="" value="<?=$related_capacity->participant_cost?>"></td>
									<td><?=$rgs_status[$i]?></td>
									<td><?php if ($showing[$i]) { ?> 
										<span class="pull-right margin-left" onclick="passId(<?=$pld->id?>, <?=$prgs_id?>, <?=$i?>)"><a class="btn btn-warning btn-xs" data-toggle="modal" data-target="#delete_participant_register<?=$pld->id?>">حذف همراه</a><?php  } else { ?>
										<a href="#" class="btn btn-warning btn-xs" id="add_participant" onclick="remove_participant(<?=$pld->id?>, <?=$i?>, <?=$userdetails->account_charge?>)"><span class="glyphicon glyphicon-remove"></span></a>
										<?php } ?>
									</td>
								</tr>

								<?php 
										$i++;
									} 
								?>



								<tr id="add_participant" class="<?php if (!$add_participant) {echo "hidden";} ?>" >
									<td>
										<a href="#" class="btn btn-success btn-xs" id="add_participant" onclick="add_participant(<?=$pld->id?>, <?=$userdetails->account_charge?>)">
											<span class="glyphicon glyphicon-plus">&nbsp;</span>اضافه کردن همراه
										</a>
									</td>
								</tr>
						 	</tbody>
						</table>
                    </div>
                    

						<input class="form-control" type="text" name="total_cost" id="total_cost" readOnly="" value="مجموع هزینه ها: <?php if($rgs) {print_r($plan_register_details[0]->paid_cost); } else {print_r($related_capacity->cost);} ?> تومان" style="margin: 0px;">
						<span class="col-xs-12 bg-danger text-danger hidden" id="charge_error">موجودی حساب شما کافی نمی باشد. لطفاً حساب خود را شارژ کنید.</span><br>
						
					</div><!-- end .modal-body -->
					<div class="modal-footer">
						<input type="hidden" value="<?=$userdetails->id;?>" name="user_id">
						<input type="hidden" value="<?=$related_capacity->plan_id?>" name="plan_id">
						<input type="hidden" value="<?=$related_capacity->id?>" name="capacity_id">
						<input type="hidden" value="<?=Token::generate();?>" name="csrf">
						<?php if($rgs) { ?>
							<button class="submit btn btn-primary btn-xs register-update" id="<?=$pld->id?>" type="submit" <?php if($related_capacity->cost > $userdetails->account_charge){ echo "disabled=''";} ?> >به روز رسانی</button>
						<?php } else{ ?>
							<button class="submit btn btn-success btn-xs register-plan" id="<?=$pld->id?>" type="submit" <?php if($related_capacity->cost > $userdetails->account_charge){ echo "disabled=''";} ?> >ثبت نام و پرداخت</button>
						<?php } ?>
						<span class="close pull-left" data-dismiss="modal"><a href="#" class="btn btn-danger btn-xs">انصراف</a></span>
					</div>
				</form>
				
			</div><!-- end .modal-content -->
		</div><!-- end .modal-dialog -->
	</div><!-- end .modal -->
	<!-- END of MODAL -->

					
	<!--  MODAL POPUP -->
	<div class="modal" id="delete_user_register<?=$pld->id?>">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
				</div>
				<div class="modal-body">
					با لغو کردن ثبت نام، شما و همه همراهان از برنامه حذف خواهید شد، آیا از این کار مطمئن هستید؟
				</div>
				<div class="modal-footer">
					<span class="pull-left" data-dismiss="modal" onclick="remove_user_register(<?=$prgs_id?>)"><a class="btn btn-danger btn-xs" data-toggle="modal" >بله، ثبت نام لغو شود</a></span>
				</div>
			</div><!-- end .modal-content -->
		</div><!-- end .modal-dialog -->
	</div><!-- end .modal -->
	<!-- END MODAL -->

	<div class="modal" id="delete_participant_register<?=$pld->id?>">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
				</div>
				<div class="modal-body">
					آیا مطمئن هستید می خواهید همراه خود را از برنامه حذف کنید؟
				</div>
				<div class="modal-footer">
					<span class="pull-left" data-dismiss="modal" onclick="remove_participant_register(<?=$prgs_id?>, iii)"><a class="btn btn-danger btn-xs" data-toggle="modal" >بله، همراه حذف شود</a></span>
				</div>
			</div><!-- end .modal-content -->
		</div><!-- end .modal-dialog -->
	</div><!-- end .modal -->
	<!-- END MODAL -->

	<!--  MODAL POPUP -->
	<div class="modal" id="edit_register_modal<?=$pld->id?>">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h2>اضافه شدن به برنامه</h2>
				</div>
				<div class="modal-body">
					<div class="modal-success-feedback<?=$pld->id?>">
					<?php
					if (isset($plan_id)) {
						if($plan_id == $pld->id){
                        	if (!$form_valid && Input::exists() ){
 								
                        	}else if(count($successes) > 0){
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
				</div>
				<div class="modal-footer">
					<span class="pull-left margin-left" data-dismiss="modal"><a class="btn btn-success btn-xs" data-toggle="modal" >تأیید</a></span>
				</div>
			</div><!-- end .modal-content -->
		</div><!-- end .modal-dialog -->
	</div><!-- end .modal -->
	<!-- END MODAL -->

					<?php
						}
						// end of ELSE (can register)
					?>

	<!-- Plan Panel -->
	<div class="col-xs-12 col-sm-6 col-md-4 pull-right">
		<div class="panel panel-default">
			<div class="panel-heading">
				<strong><?=$pld->title?></strong>
				<span class="pull-left"><?=$pld->id?></span>
			</div>
			<div class="panel-body text-center"><div class="huge" style="font-size: 16px; text-align: justify;"><span><?=$pld->description?>	</span></div></div>	
			<div class="panel-footer">	
				<span class="pull-left" ><a class="btn btn-info btn-xs" href="user_plan.php?id=<?=$pld->id?>">بیشتر</a></span>
				<?php if($rgs) { ?>
				<span class="pull-right margin-left"><a class="btn btn-warning btn-xs" href="#" data-toggle="modal" data-target="#register_modal<?=$pld->id?>" >ویرایش ثبت نام</a></span>

				<?php } else{ ?>
				<span class="pull-right margin-left"><a class="btn btn-success btn-xs" href="#" data-toggle="modal" data-target="#register_modal<?=$pld->id?>" >ثبت نام</a></span>
				<?php } ?>

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
		margin-bottom: 0px;
	}
	.modal-body th{
		text-align: center;
		padding: 0px;
	}
	.modal-body table input, .modal-body table select{
		text-align: center;
		height: 40px;
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
	span.glyphicon{
		margin-top: 5px;
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



		$.ajax({
			url: 'account.php',
			type: "POST",
			data: $(this).serialize(),
			success: function(data){

			  	console.log(data)

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

				console.log($feedback[0].childElementCount);
				console.log($feedback[0]);
				console.log($feedback2[0].childElementCount);
				console.log($feedback2[0]);
				//console.log($form.prepend($feedback));
				//location.reload();
			},
			error: function(){
				alert('failure');
			}
		});

		console.log("+++++++++++++++++++++");
		
	});

	function remove_user_register(prgs_id){
		
		$.ajax({
		 	url: 'account.php',
		 	type: "POST",
		 	data: {'prgs_id' : prgs_id},
		 	success: function(){
		 		location.reload();
		 	},
		 	error: function(){
		 		alert('failure');
		 	}
		});
	}
	function remove_participant_register(prgs_id, id){
		
		$.ajax({
		 	url: 'account.php',
		 	type: "POST",
		 	data: {'prgs_id' : prgs_id, 'participant_number'  : id},
		 	success: function(){
		 		location.reload();
		 	},
		 	error: function(){
		 		alert('failure');
		 	}
		});
	}

	function add_participant(plan_id, account_charge) {

		if ($('table.plan'+plan_id+' tr.hidden').length == 0) {
			$('table.plan'+plan_id+' tr#add_participant').addClass('hidden');
		}else{
			$('table.plan'+plan_id+' tr.hidden')[0].classList.remove('hidden');
			changeOneParticipantCostToTotalCost(plan_id, +1, account_charge)
			if ($('table.plan'+plan_id+' tr.hidden').length == 0)
				$('table.plan'+plan_id+' tr#add_participant').addClass('hidden');
		}
	}

	function remove_participant(plan_id, i, account_charge) {
		var l1 = $('table.plan'+plan_id+' tr.hidden.dont-hidden').length;
		var l2 = $('table.plan'+plan_id+' tr.dont-hidden').length
		console.log(l1 + "jjj" + l2);
		console.log($('table.plan'+plan_id+' tr.dont-hidden'));
		$('table.plan'+plan_id+' tr.dont-hidden')[l2 - l1 - 1].classList.add('hidden');
		if(i == 1){
			console.log(i + "***");
			if(l2 > 1){
				$('table.plan'+plan_id+' #participant_name1')[0].value = $('table.plan'+plan_id+' #participant_name2')[0].value;
				$('table.plan'+plan_id+' #participant_code1')[0].value = $('table.plan'+plan_id+' #participant_code2')[0].value;
				$('table.plan'+plan_id+' #participant_gender1')[0].value = $('table.plan'+plan_id+' #participant_gender2')[0].value;
			}
			if (l2 > 2) {
				$('table.plan'+plan_id+' #participant_name2')[0].value = $('table.plan'+plan_id+' #participant_name3')[0].value;
				$('table.plan'+plan_id+' #participant_code2')[0].value = $('table.plan'+plan_id+' #participant_code3')[0].value;
				$('table.plan'+plan_id+' #participant_gender2')[0].value = $('table.plan'+plan_id+' #participant_gender3')[0].value;
			}
		}
		if(i == 2 & l2 > 2){
			$('table.plan'+plan_id+' #participant_name2')[0].value = $('table.plan'+plan_id+' #participant_name3')[0].value;
			$('table.plan'+plan_id+' #participant_code2')[0].value = $('table.plan'+plan_id+' #participant_code3')[0].value;
			$('table.plan'+plan_id+' #participant_gender2')[0].value = $('table.plan'+plan_id+' #participant_gender3')[0].value;
		}
		changeOneParticipantCostToTotalCost(plan_id, -1, account_charge);
		deleteText($('table.plan'+plan_id+' tr.dont-hidden')[l2 - 1]);
		if ($('table.plan'+plan_id+' tr.hidden').length > 0)
			$('table.plan'+plan_id+' tr#add_participant').removeClass('hidden');
	}


	function changeOneParticipantCostToTotalCost(plan_id, sign, account_charge) {

		var total_cost = document.querySelector('#register_modal'+plan_id+' #total_cost');
		var participant_cost = document.querySelector('#register_modal'+plan_id+' #participant_cost1');
		var i = total_cost.value.indexOf(':');
		var j = total_cost.value.indexOf('تومان');
		var total = parseInt(total_cost.value.substring(i+2, j-1));

		total += parseInt(participant_cost.value) * sign;

		total_cost.value = "مجموع هزینه ها: " + total +" تومان";
		if (total > account_charge) {
			$('#register_modal'+plan_id+' #charge_error').removeClass('hidden');
			if($('button.register-plan#'+plan_id)[0] != undefined)
				$('button.register-plan#'+plan_id)[0].disabled = true;
			if($('button.register-update#'+plan_id)[0] != undefined)
				$('button.register-update#'+plan_id)[0].disabled = true;
		}else{
			$('#register_modal'+plan_id+' #charge_error').addClass('hidden');
			if($('button.register-plan#'+plan_id)[0] != undefined)
				$('button.register-plan#'+plan_id)[0].disabled = false;
			if($('button.register-update#'+plan_id)[0] != undefined)
				$('button.register-update#'+plan_id)[0].disabled = false;
		}
	}
	/*function removeOneParticipantCostToTotalCost(plan_id) {
		
		var total_cost = document.querySelector('#register_modal'+plan_id+' #total_cost');
		var participant_cost = document.querySelector('#register_modal'+plan_id+' #participant_cost1');
		var i = total_cost.value.indexOf(':');
		var j = total_cost.value.indexOf('تومان');
		var total = parseInt(total_cost.value.substring(i+2, j-1));

		total -= parseInt(participant_cost.value);

		total_cost.value = "مجموع هزینه ها: " + total +" تومان";
	}*/
	function deleteText(el){
		var x = el.querySelectorAll('input');
		x[0].value = "";
		x[1].value = "";
		var y = el.querySelector('select');
		y.selectedIndex  = 0;
	}

	function passId(plan_id, prgs_id, id){
		var modal = document.querySelector('#delete_participant_register'+plan_id+' span');
		//modal.attr('onclick','').unbind('click');
		console.log(modal);
		console.log(modal.onclick);
		modal.onclick = function(event) {remove_participant_register(prgs_id, id)};
		console.log(modal);
	}
</script>
