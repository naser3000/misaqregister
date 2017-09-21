<?php
/*
UserSpice 4
An Open Source PHP User Management System
by the UserSpice Team at http://UserSpice.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
?>
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

    
    echo "\n***************************";
    echo "\n***************************";
    echo "\n***************************";
    echo "\n***************************";
    echo "\n***************************";
    print_r($_POST);
    print_r($_POST);
    print_r($_POST);
    print_r($_POST);
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

    for ($i = 0; $i <= 10; $i++) {
        $status = Input::get('status');
        $yinter = Input::get('yinter');
        $gender = Input::get('gender');
        $cost = Input::get('cost');
        $capacity_number = Input::get('capacity_number');
        $participant_number = Input::get('participant_number');
        $participant_cost = Input::get('participant_cost');
        $plan_id = Input::get('plan_id');
    }
    
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
      'required' => true,
      ),
      'register_end_date' => array(
      'display' => 'تاریخ پایان ثبت نام',
      'required' => true,
      ),
      'confirm_end_date' => array(
      'display' => 'تاریخ مهلت لغو ثبت نام',
      'required' => true,
      ),
      'plan_start_date' => array(
      'display' => 'تاریخ شروع برنامه',
      'required' => true,
      ),
      'plan_end_date' => array(
      'display' => 'تاریخ پایان برنامه',
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
      /*
      'email' => array(
      'display' => 'Email',
      'required' => true,
      'valid_email' => true,
      'unique' => 'users',
      ),
      'password' => array(
      'display' => 'Password',
      'required' => true,
      'min' => $settings->min_pw,
      'max' => $settings->max_pw,
      ),
      'confirm' => array(
      'display' => 'Confirm Password',
      'required' => true,
      'matches' => 'password',
      ),*/
    ));
    if($validation->passed()) {
    $form_valid=TRUE;
      try {
        // echo "Trying to create user";
        $fields=array(


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
        $db->insert('plans',$fields);
        $theNewId=$db->lastId();
        // bold($theNewId);


        $successes[] = lang("ACCOUNT_USER_ADDED");

      } catch (Exception $e) {
        die($e->getMessage());
      }

    }
  }
}

$capacityData = fetchAllCapacity(); //Fetch information for all users


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

              


              <form class="form-signup" action="admin_plans.php" method="POST" id="payment-form">
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
                            <textarea class="form-control" id="description" name="description" placeholder="توضیحات" rows="5"></textarea>
                        </div>
                    </div>
                </div>
                </div>

