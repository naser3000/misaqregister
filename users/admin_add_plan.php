<?php require_once 'init.php'; ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/header.php'; ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; ?>

<?php if (!securePage($_SERVER['PHP_SELF'])){die();} ?>
<?php
//PHP Goes Here!
$errors = $successes = [];
$form_valid=TRUE;
$permOpsQ = $db->query("SELECT * FROM permissions");
$permOps = $permOpsQ->results();
// dnd($permOps);

//Forms posted
if (!empty($_POST)) {
  //Delete User Checkboxes
  if (!empty($_POST['delete'])){
    $deletions = $_POST['delete'];
    if ($deletion_count = deleteUsers($deletions)){
      $successes[] = lang("ACCOUNT_DELETIONS_SUCCESSFUL", array($deletion_count));
    }
    else {
      $errors[] = lang("SQL_ERROR");
    }
  }
  //Manually Add Plan
  if(!empty($_POST['addPlan'])) {

  //  $join_date = date("Y-m-d H:i:s");
    $title = Input::get('title');
    $description = Input::get('description');
    $register_start_date = Input::get('register_start_date');
    $register_end_date = Input::get('register_end_date');
    $confirm_end_date = Input::get('confirm_end_date');
    $plan_start_date = Input::get('plan_start_date');
    $plan_end_date = Input::get('plan_end_date');
    $register_start_time = Input::get('register_start_time');
    $register_end_time = Input::get('register_end_time');
    $confirm_end_time = Input::get('confirm_end_time');
    $plan_start_time = Input::get('plan_start_time');
    $plan_end_time = Input::get('plan_end_time');

    $capacity_row = Input::get('send_to_db0');
    /*$status = Input::get('status');
    $yinter = Input::get('yinter');
    $gender = Input::get('gender');
    $cost = Input::get('cost');
    $capacity_number = Input::get('capacity_number');
    $participant_number = Input::get('participant_number');
    $participant_cost = Input::get('participant_cost');
    $plan_id = Input::get('plan_id');*/
    $token = $_POST['csrf'];

    if(!Token::check($token)){
      die('Token doesn\'t match!');
    }

    $form_valid=FALSE; // assume the worst
    $validation = new Validate();
    $validation->check($_POST,array(
      'title' => array(
      'display' => 'عنوان',
      'required' => true,
      'max' => 100,
      ),
      'description' => array(
      'display' => 'توضیحات',
      'required' => true,
      'min' => 20,
      'max' => 500,
      ),
      'register_start_date' => array(
      'display' => 'تاریخ شروع ثبت نام',
      'after_equal' => gregorian_to_jalali(explode('/', date("Y/m/d"))),
      'before_equal' => $register_end_date,
      'required' => true,
      ),
      'register_end_date' => array(
      'display' => 'تاریخ پایان ثبت نام',
      'after_equal' => $register_start_date,
      'before_equal' => $confirm_end_date,
      'required' => true,
      ),
      'confirm_end_date' => array(
      'display' => 'تاریخ مهلت لغو ثبت نام',
      'after_equal' => $register_end_date,
      'before_equal' => $plan_start_date,
      'required' => true,
      ),
      'plan_start_date' => array(
      'display' => 'تاریخ شروع برنامه',
      'after_equal' => $confirm_end_date,
      'before_equal' => $plan_end_date,
      'required' => true,
      ),
      'plan_end_date' => array(
      'display' => 'تاریخ پایان برنامه',
      'after_equal' => $plan_start_date,
      'required' => true,
      ),
      'register_start_time' => array(
      'display' => 'زمان شروع ثبت نام',
      'required' => true,
      ),
      
      'register_end_time' => array(
      'display' => 'زمان پایان ثبت نام',
      'required' => true,
      ),
      'confirm_end_time' => array(
      'display' => 'زمان مهلت لغو برنامه',
      'required' => true,
      ),
      'plan_start_time' => array(
      'display' => 'زمان شروع برنامه',
      'required' => true,
      ),
      'plan_end_time' => array(
      'display' => 'زمان پایان برنامه',
      'required' => true,
      ),
      'send_to_db0' => array(
      'display' => 'ظرفیت(ها)',
      'required' => true,
      ),
    ));
    if($validation->passed()) {
    $form_valid=TRUE;
      try {
        // echo "Trying to create user";
        $plan_fields=array(
          'title' => Input::get('title'),
          'description' => Input::get('description'),
          'register_start_date' => Input::get('register_start_date'),
          'register_end_date' => Input::get('register_end_date'),
          'confirm_end_date' => Input::get('confirm_end_date'),
          'plan_start_date' => Input::get('plan_start_date'),
          'plan_end_date' => Input::get('plan_end_date'),
          'register_start_time' => Input::get('register_start_time'),
          'register_end_time' => Input::get('register_end_time'),
          'confirm_end_time' => Input::get('confirm_end_time'),
          'plan_start_time' => Input::get('plan_start_time'),
          'plan_end_time' => Input::get('plan_end_time'),
        );

        $db->insert('plans',$plan_fields);
        $theNewPlanId=$db->lastId();
        // bold($theNewId);


        $capacity_row = Input::get('send_to_db0');
        $i = 0;
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
          'plan_id' => $theNewPlanId,
        );

          $db->insert('capacity',$capacity_fields);
          $i++;
          $next_data = "send_to_db".(string)$i;
          $capacity_row = Input::get($next_data);
        }
        
        

        $successes[] = lang("PLAN_ADDED");

      } catch (Exception $e) {
        die($e->getMessage());
      }

    }
  }
}


