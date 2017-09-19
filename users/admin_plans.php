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
  //Manually Add User
  if(!empty($_POST['addUser'])) {
    $join_date = date("Y-m-d H:i:s");
    $username = Input::get('username');
    $fname = Input::get('fname');
    $lname = Input::get('lname');
    $email = Input::get('email');
    $token = $_POST['csrf'];

    if(!Token::check($token)){
      die('Token doesn\'t match!');
    }

    $form_valid=FALSE; // assume the worst
    $validation = new Validate();
    $validation->check($_POST,array(
      'username' => array(
      'display' => 'Username',
      'required' => true,
      'min' => $settings->min_un,
      'max' => $settings->max_un,
      'unique' => 'users',
      ),
      'fname' => array(
      'display' => 'First Name',
      'required' => true,
      'min' => 2,
      'max' => 35,
      ),
      'lname' => array(
      'display' => 'Last Name',
      'required' => true,
      'min' => 2,
      'max' => 35,
      ),
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
      ),
    ));
    if($validation->passed()) {
    $form_valid=TRUE;
      try {
        // echo "Trying to create user";
        $fields=array(
          'username' => Input::get('username'),
          'fname' => Input::get('fname'),
          'lname' => Input::get('lname'),
          'email' => Input::get('email'),
          'password' =>
          password_hash(Input::get('password'), PASSWORD_BCRYPT, array('cost' => 12)),
          'permissions' => 1,
          'account_owner' => 1,
          'stripe_cust_id' => '',
          'join_date' => $join_date,
          'company' => Input::get('company'),
          'email_verified' => 1,
          'active' => 1,
          'vericode' => 111111,
        );
        $db->insert('users',$fields);
        $theNewId=$db->lastId();
        // bold($theNewId);
        $perm = Input::get('perm');
        $addNewPermission = array('user_id' => $theNewId, 'permission_id' => $perm);
        $db->insert('user_permission_matches',$addNewPermission);
        $db->insert('profiles',['user_id'=>$theNewId, 'bio'=>'This is your bio']);

        if($perm != 1){
          $addNewPermission2 = array('user_id' => $theNewId, 'permission_id' => 1);
          $db->insert('user_permission_matches',$addNewPermission2);
        }

        $successes[] = lang("ACCOUNT_USER_ADDED");

      } catch (Exception $e) {
        die($e->getMessage());
      }

    }
  }
}

$userData = fetchAllUsers(); //Fetch information for all users


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

                    <form class="form-signup" action="admin_users.php" method="POST" id="payment-form">

                        

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
                            <input  class="form-control" type="text" name="username" id="username" placeholder="نام برنامه" value="<?php if (!$form_valid && !empty($_POST)){ echo $username;} ?>" required autofocus>
                        </div>
                        <div class="col-xs-6">
                            <input type="text" class="form-control" id="fname" name="fname" placeholder="هزینه" value="<?php if (!$form_valid && !empty($_POST)){ echo $fname;} ?>" required>
                        </div>
                        <div class="col-xs-12">
                            <textarea class="form-control" id="lname" name="lname" placeholder="توضیحات" rows="5"></textarea>
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
                                <input type='text' class="form-control" />
                            </div>
                        </div>

                        <div class="col-xs-2 ">                    
                            <div class='input-group date' id='datepicker' >
                                <span class="input-group-addon" >
                                    <span class="glyphicon glyphicon-calendar" ></span>
                                </span>
                                <input type='text' class="form-control" />
                            </div>
                        </div>

                        <div class="col-xs-2 ">                    
                            <div class='input-group date' id='datepicker' >
                                <span class="input-group-addon" >
                                    <span class="glyphicon glyphicon-calendar" ></span>
                                </span>
                                <input type='text' class="form-control" />
                            </div>
                        </div>

                        <div class="col-xs-2 ">                    
                            <div class='input-group date' id='datepicker' >
                                <span class="input-group-addon" >
                                    <span class="glyphicon glyphicon-calendar" ></span>
                                </span>
                                <input type='text' class="form-control" />
                            </div>
                        </div>

                        <div class="col-xs-2 ">                    
                            <div class='input-group date' id='datepicker' >
                                <span class="input-group-addon" >
                                    <span class="glyphicon glyphicon-calendar" ></span>
                                </span>
                                <input type='text' class="form-control" />
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
                                <input type='text' class="form-control" />
                            </div>
                        </div>

                        <div class="col-xs-2">                    
                            <div class='input-group date' id='timepicker' >
                                <span class="input-group-addon" >
                                    <span class="glyphicon glyphicon-time" ></span>
                                </span>
                                <input type='text' class="form-control" />
                            </div>
                        </div>

                        <div class="col-xs-2">                    
                            <div class='input-group date' id='timepicker' >
                                <span class="input-group-addon" >
                                    <span class="glyphicon glyphicon-time" ></span>
                                </span>
                                <input type='text' class="form-control" />
                            </div>
                        </div>

                        <div class="col-xs-2">                    
                            <div class='input-group date' id='timepicker' >
                                <span class="input-group-addon" >
                                    <span class="glyphicon glyphicon-time" ></span>
                                </span>
                                <input type='text' class="form-control" />
                            </div>
                        </div>

                        <div class="col-xs-2">                    
                            <div class='input-group date' id='timepicker' >
                                <span class="input-group-addon" >
                                    <span class="glyphicon glyphicon-time" ></span>
                                </span>
                                <input type='text' class="form-control" />
                            </div>
                        </div>                      

                    </div> <!-- End of row  -->                  

                </div><!-- End of form-group  -->
                </div>

