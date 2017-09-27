<style type="text/css">
    
    input{
        margin-left: 10px;
    }
    .container label{
        margin-top: 5px;
    }

</style>

<?php require_once 'init.php'; ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/header.php'; ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; ?>


<?php
if (!securePage($_SERVER['PHP_SELF'])){die();}?>

<?php
//dealing with if the user is logged in
if($user->isLoggedIn() && !checkMenu(2,$user->data()->id)){
	if (($settings->site_offline==1) && (!in_array($user->data()->id, $master_account)) && ($currentPage != 'login.php') && ($currentPage != 'maintenance.php')){
		$user->logout();
		Redirect::to($us_url_root.'users/maintenance.php');
	}
}


$emailQ = $db->query("SELECT * FROM email");
$emailR = $emailQ->first();
// dump($emailR);
// dump($emailR->email_act);
//PHP Goes Here!
$errors=[];
$successes=[];
$userId = $user->data()->id;
$grav = get_gravatar(strtolower(trim($user->data()->email)));
$validation = new Validate();
$userdetails=$user->data();
//Temporary Success Message
$holdover = Input::get('success');
if($holdover == 'true'){
    bold("حساب کاربری به روز رسانی شد");
}
//Forms posted
if(!empty($_POST)) {
    $token = $_POST['csrf'];
    if(!Token::check($token)){
        die('Token doesn\'t match!');
    }else {
        //Update display name
        if ($userdetails->username != $_POST['username']){
            $displayname = Input::get("username");
            $fields=array(
                'username'=>$displayname,
                'un_changed' => 1,
            );
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
                if(($settings->change_un == 2) && ($user->data()->un_changed == 1)){
                    Redirect::to('user_settings.php?err=Username+has+already+been+changed+once.');
                }
                $db->update('users',$userId,$fields);
                $successes[]="نام کاربری به روز رسانی شد.";
            }else{
                //validation did not pass
                foreach ($validation->errors() as $error) {
                    $errors[] = $error;
                }
            }
        }else{
            $displayname=$userdetails->username;
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
                $successes[]='نام به روز رسانی شد.';
            }else{
                //validation did not pass
                foreach ($validation->errors() as $error) {
                    $errors[] = $error;
                }
            }
        }else{
            $fname=$userdetails->fname;
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
                $successes[]='نام خانوادگی به روز رسانی شد.';
            }else{
                //validation did not pass
                foreach ($validation->errors() as $error) {
                    $errors[] = $error;
                }
            }
        }else{
            $lname=$userdetails->lname;
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
                $successes[]='کد ملی به روز رسانی شد.';
            }else{
                //validation did not pass
                foreach ($validation->errors() as $error) {
                    $errors[] = $error;
                }
            }
        }else{
            $icode=$userdetails->icode;
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
                $successes[]='شماره تماس به روز رسانی شد.';
            }else{
                //validation did not pass
                foreach ($validation->errors() as $error) {
                    $errors[] = $error;
                }
            }
        }else{
            $phnumber=$userdetails->phnumber;
        }
        //Update status
        if ($userdetails->status != $_POST['status']){
            $status = Input::get("status");
            if ($status == "دانشجو"){
                    $std_number = Input::get("std_number");
                    $dorms = Input::get("dorms");
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
                        //validation did not pass
                        foreach ($validation->errors() as $error) {
                            $errors[] = $error;
                        }
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
                        //validation did not pass
                        foreach ($validation->errors() as $error) {
                            $errors[] = $error;
                        }
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
            $status=$userdetails->status;
        }
    //Update std number and dorms
    if ($status == "دانشجو"){
        // std_number
        if ($userdetails->std_number != $_POST['std_number']){
            $std_number = Input::get("std_number");
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
                //validation did not pass
                foreach ($validation->errors() as $error) {
                    $errors[] = $error;
                }
            }
        }else{
            $std_number=$userdetails->std_number;
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
                //validation did not pass
                foreach ($validation->errors() as $error) {
                    $errors[] = $error;
                }
            }
        }else{
            $dorms=$userdetails->dorms;
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
                //validation did not pass
                foreach ($validation->errors() as $error) {
                    $errors[] = $error;
                }
            }
        }else{
            $emp_number=$userdetails->emp_number;
        }
    }
        //Update major
        if ($userdetails->major != $_POST['major']){
            $major = Input::get("major");
            $fields=array('major'=>$major);
            $validation->check($_POST,array(
                'major' => array(
                    'display' => 'رشته تحصیلی',
                    'required' => false,
                )
            ));
            if($validation->passed()){
                $db->update('users',$userId,$fields);
                $successes[]='رشته تحصیلی به روز رسانی شد.';
            }else{
                //validation did not pass
                foreach ($validation->errors() as $error) {
                    $errors[] = $error;
                }
            }
        }else{
            $major=$userdetails->major;
        }
        //Update gender
        if ($userdetails->gender != $_POST['gender']){
            $gender = Input::get("gender");
            $fields=array('gender'=>$gender);
            $validation->check($_POST,array(
                'gender' => array(
                    'display' => 'جنسیت',
                    'required' => true,
                )
            ));
            if($validation->passed()){
                $db->update('users',$userId,$fields);
                $successes[]='جنسیت به روز رسانی شد.';
            }else{
                //validation did not pass
                foreach ($validation->errors() as $error) {
                    $errors[] = $error;
                }
            }
        }else{
            $gender=$userdetails->gender;
        }
        //Update interested
        if ($userdetails->interested != $_POST['interested']){
            $interested = Input::get("interested");
            $fields=array('interested'=>$interested);
            $validation->check($_POST,array(
                'interested' => array(
                    'display' => 'علاقه مندی',
                    'required' => false,
                )
            ));
            if($validation->passed()){
                $db->update('users',$userId,$fields);
                $successes[]='علاقه مندی به روز رسانی شد.';
            }else{
                //validation did not pass
                foreach ($validation->errors() as $error) {
                    $errors[] = $error;
                }
            }
        }else{
            $interested=$userdetails->interested;
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
                if($emailR->email_act==1){
                    $db->update('users',$userId,['email_verified'=>0]);
                }
                $successes[]='ایمیل به روز رسانی شد.';
            }else{
                //validation did not pass
                foreach ($validation->errors() as $error) {
                    $errors[] = $error;
                }
            }
        }else{
            $email=$userdetails->email;
        }
        if(!empty($_POST['password'])) {
            $validation->check($_POST,array(
                'old' => array(
                    'display' => 'رمز عبور قبلی',
                    'required' => true,
                ),
                'password' => array(
                    'display' => 'رمز عبور جدید',
                    'required' => true,
                    'min' => $settings->min_pw,
                'max' => $settings->max_pw,
                ),
                'confirm' => array(
                    'display' => 'تکرار رمز عبور جدید',
                    'required' => true,
                    'matches' => 'password',
                ),
            ));
            foreach ($validation->errors() as $error) {
                $errors[] = $error;
            }
            if (!password_verify(Input::get('old'),$user->data()->password)) {
                foreach ($validation->errors() as $error) {
                    $errors[] = $error;
                }
                $errors[]='یک مشکل با رمز عبور شما وجود دارد.';
            }
            if (empty($errors)) {
                //process
                $new_password_hash = password_hash(Input::get('password'),PASSWORD_BCRYPT,array('cost' => 12));
                $user->update(array('password' => $new_password_hash,),$user->data()->id);
                $successes[]='Password updated.';
            }
        }
    }
}else{
    $displayname=$userdetails->username;
    $fname=$userdetails->fname;
    $lname=$userdetails->lname;
    $gender=$userdetails->gender;
    $email=$userdetails->email;
    $icode=$userdetails->icode;
    $phnumber=$userdetails->phnumber;
    $status=$userdetails->status;
    $major=$userdetails->major;
    $dorms=$userdetails->dorms;
    $std_number=$userdetails->std_number;
    $emp_number=$userdetails->emp_number;
    $interested=$userdetails->interested;
}
?>
<div id="page-wrapper">
    <div class="container">
        <div class="well">
            <div class="row">
                <div class="col-xs-12 col-md-2">
                    <p><img src="<?=$grav; ?>" class="img-thumbnail" alt="Generic placeholder thumbnail"></p>
                </div>
                <div class="col-xs-12 col-md-10">
                    <h1>حساب کاربری خود را به روز رسانی کنید</h1>
                    <strong>آیا میخواهید تصویر پروفایل خود را تغییر دهید؟ </strong><br> Visit <a href="https://en.gravatar.com/">https://en.gravatar.com/</a> and setup an account with the email address <?=$email?>.  It works across millions of sites. It's fast and easy!<br>
                    <span class="bg-danger" style="color: red"><?=display_errors($errors);?></span>
                    <span style=" color: green;"><?=display_successes($successes);?></span>

                    <form name='updateAccount' action='user_settings.php' method='post'>                        

