<?php require_once 'init.php'; ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/header.php'; ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; ?>

<?php if (!securePage($_SERVER['PHP_SELF'])){die();} ?>
<?php
$validation = new Validate();
//PHP Goes Here!
$errors = [];
$successes = [];

$planId = Input::get('id');

//Check if selected user exists
if(!planIdExists($planId)){
  Redirect::to("admin_plans.php"); die();
}

$plandetails = fetchPlanDetails(NULL, $planId); //Fetch plan details
$planCapacitiesData = fetchAllPlanCapacities($planId); //Fetch information for all capacities of plan

//Forms posted
if(!empty($_POST)) {
    $token = $_POST['csrf'];
    if(!Token::check($token)){
      die('Token doesn\'t match!');
    }else {

    
//Update plan title
    if ($plandetails->title != $_POST['title']){
      $displayname = Input::get("title");

      $fields=array('title'=>$displayname);
      $validation->check($_POST,array(
        'title' => array(
          'display' => 'عنوان برنامه',
          'required' => true,
          'max' => 100,
        )
      ));
    if($validation->passed()){
      $db->update('plans',$planId,$fields);
     $successes[] = "عنوان برنامه به روز رسانی شد.";
    }else{
          $errors[] = $validation->show_errors();
      }
    }

//Update plan description
    if ($plandetails->description." " != $_POST['description']){
       $displayname = Input::get("description");

      $fields=array('description'=>$displayname);
      $validation->check($_POST,array(
        'description' => array(
          'display' => 'توضیحات',
          'required' => true,
          'min' => 20,
          'max' => 500,
        )
      ));
    if($validation->passed()){
      $db->update('plans',$planId,$fields);
      $successes[] = "توضیحات به روز رسانی شد.";
    }else{
          $errors[] = $validation->show_errors();
      }
    }

//Update register_start_date

    if ($plandetails->register_start_date != str_replace("/", "-", $_POST['register_start_date'])){
      $displayname = Input::get("register_start_date");

      $fields=array('register_start_date'=>$displayname);
      $validation->check($_POST,array(
        'register_start_date' => array(
          'display' => 'تاریخ شروع ثبت نام',
          'after_equal' => gregorian_to_jalali(explode('/', date("Y/m/d"))),
          'before_equal' => $_POST['register_end_date'],
          'required' => true,
        )
      ));
    if($validation->passed()){
      $db->update('plans',$planId,$fields);
      $successes[] = "تاریخ شروع ثبت نام به روز رسانی شد.";
    }else{
          $errors[] = $validation->show_errors();
      }
    }

//Update register_end_date

    if ($plandetails->register_end_date != str_replace("/", "-", $_POST['register_end_date'])){
      $displayname = Input::get("register_end_date");

      $fields=array('register_end_date'=>$displayname);
      $validation->check($_POST,array(
        'register_end_date' => array(
          'display' => 'تاریخ پایان ثبت نام',
          'after_equal' => $_POST['register_start_date'],
          'before_equal' => $_POST['confirm_end_date'],
          'required' => true,
        )
      ));
    if($validation->passed()){
      $db->update('plans',$planId,$fields);
      $successes[] = "تاریخ پایان ثبت نام به روز رسانی شد.";
    }else{
          $errors[] = $validation->show_errors();
      }
    }


//Update confirm_end_date
    if ($plandetails->confirm_end_date != str_replace("/", "-", $_POST['confirm_end_date'])){
      $displayname = Input::get("confirm_end_date");

      $fields=array('confirm_end_date'=>$displayname);
      $validation->check($_POST,array(
        'confirm_end_date' => array(
          'display' => 'تاریخ مهلت لغو ثبت نام',
          'after_equal' => $_POST['register_end_date'],
          'before_equal' => $_POST['plan_start_date'],
          'required' => true,
        )
      ));
    if($validation->passed()){
      $db->update('plans',$planId,$fields);
      $successes[] = "تاریخ مهلت لغو ثبت نام به روز رسانی شد.";
    }else{
          $errors[] = $validation->show_errors();
      }
    }

//Update plan_start_date
    if ($plandetails->plan_start_date != str_replace("/", "-", $_POST['plan_start_date'])){
      $displayname = Input::get("plan_start_date");

      $fields=array('plan_start_date'=>$displayname);
      $validation->check($_POST,array(
        'plan_start_date' => array(
          'display' => 'تاریخ شروع برنامه',
          'after_equal' => $_POST['confirm_end_date'],
          'before_equal' => $_POST['plan_end_date'],
          'required' => true,
        )
      ));
    if($validation->passed()){
      $db->update('plans',$planId,$fields);
      $successes[] = "تاریخ شروع برنامه به روز رسانی شد.";
    }else{
          $errors[] = $validation->show_errors();
      }
    }

//Update plan_end_date
    if ($plandetails->plan_end_date != str_replace("/", "-", $_POST['plan_end_date'])){
      $displayname = Input::get("plan_end_date");

      $fields=array('plan_end_date'=>$displayname);
      $validation->check($_POST,array(
        'plan_end_date' => array(
          'display' => 'تاریخ پایان برنامه',
          'required' => true,
          'after_equal' => $_POST['plan_start_date'],
        )
      ));
    if($validation->passed()){
      $db->update('plans',$planId,$fields);
      $successes[] = "تاریخ پایان برنامه به روز رسانی شد.";
    }else{
          $errors[] = $validation->show_errors();
      }
    }

//Update register_start_time
    if ($plandetails->register_start_time != ($_POST['register_start_time']+":00")){
      $displayname = Input::get("register_start_time");

      $fields=array('register_start_time'=>$displayname);
      $validation->check($_POST,array(
        'register_start_time' => array(
          'display' => 'زمان شروع ثبت نام',
          'required' => true,
        )
      ));
    if($validation->passed()){
      $db->update('plans',$planId,$fields);
      $successes[] = "زمان شروع ثبت نام به روز رسانی شد.";
    }else{
          $errors[] = $validation->show_errors();
      }
    }

//Update register_end_time
    if ($plandetails->register_end_time != ($_POST['register_end_time']+":00")){
      $displayname = Input::get("register_end_time");

      $fields=array('register_end_time'=>$displayname);
      $validation->check($_POST,array(
        'register_end_time' => array(
          'display' => 'زمان پایان ثبت نام',
          'required' => true,
        )
      ));
    if($validation->passed()){
      $db->update('plans',$planId,$fields);
      $successes[] = "زمان پایان ثبت نام به روز رسانی شد.";
    }else{
          $errors[] = $validation->show_errors();
      }
    }

//Update confirm_end_time
    if ($plandetails->confirm_end_time != ($_POST['confirm_end_time']+":00")){
      $displayname = Input::get("confirm_end_time");

      $fields=array('confirm_end_time'=>$displayname);
      $validation->check($_POST,array(
        'confirm_end_time' => array(
          'display' => 'زمان مهلت لغو ثبت نام',
          'required' => true,
        )
      ));
    if($validation->passed()){
      $db->update('plans',$planId,$fields);
      $successes[] = "زمان مهلت لغو ثبت نام به روز رسانی شد.";
    }else{
          $errors[] = $validation->show_errors();
      }
    }

//Update plan_start_time
    if ($plandetails->plan_start_time != ($_POST['plan_start_time']+":00")){
      $displayname = Input::get("plan_start_time");

      $fields=array('plan_start_time'=>$displayname);
      $validation->check($_POST,array(
        'plan_start_time' => array(
          'display' => 'زمان شروع برنامه',
          'required' => true,
        )
      ));
    if($validation->passed()){
      $db->update('plans',$planId,$fields);
      $successes[] = "زمان شروع برنامه به روز رسانی شد.";
    }else{
          $errors[] = $validation->show_errors();
      }
    }


//Update plan_end_time
    if ($plandetails->plan_end_time != ($_POST['plan_end_time']+":00")){
      $displayname = Input::get("plan_end_time");

      $fields=array('plan_end_time'=>$displayname);
      $validation->check($_POST,array(
        'plan_end_time' => array(
          'display' => 'زمان پایان برنامه',
          'required' => true,
        )
      ));
    if($validation->passed()){
      $db->update('plans',$planId,$fields);
      $successes[] = "زمان پایان برنامه به روز رسانی شد.";
    }else{
          $errors[] = $validation->show_errors();
      }
    }


    $i = 0;
    $capacity_row = Input::get('send_to_db0');
        
        while (strlen($capacity_row) > 0) {
          $pieces_of_row = explode("|", $capacity_row);
          $capacity_fields=array(
          'status' => $pieces_of_row[0],
          'yinter' => $pieces_of_row[1],
          'gender' => $pieces_of_row[2],
          'cost' => (int)$pieces_of_row[3],
          'capacity_number' => (int)$pieces_of_row[4],
          'participant_number' => (int)$pieces_of_row[5],
          'participant_cost' => (int)$pieces_of_row[6],
          'plan_id' => $planId,
        );

          $db->insert('capacity',$capacity_fields);
          
          $i++;
          $next_data = "send_to_db".(string)$i;
          $capacity_row = Input::get($next_data);
        }
        if ($i >= 1)
        	$successes[] = "تعداد ".(string)$i." ظرفیت به برنامه اضافه شد.";

  }
  $plandetails = fetchPlanDetails(NULL, $planId); //Fetch plan details
  $planCapacitiesData = fetchAllPlanCapacities($planId);
}