?>

<div id="page-wrapper">

    <div class="container">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <h1>مدیریت برنامه ها</h1>
            </div>
            <div class="col-xs-12 col-md-6">
                <form class="">
                    <label for="system-search">جستجو:</label>
                    <div class="input-group">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-default"><i class="fa fa-times"></i></button>
                        </span>
                        <input class="form-control" id="system-search" name="q" placeholder="جستجوی کاربران..." type="text">
                    </div>
                </form>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
          <?php echo resultBlock($errors,$successes);
        ?>

                <hr />
                <div class="row">
                <div class="col-xs-12">
                    <?php
                        if (!$form_valid && Input::exists()){
                            echo display_errors($validation->errors());
                        }
                    ?>

              


              <form class="form-signup" action="admin_add_plan.php" method="POST" id="payment-form">
<!--||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||-->
<!--||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||-->
<!--||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||-->
                <div class="well well-sm">
                <div class="form-group" >

                    <div class="row-fluid">
                        <h2>مشخصات</h2>
                    </div>
                    <div class="row spec-row">
                        <div class="col-xs-6" >
                            <input  class="form-control" type="text" name="title" id="title" placeholder="نام برنامه" value="<?php if (!$form_valid && !empty($_POST)){ echo $title;} ?>" required autofocus>
                        </div>
                        
                        <div class="col-xs-12">
                            <textarea class="form-control" id="description" name="description" placeholder="توضیحات" rows="5"><?php if (!$form_valid && !empty($_POST)){ echo $description;} ?></textarea>
                        </div>
                    </div>
                </div>
                </div>