<!--||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||-->
<!--||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||-->
<!--||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||-->                
                <div class="well well-sm scrollmenu">
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
                                <span class="input-group-addon" >
                                    <span class="glyphicon glyphicon-calendar" ></span>
                                </span>
                                <input type='text' class="form-control" name="register_start_date" id="register_start_date" placeholder="تارخ شروع برنامه" value="<?php if (!$form_valid && !empty($_POST)){ echo $register_start_date;} ?>" required autofocus/>
                            </div>
                        </div>

                        <div class="col-xs-2 ">                    
                            <div class='input-group date' id='datepicker' >
                                <span class="input-group-addon" >
                                    <span class="glyphicon glyphicon-calendar" ></span>
                                </span>
                                <input type='text' class="form-control" name="register_end_date" id="register_end_date" placeholder="تارخ شروع برنامه" value="<?php if (!$form_valid && !empty($_POST)){ echo $register_end_date;} ?>" required autofocus/>
                            </div>
                        </div>

                        <div class="col-xs-2 ">                    
                            <div class='input-group date' id='datepicker' >
                                <span class="input-group-addon" >
                                    <span class="glyphicon glyphicon-calendar" ></span>
                                </span>
                                <input type='text' class="form-control" name="confirm_end_date" id="confirm_end_date" placeholder="تارخ شروع برنامه" value="<?php if (!$form_valid && !empty($_POST)){ echo $confirm_end_date;} ?>" required autofocus/>
                            </div>
                        </div>

                        <div class="col-xs-2 ">                    
                            <div class='input-group date' id='datepicker' >
                                <span class="input-group-addon" >
                                    <span class="glyphicon glyphicon-calendar" ></span>
                                </span>
                                <input type='text' class="form-control" name="plan_start_date" id="plan_start_date" placeholder="تارخ شروع برنامه" value="<?php if (!$form_valid && !empty($_POST)){ echo $plan_start_date;} ?>" required autofocus/>
                            </div>
                        </div>

                        <div class="col-xs-2 ">                    
                            <div class='input-group date' id='datepicker' >
                                <span class="input-group-addon" >
                                    <span class="glyphicon glyphicon-calendar" ></span>
                                </span>
                                <input type='text' class="form-control" name="plan_end_date" id="plan_end_date" placeholder="تارخ شروع برنامه" value="<?php if (!$form_valid && !empty($_POST)){ echo $plan_end_date;} ?>" required autofocus/>
                            </div>
                        </div>

                    </div> <!-- End of row  -->



                    <div class="row" style="text-align: center;">
                        <div class="col-xs-2">
                            <p>ساعت</p>
                        </div>

                        <div class="col-xs-2">                    
                            <div class='input-group date' id='timepicker' >
                                <span class="input-group-addon" >
                                    <span class="glyphicon glyphicon-time" ></span>
                                </span>     
                                <input type='text' class="form-control" name="register_start_time" id="register_start_time" placeholder="تارخ شروع برنامه" value="<?php if (!$form_valid && !empty($_POST)){ echo $register_start_time;} ?>" required autofocus/>
                            </div>
                        </div>

                        <div class="col-xs-2">                    
                            <div class='input-group date' id='timepicker' >
                                <span class="input-group-addon" >
                                    <span class="glyphicon glyphicon-time" ></span>
                                </span>
                                <input type='text' class="form-control" name="register_end_time" id="register_end_time" placeholder="تارخ شروع برنامه" value="<?php if (!$form_valid && !empty($_POST)){ echo $register_end_time;} ?>" required autofocus/>
                            </div>
                        </div>

                        <div class="col-xs-2">                    
                            <div class='input-group date' id='timepicker' >
                                <span class="input-group-addon" >
                                    <span class="glyphicon glyphicon-time" ></span>
                                </span>
                                <input type='text' class="form-control" name="confirm_end_time" id="confirm_end_time" placeholder="تارخ شروع برنامه" value="<?php if (!$form_valid && !empty($_POST)){ echo $confirm_end_time;} ?>" required autofocus/>
                            </div>
                        </div>

                        <div class="col-xs-2">                    
                            <div class='input-group date' id='timepicker' >
                                <span class="input-group-addon" >
                                    <span class="glyphicon glyphicon-time" ></span>
                                </span>
                                <input type='text' class="form-control" name="plan_start_time" id="plan_start_time" placeholder="تارخ شروع برنامه" value="<?php if (!$form_valid && !empty($_POST)){ echo $plan_start_time;} ?>" required autofocus/>
                            </div>
                        </div>

                        <div class="col-xs-2">                    
                            <div class='input-group date' id='timepicker' >
                                <span class="input-group-addon" >
                                    <span class="glyphicon glyphicon-time" ></span>
                                </span>
                                <input type='text' class="form-control" name="plan_end_time" id="plan_end_time" placeholder="تارخ شروع برنامه" value="<?php if (!$form_valid && !empty($_POST)){ echo $plan_end_time;} ?>" required autofocus/>
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
                                    <option  value="85">85</option>
                                    <option  value="86">86</option>
                                    <option  value="87">87</option>
                                    <option  value="88">88</option>
                                    <option  value="89">89</option>
                                    <option  value="90">90</option>
                                    <option  value="91">91</option>
                                    <option  value="92">92</option>
                                    <option  value="93">93</option>
                                    <option  value="94">94</option>
                                    <option  value="95">95</option>
                                    <option  value="96">96</option>
                                    <option  value="97">97</option>
                                    <option  value="98">98</option>
                                    <option  value="99">99</option>
                                </select>
                                <span class="input-group-addon" >
                                    <span class="capacity" >سال ورود</span>
                                </span>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 capacity">
                            <div class="input-group">
                                <select id="gender" class="form-control">
                                    <option value="بدون اهمیت">بدون اهمیت</option>
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
<script src="js/search.js" charset="utf-8"></script>

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