//
?>
<div id="page-wrapper">

<div class="container">

<?=resultBlock($errors,$successes);?>


<div class="row">

	<div class="col-xs-12 col-sm-12">
	<form class="form" name='adminUser' action='admin_plan.php?id=<?=$planId?>' method='post'>

	<h3>مشخصات برنامه</h3>
	<div class="panel panel-default">
	<div class="panel-heading">ID برنامه: <?=$plandetails->id?></div>
	<div class="panel-body">

		<div class="row spec-row">
            <div class="col-xs-6" >
            	<label>نام برنامه</label>
                <input  class="form-control" type="text" name="title" id="title" placeholder="نام برنامه" value="<?=$plandetails->title?>">
            </div>        
            <div class="col-xs-12">
            	<label>توضیحات</label>
                <textarea class="form-control" id="description" name="description" placeholder="توضیحات" rows="5"
                ><?=$plandetails->description?></textarea>
            </div>
        </div>

	</div>

	</div>

	<h3></h3>
	<div class="panel panel-default datetime-group">
	<div class="panel-heading">زمان برنامه</div>
	<div class="panel-body">

		<div class="row text-center">
            <div class="col-xs-2">
                <p></p>
            </div>
            <div class="col-xs-2">
                <p>شروع ثبت نام</p>
            </div>
            <div class="col-xs-2 ">
                <p>مهلت لغو ثبت نام</p>
            </div>
            <div class="col-xs-2 ">
                <p>پایان ثبت نام</p>
            </div>
            <div class="col-xs-2 ">
                <p>شروع برنامه</p>
            </div>
            <div class="col-xs-2 ">
                <p>پایان برنامه</p>
            </div>
        </div>


        <div class="row">
            <div class="col-xs-2">
                <p>تاریخ</p>
            </div>

             <div class="col-xs-2 ">                    
                <div class='input-group date' id='datepicker' >
                    <span class="input-group-addon" id="register_start_date_btn" >
                        <span class="glyphicon glyphicon-calendar" ></span>
                    </span>
                    <input type='text' class="form-control" name="register_start_date" id="register_start_date" value="<?=str_replace("-", "/", $plandetails->register_start_date)?>" readonly />
                </div>
            </div>

            <div class="col-xs-2 ">                    
                <div class='input-group date' id='datepicker' >
                    <span class="input-group-addon" id="confirm_end_date_btn">
                        <span class="glyphicon glyphicon-calendar" id="confirm_end_date_btn"></span>
                    </span>
                    <input type='text' class="form-control" name="confirm_end_date" id="confirm_end_date" value="<?=str_replace("-", "/", $plandetails->confirm_end_date)?>" readonly />
                </div>
            </div>

            <div class="col-xs-2 ">                    
                <div class='input-group date' id='datepicker' >
                    <span class="input-group-addon" id="register_end_date_btn" >
                        <span class="glyphicon glyphicon-calendar" ></span>
                    </span>
                    <input type='text' class="form-control" name="register_end_date" id="register_end_date" value="<?=str_replace("-", "/", $plandetails->register_end_date)?>" readonly />
                </div>
            </div>

            <div class="col-xs-2 ">                    
                <div class='input-group date' id='datepicker' >
                    <span class="input-group-addon" id="plan_start_date_btn">
                        <span class="glyphicon glyphicon-calendar" ></span>
                    </span>
                    <input type='text' class="form-control" name="plan_start_date" id="plan_start_date" value="<?=str_replace("-", "/", $plandetails->plan_start_date)?>" readonly />
                </div>
            </div>

            <div class="col-xs-2 ">                    
                <div class='input-group date' id='datepicker' >
                    <span class="input-group-addon" id="plan_end_date_btn">
                        <span class="glyphicon glyphicon-calendar" ></span>
                    </span>
                    <input type='text' class="form-control" name="plan_end_date" id="plan_end_date" value="<?=str_replace("-", "/", $plandetails->plan_end_date)?>" readonly />
                </div>
            </div>

        </div> <!-- End of row  -->



        <div class="row" >
            <div class="col-xs-2">
                <p>ساعت</p>
            </div>

            <div class="col-xs-2">                    
                <div class='input-group date' id='timepicker' >
                    <span class="input-group-addon" id="register_start_time_btn">
                        <span class="glyphicon glyphicon-time" ></span>
                    </span>     
                    <input type='text' class="form-control" name="register_start_time" id="register_start_time" value="<?=$plandetails->register_start_time?>"/>
                </div>
            </div>

            <div class="col-xs-2">                    
                <div class='input-group date' id='timepicker' >
                    <span class="input-group-addon" id="confirm_end_time_btn">
                        <span class="glyphicon glyphicon-time" ></span>
                    </span>
                    <input type='text' class="form-control" name="confirm_end_time" id="confirm_end_time" value="<?=$plandetails->confirm_end_time?>" />
                </div>
            </div>

            <div class="col-xs-2">                    
                <div class='input-group date' id='timepicker' >
                    <span class="input-group-addon" id="register_end_time_btn">
                        <span class="glyphicon glyphicon-time" ></span>
                    </span>
                    <input type='text' class="form-control" name="register_end_time" id="register_end_time" value="<?=$plandetails->register_end_time?>"/>
                </div>
            </div>

            <div class="col-xs-2">                    
                <div class='input-group date' id='timepicker' >
                    <span class="input-group-addon" id="plan_start_time_btn">
                        <span class="glyphicon glyphicon-time" ></span>
                    </span>
                    <input type='text' class="form-control" name="plan_start_time" id="plan_start_time" value="<?=$plandetails->plan_start_time?>" />
                </div>
            </div>

            <div class="col-xs-2">                    
                <div class='input-group date' id='timepicker' >
                    <span class="input-group-addon" id="plan_end_time_btn">
                        <span class="glyphicon glyphicon-time" ></span>
                    </span>
                    <input type='text' class="form-control" name="plan_end_time" id="plan_end_time" value="<?=$plandetails->plan_end_time?>" />
                </div>
            </div>                      

        </div>

	</div>
	</div>

	<div class="panel panel-default">
	<div class="panel-heading">تعیین ظرفیت جدید</div>
	<div class="panel-body">

		<div class="row capacity-row">
                
                        <div class="col-xs-12 col-md-8 col-lg-6 capacity">
                            <div class="input-group">
                                <select name="" id="status" class="multiselect-ui form-control" multiple="multiple" onchange="changeStatusItems()" >
                                    <option>  فارغ التحصیل</option>
                                    <option>  دانشجو</option>
                                    <option>  کارمند</option>
                                    <option>  استاد</option>
                                    <option>  آزاد</option>
                                </select>
                                <span class="input-group-addon" >
                                    <span class="capacity" >وضعیت</span>
                                </span>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 capacity">
                            <div class="input-group">
                                <select name="" class="multiselect-ui form-control" id="yinter" multiple="multiple" >
                                    <option  value="بدون اهمیت">بدون اهمیت</option><option  value="کارشناسی">کارشناسی</option><option  value="کارشناسی ارشد">کارشناسی ارشد</option><option  value="دکترا">دکترا</option>
                                    <option  value="90">90</option><option  value="کارشناسی (90)">کارشناسی (90)</option><option  value="کارشناسی ارشد (90)">کارشناسی ارشد (90)</option><option  value="دکترا (90)">دکترا (90)</option>
                                    <option  value="91">91</option><option  value="کارشناسی (91)">کارشناسی (91)</option><option  value="کارشناسی ارشد (91)">کارشناسی ارشد (91)</option><option  value="دکترا (91)">دکترا (91)</option>
                                    <option  value="92">92</option><option  value="کارشناسی (92)">کارشناسی (92)</option><option  value="کارشناسی ارشد (92)">کارشناسی ارشد (92)</option><option  value="دکترا (92)">دکترا (92)</option>
                                    <option  value="93">93</option><option  value="کارشناسی (93)">کارشناسی (93)</option><option  value="کارشناسی ارشد (93)">کارشناسی ارشد (93)</option><option  value="دکترا (93)">دکترا (93)</option>
                                    <option  value="94">94</option><option  value="کارشناسی (94)">کارشناسی (94)</option><option  value="کارشناسی ارشد (94)">کارشناسی ارشد (94)</option><option  value="دکترا (94)">دکترا (94)</option>
                                    <option  value="95">95</option><option  value="کارشناسی (95)">کارشناسی (95)</option><option  value="کارشناسی ارشد (95)">کارشناسی ارشد (95)</option><option  value="دکترا (95)">دکترا (95)</option>
                                    <option  value="96">96</option><option  value="کارشناسی (96)">کارشناسی (96)</option><option  value="کارشناسی ارشد (96)">کارشناسی ارشد (96)</option><option  value="دکترا (96)">دکترا (96)</option>
                                    <option  value="97">97</option><option  value="کارشناسی (97)">کارشناسی (97)</option><option  value="کارشناسی ارشد (97)">کارشناسی ارشد (97)</option><option  value="دکترا (97)">دکترا (97)</option>
                                    <option  value="98">98</option><option  value="کارشناسی (98)">کارشناسی (98)</option><option  value="کارشناسی ارشد (98)">کارشناسی ارشد (98)</option><option  value="دکترا (98)">دکترا (98)</option>
                                    <option  value="99">99</option><option  value="کارشناسی (99)">کارشناسی (99)</option><option  value="کارشناسی ارشد (99)">کارشناسی ارشد (99)</option><option  value="دکترا (99)">دکترا (99)</option>
                                </select>
                                <span class="input-group-addon" >
                                    <span class="capacity" >سال ورود</span>
                                </span>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 capacity">
                            <div class="input-group">
                                <select id="gender" class="form-control">
                                    <option value="آقا, خانم">آقا, خانم</option>
                                    <option value="آقا">آقا</option>
                                    <option value="خانم">خانم</option>
                                </select>
                                <span class="input-group-addon" >
                                    <span class="capacity" >جنسیت</span>
                                </span>
                            </div>
                        </div>
                 
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 capacity">
                            <div class="input-group">
                                <span class="input-group-addon" >
                                    <span class="capacity" >تومان</span>
                                </span>
                                <input type="number" id="cost" class="form-control" >
                                <span class="input-group-addon" >
                                    <span class="capacity" >هزینه</span>
                                </span>
                            </div>
                        </div>
    
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 capacity">
                            <div class="input-group">
                                <span class="input-group-addon" >
                                    <span class="capacity" >نفر</span>
                                </span>
                                <input type="number" id="capacity_number" class="form-control" >
                                <span class="input-group-addon" >
                                    <span class="capacity" >ظرفیت</span>
                                </span>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 capacity">
                            <div class="input-group">
                                <span class="input-group-addon" >
                                    <span class="capacity" >نفر</span>
                                </span>
                                <input type="number" id="participant_number" class="form-control" >
                                <span class="input-group-addon" >
                                    <span class="capacity" >تعداد همراه</span>
                                </span>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 capacity">
                            <div class="input-group">
                                <span class="input-group-addon" >
                                    <span class="capacity" >تومان</span>
                                </span>
                                <input type="number" id="participant_cost" class="form-control" >
                                <span class="input-group-addon" >
                                    <span class="capacity" >هزینه همراه</span>
                                </span>
                            </div>
                        </div>
                    </div> <!-- End of row  -->
                    <input class='btn btn-success' type='button' name='' onclick="addCapacity()" value='اضافه کردن ظرفیت' />

	</div>
	</div>

	<div class="panel panel-default">
	<div class="panel-heading">ظرفیت(های) تعیین شده</div>
	<div class="panel-body">
		<div class="allutable table-responsive" style="align-content: right;">
					<table class='table table-hover table-list-search'>
					<thead>
					<tr>
						<th>وضعیت</th><th>سال ورودی</th><th>جنسیت</th><th>هزینه</th><th>هزینه همراه</th><th>تعداد همراه</th><th>تعداد ثبت نام</th><th>صف رزرو</th><th>ظرفیت</th>
					 </tr>
					</thead>
				 <tbody>
					<?php
					//Cycle through capacities of plan
					foreach ($planCapacitiesData as $v1) {
							?>
					<tr>
					<td><a href='admin_capacity.php?id=1'><?=$v1->status?></a></td>
					<td><?=$v1->yinter?></td>
					<td><?=$v1->gender?></td>
					<td><?=$v1->cost?></td>
					<td><?=$v1->participant_cost?></td>
					<td><?=$v1->participant_number?></td>
					<td><?=$v1->registered?></td>
					<td><?=$v1->reserved?></td>
					<td><?=$v1->capacity_number?></td>
					</tr>
							<?php } ?>


					<tr>
						<th><hr></th> <th><hr></th> <th><hr></th> <th><hr></th> <th><hr></th> <th><hr></th> <th><hr></th> <th><hr></th> <th><hr></th>
					</tr>
					<tr>
                        <th>انتخاب</th><th>وضعیت</th><th>سال ورودی</th><th>جنسیت</th><th>هزینه</th><th>هزینه همراه</th><th>تعداد همراه</th><th>ظرفیت</th>
                    </tr>
				  </tbody>

				</table>
		</div>
		<input class='btn btn-danger' type='button' name='' onclick="removeCapacity()" value='حذف' style="width: 100px" />
        <span class="" id="remove_capacity_message"></span>

	</div>
	</div>

	<br><br>
	<input type="hidden" name="csrf" value="<?=Token::generate();?>" />
	<input class='btn btn-primary' type='submit' value='به روز رسانی' class='submit' />
	<a class='btn btn-warning' href="admin_plans.php">انصراف</a><br><br>

	</form>

	</div><!--/col-9-->
</div><!--/row-->


</div>
</div>


<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

    <!-- Place any per-page javascript here -->

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>



<script src="js/jquery.min.js"></script>
<script src="js/search.js" charset="utf-8"></script>

<link rel="stylesheet" href="css/bootstrap-multiselect.css" type="text/css"/>
<link rel="stylesheet" href="css/bootstrap-datepicker.min.css" type="text/css"/>

<script type="text/javascript" src="js/bootstrap-multiselect.js"></script>

<script src="js/moment-with-locales.js"></script> 
<script src="js/bootstrap-datetimepicker.js"></script>

<script src="js/bootstrap-datepicker.min.js"></script>
<script src="js/bootstrap-datepicker.fa.min.js"></script>

<script src="js/admin_add_plan.js"></script>

<script type="text/javascript">



    console.log("***");
    //console.log(gregorian_to_jalali([2017, 10, 18]));
</script>