<div class="col-md-6">
    <div class=" panel panel-default">
        <div class="panel-heading">اطلاعات حساب کاربری</div>
        <div class="panel-body">
                        <div class="form-group">
                            <label>نام کاربری</label>
                            <?php if (($settings->change_un == 0) || (($settings->change_un == 2) && ($user->data()->un_changed == 1)) ) {
                                echo "<input  class='form-control' type='text' name='username' value='$displayname' readonly/>";
                            }else{
                                echo "<input  class='form-control' type='text' name='username' value='$displayname'>";
                            }
                            ?>
                        </div>

                        <div class="form-group">
                            <label>ایمیل</label>
                            <input class='form-control' type='text' name='email' value='<?=$email?>' />
                        </div>

                        <div class="form-group">
                            <label>رمز عبور قبلی (برای تغییر رمز ضروری است)</label>
                            <input class='form-control' type='password' name='old' />
                        </div>

                        <div class="form-group">
                            <label>رمز عبور جدید (حداقل <?=$settings->min_pw?> و حداکثر<?=$settings->max_pw?> کاراکتر)</label>
                            <input class='form-control' type='password' name='password' />
                        </div>

                        <div class="form-group">
                            <label>تکرار رمز عبور</label>
                            <input class='form-control' type='password' name='confirm' />
                        </div>

                        <label for="interested">علاقه مند به همکاری</label><br>
                        <input type="radio" class="form-contro" id="interested" name="interested" value="بله" <?php if($interested == "بله") echo "checked"; ?>>بله<br>
                        <input type="radio" class="form-contro" id="interested" name="interested" value="خیر"  <?php if($interested == "خیر") echo "checked"; ?>>خیر<br>
        </div>
    </div><!--END OF panel-default  -->