<!--||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||-->
<!--||||||||||||||||||||||||||||    F O R M   O F   A D D I N G   C A P A C I T Y    |||||||||||||||||||||||||||||||||-->
<!--||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||-->
                <div class="well well-sm">
                <form class="" action="#" method="POST" id="">                
                    <div class="row-fluid">
                        <h2>ظرفیت</h2>
                    </div>

                    <div class="row capacity-row">
                
                        <div class="col-xs-12 col-md-8 col-lg-6 capacity">
                            <div class="input-group">
                                <select id="status" class="multiselect-ui form-control" multiple="multiple" onchange="changeStatusItems()">
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
                                <select class="multiselect-ui form-control" id="yinter" multiple="multiple" >
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
                                <select class="form-control">
                                    <option value="0">بدون اهمیت</option>
                                    <option value="1">آقا</option>
                                    <option value="2">خانم</option>
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
                                <input type="number" name="" class="form-control">
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
                                <input type="number" name="" class="form-control">
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
                                <input type="number" name="" class="form-control">
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
                                <input type="number" name="" class="form-control">
                                <span class="input-group-addon" >
                                    <span class="capacity" >هزینه همراه</span>
                                </span>
                            </div>
                        </div>
                    <!--
                    <div class="col-xs-3">
                        <div class="input-group">
                            <input type="text" class="form-control" aria-label="Text input with dropdown button"> 
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                وضعیت
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a class="dropdown-item" >فارغ التحصیل</a></li>
                                    <li><a class="dropdown-item" >دانشجو</a></li>
                                    <li><a class="dropdown-item" >کارمند</a></li>
                                    <li><a class="dropdown-item" >همراه</a></li>
                                    <li><a class="dropdown-item" >استاد</a></li>
                                    <li><a class="dropdown-item" >آزاد</a></li>
                                    <li class="divider"></li>
                                    <li><a class="dropdown-item" >بدون اهمیت</a></li>
                                </ul>
                            </div>
                            
                        </div>
                    </div>
                    -->
                    
                    </div> <!-- End of row  -->

                    <input type="hidden" value="<?=Token::generate();?>" name="csrf">
                    <input class='btn btn-success' type='submit' name='addUser' value='اضافه کردن ظرفیت' />
                </form>
                </div>

<!--||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||-->
<!--||||||||||||||||||    F O R M   O F   E D I T   A N D   R E M O V E   C A P A C I T Y    |||||||||||||||||||||||||-->
<!--||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||-->
                <div class="well well-sm">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="alluinfo">&nbsp;</div>
                        <form name="adminUsers" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                            <div class="allutable table-responsive" style="align-content: right;">
                                <table class='table table-hover table-list-search'>
                                    <thead>
                                        <tr>
                                            <th>انتخاب</th><th>وضعیت</th><th>سال ورودی</th><th>جنسیت</th><th>هزینه</th><th>هزینه همراه</th><th>تعداد همراه</th><th>ظرفیت</th>
                                            </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        //Cycle through users
                                        foreach ($userData as $v1) {
                                    ?>
                                        <tr>
                                         <td><div class="form-group"><input type="checkbox" name="delete[<?=$v1->id?>]" value="<?=$v1->id?>" /></div></td>
                                        <td><a href='admin_user.php?id=<?=$v1->id?>'><?=$v1->username?></a></td>
                                        <td><?=$v1->email?></td>
                                        <td><?=$v1->fname?></td>
                                        <td><?=$v1->lname?></td>
                                        <td><?=$v1->join_date?></td>
                                        <td><?=$v1->last_login?></td>
                                        <td><?=$v1->logins?></td>
                                        </tr>
                                    <?php } ?>

                                    </tbody>
                                </table>
                            </div>

                            <input class='btn btn-success' type='submit' name='Submit' value='ویرایش' style="width: 200px;" />
                            <input class='btn btn-danger' type='submit' name='Submit' value='حذف'  style="width: 100px" />
                            <br><br>

                        </form>

                    </div>
                </div> <!-- End of row -->
                </div>

<!--||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||-->
<!--||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||-->
<!--||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||-->                

                <div class="well well-sm">
                <br /><br /><br />
                <input class='btn btn-success center-block' type='submit' name='Submit' value='اضافه کردن برنامه'  style="width: 60%;" />
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