<!--||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||-->
<!--||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||-->
<!--||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||-->                
                <div class="well well-sm ">
                <div class="form-group datetime-group" style="width: 1100px;">
                <div class="alluinfo">&nbsp;</div>
                    <div class="row-fluid">
                        <h2>زمان</h2>
                    </div>

                    <div class="row " style="text-align: center; ">
                        <div class="col-xs-2">
                            <p></p>
                        </div>
                        <div class="col-xs-2">
                            <p>شروع ثبت نام</p>
                        </div>  
                        <div class="col-xs-2 ">
                            <p>پایان ثبت نام</p>
                        </div>  
                        <div class="col-xs-2 ">
                            <p>مهلت لغو ثبت نام</p>
                        </div>  
                        <div class="col-xs-2 ">
                            <p>شروع برنامه</p>
                        </div>  
                        <div class="col-xs-2 ">
                            <p>پایان برنامه</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-2" style="text-align: center;">
                            <p>تاریخ</p>
                        </div>

                        <div class="col-xs-2">
                            <div class='input-group date' id='datepicker' >
                                <span class="input-group-addon" id="register_start_date_btn">
                                    <span class="glyphicon glyphicon-calendar" ></span>
                                </span>
                                <input type='text' class="form-control" name="register_start_date" id="register_start_date" value="<?php if (!$form_valid && !empty($_POST)){ echo $register_start_date;} ?>" required autofocus readonly/>
                            </div>
                        </div>

                        <div class="col-xs-2 ">                    
                            <div class='input-group date' id='datepicker' >
                                <span class="input-group-addon" id="register_end_date_btn">
                                    <span class="glyphicon glyphicon-calendar" ></span>
                                </span>
                                <input type='text' class="form-control" name="register_end_date" id="register_end_date" value="<?php if (!$form_valid && !empty($_POST)){ echo $register_end_date;} ?>" required autofocus readonly/>
                            </div>
                        </div>

                        <div class="col-xs-2 ">                    
                            <div class='input-group date' id='datepicker' >
                                <span class="input-group-addon" id="confirm_end_date_btn">
                                    <span class="glyphicon glyphicon-calendar" ></span>
                                </span>
                                <input type='text' class="form-control" name="confirm_end_date" id="confirm_end_date" value="<?php if (!$form_valid && !empty($_POST)){ echo $confirm_end_date;} ?>" required autofocus readonly/>
                            </div>
                        </div>

                        <div class="col-xs-2 ">                    
                            <div class='input-group date' id='datepicker' >
                                <span class="input-group-addon" id="plan_start_date_btn">
                                    <span class="glyphicon glyphicon-calendar" ></span>
                                </span>
                                <input type='text' class="form-control" name="plan_start_date" id="plan_start_date" value="<?php if (!$form_valid && !empty($_POST)){ echo $plan_start_date;} ?>" required autofocus readonly/>
                            </div>
                        </div>

                        <div class="col-xs-2 ">                    
                            <div class='input-group date' id='datepicker' >
                                <span class="input-group-addon" id="plan_end_date_btn">
                                    <span class="glyphicon glyphicon-calendar" ></span>
                                </span>
                                <input type='text' class="form-control" name="plan_end_date" id="plan_end_date" value="<?php if (!$form_valid && !empty($_POST)){ echo $plan_end_date;} ?>" required autofocus readonly/>
                            </div>
                        </div>

                    </div> <!-- End of row  -->



                    <div class="row" style="text-align: center;">
                        <div class="col-xs-2">
                            <p>ساعت</p>
                        </div>

                        <div class="col-xs-2">                    
                            <div class='input-group date' id='timepicker' >
                                <span class="input-group-addon" id="register_start_time_btn">
                                    <span class="glyphicon glyphicon-time" ></span>
                                </span>     
                                <input type='text' class="form-control" name="register_start_time" id="register_start_time" value="<?php if (!$form_valid && !empty($_POST)){ echo $register_start_time;} ?>" required autofocus />
                            </div>
                        </div>

                        <div class="col-xs-2">                    
                            <div class='input-group date' id='timepicker' >
                                <span class="input-group-addon" id="register_end_time_btn">
                                    <span class="glyphicon glyphicon-time" ></span>
                                </span>
                                <input type='text' class="form-control" name="register_end_time" id="register_end_time" value="<?php if (!$form_valid && !empty($_POST)){ echo $register_end_time;} ?>" required autofocus />
                            </div>
                        </div>

                        <div class="col-xs-2">                    
                            <div class='input-group date' id='timepicker' >
                                <span class="input-group-addon" id="confirm_end_time_btn">
                                    <span class="glyphicon glyphicon-time" ></span>
                                </span>
                                <input type='text' class="form-control" name="confirm_end_time" id="confirm_end_time" value="<?php if (!$form_valid && !empty($_POST)){ echo $confirm_end_time;} ?>" required autofocus />
                            </div>
                        </div>

                        <div class="col-xs-2">                    
                            <div class='input-group date' id='timepicker' >
                                <span class="input-group-addon" id="plan_start_time_btn">
                                    <span class="glyphicon glyphicon-time" ></span>
                                </span>
                                <input type='text' class="form-control" name="plan_start_time" id="plan_start_time" value="<?php if (!$form_valid && !empty($_POST)){ echo $plan_start_time;} ?>" required autofocus />
                            </div>
                        </div>

                        <div class="col-xs-2">                    
                            <div class='input-group date' id='timepicker' >
                                <span class="input-group-addon" id="plan_end_time_btn">
                                    <span class="glyphicon glyphicon-time" ></span>
                                </span>
                                <input type='text' class="form-control" name="plan_end_time" id="plan_end_time" value="<?php if (!$form_valid && !empty($_POST)){ echo $plan_end_time;} ?>" required autofocus />
                            </div>
                        </div>                      

                    </div> <!-- End of row  -->                  

                </div><!-- End of form-group  -->
                </div>