</div><!--END OF col  -->

<div class="col-md-6">
    <div class=" panel panel-default">
        <div class="panel-heading">اطلاعات فردی</div>
        <div class="panel-body">
                        <div class="form-group">
                            <label>نام</label>
                            <input  class='form-control' type='text' name='fname' value='<?=$fname?>' />
                        </div>

                        <div class="form-group">
                            <label>نام خانوادگی</label>
                            <input  class='form-control' type='text' name='lname' value='<?=$lname?>' />
                        </div>

                        <div class="form-group">
                            <label>کد ملی</label>
                            <input  class='form-control' type='text' name='icode' value='<?=$icode?>' />
                        </div>

                        <div class="form-group">
                            <label>شماره تماس</label>
                            <input  class='form-control' type='text' name='phnumber' value='<?=$phnumber?>' />
                        </div>
                        <div class="form-group">
                            <label for="gender">جنسیت</label><br>
                            <input type="radio" class="form-contro" id="gender" name="gender" value="آقا" <?php if($gender == "آقا") echo "checked"; ?>>آقا<br>
                            <input type="radio" class="form-contro" id="gender" name="gender" value="خانم"  <?php if($gender == "خانم") echo "checked"; ?>>خانم<br>
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
                    <option value="فارغ التحصیل"  <?php if($status == "فارغ التحصیل") echo "selected"; ?> >فارغ التحصیل</option>
                    <option value="دانشجو"  <?php if($status == "دانشجو") echo "selected"; ?> >دانشجو</option>
                    <option value="کارمند"  <?php if($status == "کارمند") echo "selected"; ?> >کارمند</option>
                    <option value="استاد"  <?php if($status == "استاد") echo "selected"; ?> >استاد</option>
                    <option value="آزاد"  <?php if($status == "آزاد") echo "selected"; ?> >آزاد</option>
                </select>
            </div>

            <div class="form-group">
                <label for="std_number">شماره دانشجویی*</label>
                <?php   
                if ($status == "دانشجو"){
                    echo "<input type='text' class='form-control' id='std_number' name='std_number' placeholder='شماره دانشجویی' value='$std_number' >";
                }else{
                    echo "<input type='text' class='form-control' id='std_number' name='std_number' placeholder='شماره دانشجویی' readonly >";
                }
                ?>
            </div>

            <div class="form-group">
                <label for="major">رشته تحصیلی</label>
                <input type="text" class="form-control" id="major" name="major" placeholder="رشته تحصیلی" value='<?=$major?>'>
            </div>

            <div class="form-group">
                <label for="dorms">خوابگاه</label><br>
                <select name="dorms" id = "dorms" class="form-control" <?php if($status != "دانشجو") echo "disabled='disabled'"; ?> >
                    <option ></option>
                    <option value="تهرانی"  <?php if($dorms == "تهرانی") echo "selected"; ?>>تهرانی</option>
                    <option value="طرشت 3"  <?php if($dorms == "طرشت 3") echo "selected"; ?>>طرشت 3</option>
                    <option value="احمدی روشن"  <?php if($dorms == "احمدی روشن") echo "selected"; ?>>احمدی روشن</option>
                    <option value="طرشت 2"  <?php if($dorms == "طرشت 2") echo "selected"; ?>>طرشت 2</option>
                    <option value="آزادی"  <?php if($dorms == "آزادی") echo "selected"; ?>>آزادی</option>
                    <option value="وزوایی"  <?php if($dorms == "وزوایی") echo "selected"; ?>>وزوایی</option>
                    <option value="شادمان"  <?php if($dorms == "شادمان") echo "selected"; ?>>شادمان</option>
                    <option value="صادقی"  <?php if($dorms == "صادقی") echo "selected"; ?>>صادقی</option>
                    <option value="متأهلی"  <?php if($dorms == "متأهلی") echo "selected"; ?>>متأهلی</option>
                    <option value="شوریده"  <?php if($dorms == "شوریده") echo "selected"; ?>>شوریده</option>
                    <option value="ولیعصر"  <?php if($dorms == "ولیعصر") echo "selected"; ?>>ولیعصر</option>
                    <option value="12 واحدی"  <?php if($dorms == "12 واحدی") echo "selected"; ?>>12 واحدی</option>
                    <option value="حیدرتاش"  <?php if($dorms == "حیدرتاش") echo "selected"; ?>>حیدرتاش</option>
                    <option value="مصلی نژاد"  <?php if($dorms == "مصلی نژاد") echo "selected"; ?>>مصلی نژاد</option>
                </select>
            </div>

            <div class="form-group">
                <label for="emp_number">کد کارمندی*</label>
                <?php
                if ($status == "کارمند"){
                    echo "<input type='text' class='form-control' id='emp_number' name='emp_number' placeholder='کد کارمندی' value='$emp_number' >";
                }else{
                    echo "<input type='text' class='form-control' id='emp_number' name='emp_number' placeholder='کد کارمندی' readonly >";
                }
                ?>
            </div>

        </div>
    </div><!--END OF panel-default  -->
</div><!--END OF col  -->

                        <input type="hidden" name="csrf" value="<?=Token::generate();?>" />

                        <p><input class='btn btn-primary' type='submit' value='به روز رسانی' class='submit' /></p>
                        <p><a class="btn btn-info" href="account.php">انصراف</a></p>

                    </form>
                    <?php
                    if(isset($user->data()->oauth_provider) && $user->data()->oauth_provider != null){
                        echo "<strong>NOTE:</strong> If you originally signed up with your Google/Facebook account, you will need to use the forgot password link to change your password...unless you're really good at guessing.";
                    }
                    ?>
                </div>
            </div>
        </div>


    </div> <!-- /container -->

</div> <!-- /#page-wrapper -->


<!-- footers -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

<!-- Place any per-page javascript here -->

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
