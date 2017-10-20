<?php require_once 'init.php'; ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/header.php'; ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; ?>

<?php if (!securePage($_SERVER['PHP_SELF'])){die();} ?>
<?php
$validation = new Validate();
//PHP Goes Here!
$errors = [];
$successes = [];
$userId = Input::get('id');
//Check if selected user exists
if(!userIdExists($userId)){
  Redirect::to("admin_users.php"); die();
}

$userdetails = fetchUserDetails(NULL, NULL, $userId); //Fetch user details

//Forms posted
if(!empty($_POST)) {
    $token = $_POST['csrf'];
    if(!Token::check($token)){
      die('Token doesn\'t match!');
    }else {

    //Update display name
    if ($userdetails->username != $_POST['username']){
      $displayname = Input::get("username");

      $fields=array('username'=>$displayname);
      $validation->check($_POST,array(
        'username' => array(
          'display' => 'نام کاربری',
          'required' => true,
          'unique_update' => 'users,'.$userId,
          'min' => 1,
          'max' => 25
        )
      ));
    if($validation->passed()){
      $db->update('users',$userId,$fields);
     $successes[] = "نام کاربری به روز رسانی شد.";
    }else{
          ?><div id="form-errors"><?=$validation->display_errors();?></div>
            <?php
      }
    }

    //Update first name
    if ($userdetails->fname != $_POST['fname']){
       $fname = Input::get("fname");

      $fields=array('fname'=>$fname);
      $validation->check($_POST,array(
        'fname' => array(
          'display' => 'نام',
          'required' => true,
          'min' => 1,
          'max' => 25
        )
      ));
    if($validation->passed()){
      $db->update('users',$userId,$fields);
      $successes[] = "نام به روز رسانی شد.";
    }else{
          
      }
    }

    //Update last name
    if ($userdetails->lname != $_POST['lname']){
      $lname = Input::get("lname");

      $fields=array('lname'=>$lname);
      $validation->check($_POST,array(
        'lname' => array(
          'display' => 'نام خانوادگی',
          'required' => true,
          'min' => 1,
          'max' => 25
        )
      ));
    if($validation->passed()){
      $db->update('users',$userId,$fields);
      $successes[] = "نام خانوادگی به روز رسانی شد.";
    }else{
          ?>
            <?php
      }
    }

    //Update account charge
    if ($userdetails->account_charge != $_POST['account_charge']){
      $account_charge = Input::get("account_charge");

      $fields=array('account_charge'=>$account_charge);
      $validation->check($_POST,array(
        'account_charge' => array(
          'display' => 'موجودی حساب کاربری',
        )
      ));
    if($validation->passed()){
      $db->update('users',$userId,$fields);
      $successes[] = "موجودی حساب کاربری به روز رسانی شد.";
    }else{
          ?>
            <?php
      }
    }

    //Update national code
    if ($userdetails->icode != $_POST['icode']){
      $icode = Input::get("icode");

      $fields=array('icode'=>$icode);
      $validation->check($_POST,array(
        'icode' => array(
          'display' => 'کد ملی',
          'required' => true,
          'exact' => 10,
        )
      ));
    if($validation->passed()){
      $db->update('users',$userId,$fields);
      $successes[] = "کد ملی به روز رسانی شد.";
    }else{
          ?>
            <?php
      }
    }
    
    //Update phone number
    if ($userdetails->phnumber != $_POST['phnumber']){
      $phnumber = Input::get("phnumber");

      $fields=array('phnumber'=>$phnumber);
      $validation->check($_POST,array(
        'phnumber' => array(
          'display' => 'شماره تماس',
          'required' => true,
          'exact' => 11,
        )
      ));
    if($validation->passed()){
      $db->update('users',$userId,$fields);
      $successes[] = "شماره تماس به روز رسانی شد.";
    }else{
          ?>
            <?php
      }
    }
    
    //Update major
    if ($userdetails->major != $_POST['major']){
      $major = Input::get("major");

      $fields=array('major'=>$major);
      $validation->check($_POST,array(
        'major' => array(
          'display' => 'رشته تحصیلی',
        )
      ));
    if($validation->passed()){
      $db->update('users',$userId,$fields);
      $successes[] = "رشته تحصیلی به روز رسانی شد.";
    }else{
          ?>
            <?php
      }
    }

    //Update status
        if ($userdetails->status != $_POST['status']){
            $status = Input::get("status");
            if ($status == "دانشجو"){
                    $std_number = Input::get("std_number");
                    $dorms = Input::get("dorms");
                    $grade = "";
                    if ( ($std_number/100000)%10 == 1 )
                        $grade = "کارشناسی";
                    if ( ($std_number/100000)%10 == 2 )
                        $grade = "کارشناسی ارشد";
                    if ( ($std_number/100000)%10 == 3 )
                        $grade = "دکترا";
                    $fields=array('std_number'=>$std_number, 'yinter'=>$std_number/1000000, 'grade'=>$grade, 'dorms'=>$dorms);
                    $validation->check($_POST,array(
                        'std_number' => array(
                            'display' => 'شماره دانشجویی',
                            'required' => true,
                            'exact' => 8,
                        )
                    ));
                    if($validation->passed()){
                        $db->update('users',$userId,array('status'=>$status));
                        $db->update('users',$userId,$fields);
                        $db->update('users',$userId,array('emp_number'=>null));
                        $successes[]='وضعیت به روز رسانی شد.';
                    }else{
                        ?>
                            <?php
                    }
            }
            elseif ($status == "کارمند") {
                    $emp_number = Input::get("emp_number");
                    $fields=array('emp_number'=>$emp_number);
                    $validation->check($_POST,array(
                        'emp_number' => array(
                            'display' => 'کد پرسنلی',
                            'required' => true,
                        )
                    ));
                    if($validation->passed()){
                        $db->update('users',$userId,array('status'=>$status));
                        $db->update('users',$userId,array('std_number'=>null, 'yinter'=>0, 'grade'=>null, 'dorms'=>null));
                        $db->update('users',$userId,$fields);
                        $successes[]='وضعیت به روز رسانی شد.';
                    }else{
                        ?>
                            <?php
                    }
            }
            else{
                $fields = array('status'=>$status);
                $db->update('users',$userId,$fields);
                $db->update('users',$userId,array('std_number'=>null, 'yinter'=>0, 'grade'=>null, 'dorms'=>null));
                $db->update('users',$userId,array('emp_number'=>null));
                $successes[]='وضعیت به روز رسانی شد.';

            }
        }else{
                $status = $userdetails->status;
        }

    //Update std number and dorms
    if ($status == "دانشجو" ){
        // std_number
        if ($userdetails->std_number != $_POST['std_number']){
            $std_number = Input::get("std_number");
            $grade = "";
            if ( ($std_number/100000)%10 == 1 )
                $grade = "کارشناسی";
            if ( ($std_number/100000)%10 == 2 )
                $grade = "کارشناسی ارشد";
            if ( ($std_number/100000)%10 == 3 )
                $grade = "دکترا";
            $fields=array('std_number'=>$std_number, 'yinter'=>$std_number/1000000, 'grade'=>$grade);
            $validation->check($_POST,array(
                'std_number' => array(
                    'display' => 'شماره دانشجویی',
                    'required' => true,
                    'exact' => 8,
                )
            ));
            if($validation->passed()){
                $db->update('users',$userId,$fields);
                $successes[]='شماره دانشجویی به روز رسانی شد.';
            }else{
                ?>
                    <?php
            }
        }
        // dorms
        if ($userdetails->dorms != $_POST['dorms']){
            $dorms = Input::get("dorms");
            $fields=array('dorms'=>$dorms);
            $validation->check($_POST,array(
                'dorms' => array(
                    'display' => 'خوابگاه',
                    'required' => false,
                )
            ));
            if($validation->passed()){
                $db->update('users',$userId,$fields);
                $successes[]='خوابگاه به روز رسانی شد.';
            }else{
                ?>
                    <?php
            }
        }
    }

    //Update emp number
    if ($status == "کارمند") {
        if ($userdetails->emp_number != $_POST['emp_number']){
            $emp_number = Input::get("emp_number");
            $fields=array('emp_number'=>$emp_number);
            $validation->check($_POST,array(
                'emp_number' => array(
                    'display' => 'کد پرسنلی',
                    'required' => true,
                )
            ));
            if($validation->passed()){
                $db->update('users',$userId,$fields);
                $successes[]='کد پرسنلی به روز رسانی شد.';
            }else{
                ?>
                    <?php
            }
        }
    }

    //Update password
    if(!empty($_POST['password'])) {
      $validation->check($_POST,array(
        'password' => array(
          'display' => 'رمز جدید',
          'required' => true,
          'min' => $settings->min_pw,
					'max' => $settings->max_pw,
        ),
        'confirm' => array(
          'display' => 'تکرار رمز جدید',
          'required' => true,
          'matches' => 'password',
        ),
      ));

    if (empty($errors)) {
      //process
      $new_password_hash = password_hash(Input::get('password'),PASSWORD_BCRYPT,array('cost' => 12));
      $user->update(array('password' => $new_password_hash,),$userId);
      $successes[]='رمز عبور به روز رسانی شد.';
    }
    }


    //Block User
    if ($userdetails->permissions != $_POST['active']){
      $active = Input::get("active");
      $fields=array('permissions'=>$active);
      $db->update('users',$userId,$fields);
    }

    //Update email
    if ($userdetails->email != $_POST['email']){
      $email = Input::get("email");
      $fields=array('email'=>$email);
      $validation->check($_POST,array(
        'email' => array(
          'display' => 'ایمیل',
          'required' => true,
          'valid_email' => true,
          'unique_update' => 'users,'.$userId,
          'min' => 3,
          'max' => 75
        )
      ));
    if($validation->passed()){
      $db->update('users',$userId,$fields);
      $successes[] = "ایمیل به روز رسانی شد.";
    }else{
          ?><div id="form-errors">
            <?=$validation->display_errors();?></div>
            <?php
      }

    }

    //Remove permission level
    if(!empty($_POST['removePermission'])){
      $remove = $_POST['removePermission'];
      if ($deletion_count = removePermission($remove, $userId)){
        $successes[] = lang("ACCOUNT_PERMISSION_REMOVED", array ($deletion_count));
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }
    if(!empty($_POST['addPermission'])){
      $add = $_POST['addPermission'];
      if ($addition_count = addPermission($add, $userId,'user')){
        $successes[] = lang("ACCOUNT_PERMISSION_ADDED", array ($addition_count));
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }

    }// END OF ELSE ---> (!Token::check($token))
      $userdetails = fetchUserDetails(NULL, NULL, $userId);
  }


$userPermission = fetchUserPermissions($userId);
$permissionData = fetchAllPermissions();

$grav = get_gravatar(strtolower(trim($userdetails->email)));
$useravatar = '<img src="'.$grav.'" class="img-responsive img-thumbnail" alt="">';
//
?>
<div id="page-wrapper">

<div class="container">

<?=resultBlock($errors,$successes);?>
<?=$validation->display_errors();?>


<div class="row">
	<div class="col-xs-12 col-sm-2"><!--left col-->
	<?php echo $useravatar;?>
	</div><!--/col-2-->

	<div class="col-xs-12 col-sm-10">
	<form class="form" name='adminUser' action='admin_user.php?id=<?=$userId?>' method='post'>

	<h3>اطلاعات کاربر</h3>
  <div class="panel panel-default">
    <div class="panel-heading">ID کاربر: <?=$userdetails->id?></div>
    <div class="panel-body">

      <label>عضویت: </label> <?=$userdetails->join_date?><br/>
      <label>آخرین بازدید: </label> <?=$userdetails->last_login?><br/>
      <label>تعداد ورود: </label> <?=$userdetails->logins?><br/>

      <label>نام کاربری:</label>
      <input  class='form-control' type='text' name='username' value='<?=$userdetails->username?>' />

      <label>ایمیل:</label>
      <input class='form-control' type='text' name='email' value='<?=$userdetails->email?>' />

      <div class="form-group">
        <label>رمز عبور جدید (حداقل <?=$settings->min_pw?> و حداکثر <?=$settings->max_pw?> حرف.)</label>
        <input class='form-control' type='password' name='password' />
      </div>

      <div class="form-group">
        <label>تکرار رمز عبور</label>
        <input class='form-control' type='password' name='confirm' />
      </div>

      <div class="form-group">
        <label>موجودی حساب کاربری (تومان)</label>
        <input class='form-control' type='number' name='account_charge' value='<?=$userdetails->account_charge?>'  />
      </div>

    </div>
	</div>

  <div class="col-md-6">
    <div class=" panel panel-default">
        <div class="panel-heading">اطلاعات فردی</div>
        <div class="panel-body">
                        <div class="form-group">
                            <label>نام</label>
                            <input  class='form-control' type='text' name='fname' value='<?=$userdetails->fname?>' />
                        </div>

                        <div class="form-group">
                            <label>نام خانوادگی</label>
                            <input  class='form-control' type='text' name='lname' value='<?=$userdetails->lname?>' />
                        </div>

                        <div class="form-group">
                            <label>کد ملی</label>
                            <input  class='form-control' type='text' name='icode' value='<?=$userdetails->icode?>' />
                        </div>

                        <div class="form-group">
                            <label>شماره تماس</label>
                            <input  class='form-control' type='text' name='phnumber' value='<?=$userdetails->phnumber?>' />
                        </div>
        </div>
    </div><!--END OF panel-default  -->
</div><!--END OF col  -->
<div class="col-md-6">
    <div class=" panel panel-default">
        <div class="panel-heading">اطلاعات تحصیلی</div>
        <div class="panel-body">

            <div class="form-group">
                <label for="status">وضعیت*</label><br>
                <select name="status" id = "status" class="form-control" onchange="disableInput()" >
                    <option></option>
                    <option value="مدیر برنامه ها" <?php if($userdetails->status == "مدیر برنامه ها") echo "selected"; ?> >مدیر برنامه ها</option>
                    <option value="توسعه دهنده" <?php if($userdetails->status == "توسعه دهنده") echo "selected"; ?> >توسعه دهنده</option>
                    <option value="مدیر سایت" <?php if($userdetails->status == "مدیر سایت") echo "selected"; ?> >مدیر سایت</option>
                    <option value="فارغ التحصیل"  <?php if($userdetails->status == "فارغ التحصیل") echo "selected"; ?> >فارغ التحصیل</option>
                    <option value="دانشجو"  <?php if($userdetails->status == "دانشجو") echo "selected"; ?> >دانشجو</option>
                    <option value="کارمند"  <?php if($userdetails->status == "کارمند") echo "selected"; ?> >کارمند</option>
                    <option value="استاد"  <?php if($userdetails->status == "استاد") echo "selected"; ?> >استاد</option>
                    <option value="آزاد"  <?php if($userdetails->status == "آزاد") echo "selected"; ?> >آزاد</option>
                </select>
            </div>

            <div class="form-group">
                <label for="std_number">شماره دانشجویی*</label>
                <?php   
                if ($userdetails->status == "دانشجو"){
                    echo "<input type='text' class='form-control' id='std_number' name='std_number' placeholder='شماره دانشجویی' value='$userdetails->std_number' >";
                }else{
                    echo "<input type='text' class='form-control' id='std_number' name='std_number' placeholder='شماره دانشجویی' readonly >";
                }
                ?>
            </div>

            <div class="form-group">
                <label for="major">رشته تحصیلی</label>
                <input type="text" class="form-control" id="major" name="major" placeholder="رشته تحصیلی" value='<?=$userdetails->major?>'>
            </div>

            <div class="form-group">
                <label for="dorms">خوابگاه</label><br>
                <select name="dorms" id = "dorms" class="form-control" <?php if($userdetails->status != "دانشجو") echo "disabled='disabled'"; ?> >
                    <option ></option>
                    <option value="تهرانی"  <?php if($userdetails->dorms == "تهرانی") echo "selected"; ?>>تهرانی</option>
                    <option value="طرشت 3"  <?php if($userdetails->dorms == "طرشت 3") echo "selected"; ?>>طرشت 3</option>
                    <option value="احمدی روشن"  <?php if($userdetails->dorms == "احمدی روشن") echo "selected"; ?>>احمدی روشن</option>
                    <option value="طرشت 2"  <?php if($userdetails->dorms == "طرشت 2") echo "selected"; ?>>طرشت 2</option>
                    <option value="آزادی"  <?php if($userdetails->dorms == "آزادی") echo "selected"; ?>>آزادی</option>
                    <option value="وزوایی"  <?php if($userdetails->dorms == "وزوایی") echo "selected"; ?>>وزوایی</option>
                    <option value="شادمان"  <?php if($userdetails->dorms == "شادمان") echo "selected"; ?>>شادمان</option>
                    <option value="صادقی"  <?php if($userdetails->dorms == "صادقی") echo "selected"; ?>>صادقی</option>
                    <option value="متأهلی"  <?php if($userdetails->dorms == "متأهلی") echo "selected"; ?>>متأهلی</option>
                    <option value="شوریده"  <?php if($userdetails->dorms == "شوریده") echo "selected"; ?>>شوریده</option>
                    <option value="ولیعصر"  <?php if($userdetails->dorms == "ولیعصر") echo "selected"; ?>>ولیعصر</option>
                    <option value="12 واحدی"  <?php if($userdetails->dorms == "12 واحدی") echo "selected"; ?>>12 واحدی</option>
                    <option value="حیدرتاش"  <?php if($userdetails->dorms == "حیدرتاش") echo "selected"; ?>>حیدرتاش</option>
                    <option value="مصلی نژاد"  <?php if($userdetails->dorms == "مصلی نژاد") echo "selected"; ?>>مصلی نژاد</option>
                </select>
            </div>

            <div class="form-group">
                <label for="emp_number">کد کارمندی*</label>
                <?php
                if ($userdetails->status == "کارمند"){
                    echo "<input type='text' class='form-control' id='emp_number' name='emp_number' placeholder='کد کارمندی' value='$userdetails->emp_number' >";
                }else{
                    echo "<input type='text' class='form-control' id='emp_number' name='emp_number' placeholder='کد کارمندی' readonly >";
                }
                ?>
            </div>

        </div>
    </div><!--END OF panel-default  -->
</div><!--END OF col  -->


	<h3>دسترسی ها</h3>
	<div class="panel panel-default">
		<div class="panel-heading">پاک کردن این دسترسی(ها):</div>
		<div class="panel-body">
		<?php
		//NEW List of permission levels user is apart of

		$perm_ids = [];
		foreach($userPermission as $perm){
			$perm_ids[] = $perm->permission_id;
		}

		foreach ($permissionData as $v1){
		if(in_array($v1->id,$perm_ids)  & ($v1->id!=4 || checkMenu(4,$user->data()->id) ) ){ ?>
		  <input type='checkbox' name='removePermission[]' id='removePermission[]' value='<?=$v1->id;?>' /> <?=$v1->name;?>
		<?php
		}
		}
		?>

		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">اضافه کردن این دسترسی(ها):</div>
		<div class="panel-body">
		<?php
		foreach ($permissionData as $v1){
		if(!in_array($v1->id, $perm_ids) & ( $v1->id!=4 || checkMenu(4,$user->data()->id) ) ){ ?>
		  <input type='checkbox' name='addPermission[]' id='addPermission[]' value='<?=$v1->id;?>' /> <?=$v1->name;?>
			<?php
		}
		}
		?>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">متفرقه:</div>
		<div class="panel-body">
		<label> مسدود کردن؟:</label>
		<select name="active" class="form-control">
			<option <?php if ($userdetails->permissions==1){echo "selected='selected'";} ?> value="1">خیر</option>
			<option <?php if ($userdetails->permissions==0){echo "selected='selected'";} ?>value="0">بله</option>
		</select>
		</div>
	</div>

	<input type="hidden" name="csrf" value="<?=Token::generate();?>" />
	<input class='btn btn-primary' type='submit' value='به روز رسانی' class='submit' />
	<a class='btn btn-warning' href="admin_users.php">انصراف</a><br><br>

	</form>

	</div><!--/col-9-->
</div><!--/row-->

</div>
</div>


<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

    <!-- Place any per-page javascript here -->

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