<!--||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||-->
<!--||||||||||||||||||||||||||||    F O R M   O F   A D D I N G   C A P A C I T Y    |||||||||||||||||||||||||||||||||-->
<!--||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||-->
                <div class="well well-sm">                
                    <div class="row-fluid">
                        <h2>ظرفیت</h2>
                    </div>

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
                                    <option  value="85">85</option><option  value="کارشناسی (85)">کارشناسی (85)</option><option  value="کارشناسی ارشد (85)">کارشناسی ارشد (85)</option><option  value="دکترا (85)">دکترا (85)</option>
                                    <option  value="86">86</option><option  value="کارشناسی (86)">کارشناسی (86)</option><option  value="کارشناسی ارشد (86)">کارشناسی ارشد (86)</option><option  value="دکترا (86)">دکترا (86)</option>
                                    <option  value="87">87</option><option  value="کارشناسی (87)">کارشناسی (87)</option><option  value="کارشناسی ارشد (87)">کارشناسی ارشد (87)</option><option  value="دکترا (87)">دکترا (87)</option>
                                    <option  value="88">88</option><option  value="کارشناسی (88)">کارشناسی (88)</option><option  value="کارشناسی ارشد (88)">کارشناسی ارشد (88)</option><option  value="دکترا (88)">دکترا (88)</option>
                                    <option  value="89">89</option><option  value="کارشناسی (89)">کارشناسی (89)</option><option  value="کارشناسی ارشد (89)">کارشناسی ارشد (89)</option><option  value="دکترا (89)">دکترا (89)</option>
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

<!--||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||-->
<!--||||||||||||||||||    F O R M   O F   E D I T   A N D   R E M O V E   C A P A C I T Y    |||||||||||||||||||||||||-->
<!--||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||-->
                <div class="well well-sm">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="alluinfo">&nbsp;</div>
                            <div class="allutable table-responsive" style="align-content: right;">
                                <table class='table table-hover table-list-search'>
                                    <thead>
                                        <tr>
                                            <th>انتخاب</th><th>وضعیت</th><th>سال ورودی</th><th>جنسیت</th><th>هزینه</th><th>هزینه همراه</th><th>تعداد همراه</th><th>ظرفیت</th>
                                            </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                        if (!$form_valid && !empty($_POST)){

                                          $i = 0;
                                          $next_data = "send_to_db".$i;
                                          $capacity_row = Input::get($next_data);
                                          while (strlen($capacity_row) > 0) {
                                            $pieces_of_row = explode("|", $capacity_row);
                                            echo ' <tr><td><input type="checkbox" id="delete"></td><td>'.$pieces_of_row[0].'</td><td>'.$pieces_of_row[1].'</td><td>'.$pieces_of_row[2].'</td><td>'.$pieces_of_row[3].'</td><td>'.$pieces_of_row[6].'</td><td>'.$pieces_of_row[5].'</td><td>'.$pieces_of_row[4].'</td><td><input name="'.$next_data.'" id="'.$next_data.'" type="radio" checked="checked" value="'.$capacity_row.'"></td></tr>';
                                            $i++;
                                            $next_data = "send_to_db".$i;
                                            $capacity_row = Input::get($next_data);
                                          }
                                        }
                                      ?>
                                  
                                    </tbody>
                                </table>
                            </div>

                            
                            <input class='btn btn-danger' type='button' name='' onclick="removeCapacity()" value='حذف'  style="width: 100px" />
                            <span class="" id="remove_capacity_message"></span>
                            <br><br>
                    </div>
                </div> <!-- End of row -->
                </div>

<!--||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||-->
<!--||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||-->
<!--||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||-->                

                <div class="well well-sm">
                <br /><br /><br />
                <input type="hidden" value="<?=Token::generate();?>" name="csrf">
                <input class='btn btn-success center-block' type='submit' name='addPlan' value='اضافه کردن برنامه'  style="width: 60%;" />
                </div>
            </form>

            </div>
            </div><!--End of row --> 

    </div>
    </div><!--End of row --> 

</div>
</div> <!--End of page-wrapper --> 



  <!-- End of main content section -->

<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

    <!-- Place any per-page javascript here -->


<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>



<link rel="stylesheet" href="css/bootstrap-multiselect.css" type="text/css"/>
<link rel="stylesheet" href="css/bootstrap-datepicker.min.css"/>

<script type="text/javascript" src="js/bootstrap-multiselect.js"></script>
<script src="js/bootstrap-datepicker.fa.min.js"></script>
<script src="js/bootstrap-datepicker.min.js"></script>
<script src="js/bootstrap-datetimepicker.js"></script>
<script src="js/search.js" charset="utf-8"></script>
<script src="js/moment-with-locales.js"></script>
<script src="js/admin_add_plan.js"></script>
<script src="js/jquery.min.js"></script>