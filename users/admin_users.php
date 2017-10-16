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
		    <h1>مدیریت کاربران</h1>
	    </div>
        <div class="col-xs-12 col-md-6">
            <form class="">
                <label for="system-search">جستجو:</label>
                <div class="input-group">
                    <input class="form-control" id="system-search" name="q" placeholder="جستجوی کاربران..." type="text">
                    <span class="input-group-btn">
					      <button type="submit" class="btn btn-default"><i class="fa fa-times"></i></button>
                    </span>
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

                <div class="well well-sm">
               	<h3 class="form-signin-heading"> اضافه کردن دستی
                <select name="perm">
                  <?php

                  foreach ($permOps as $permOp){
                    echo "<option value='$permOp->id'>$permOp->name</option>";
                  }
                  ?>
                  </select>
                  </h3>

               	<div class="form-group" >
                  <div class="col-xs-2" >
               		<input  class="form-control" type="text" name="username" id="username" placeholder="نام کاربری" value="<?php if (!$form_valid && !empty($_POST)){ echo $username;} ?>" required autofocus>
</div>
                  <div class="col-xs-2">
               		<input type="text" class="form-control" id="fname" name="fname" placeholder="نام" value="<?php if (!$form_valid && !empty($_POST)){ echo $fname;} ?>" required>
</div>
                  <div class="col-xs-2">
               		<input type="text" class="form-control" id="lname" name="lname" placeholder="نام خانوادگی" value="<?php if (!$form_valid && !empty($_POST)){ echo $lname;} ?>" required>
</div>
                  <div class="col-xs-2">
               		<input  class="form-control" type="text" name="email" id="email" placeholder="آدرس ایمیل" value="<?php if (!$form_valid && !empty($_POST)){ echo $email;} ?>" required >
</div>
                  <div class="col-xs-2">
               		<input  class="form-control" type="password" name="password" id="password" placeholder="رمز عبور" required aria-describedby="passwordhelp">
</div>
                  <div class="col-xs-2">
               		<input  type="password" id="confirm" name="confirm" class="form-control" placeholder="تکرار رمز عبور" required >
</div>
               	</div>

                <br /><br />
               	<input type="hidden" value="<?=Token::generate();?>" name="csrf">
	            <input class='btn btn-primary' type='submit' name='addUser' value='اضافه کردن دستی' />
              </div>
               </form>
               </div>
               </div>
        <div class="row">
        <div class="col-xs-12">
				 <div class="alluinfo">&nbsp;</div>
				<form name="adminUsers" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
              <div class="allutable table-responsive" id="user_data">
                <table class='table table-striped table-bordered table-list-search' id="user_data_table">
                  <thead>
                    <tr>
                    	<th>حذف</th><th>نام کاربری</th><th>نام</th><th>نام خانوادگی</th><th>جنسیت</th><th>کدملی</th><th>وضعیت</th><th>شماره دانشجویی</th><th>شماره تماس</th><th>ایمیل</th><th>علاقمندی</th><th>آخرین فعالیت</th>
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
                      <td><?=$v1->fname?></td>
                      <td><?=$v1->lname?></td>
                      <td><?=$v1->gender?></td>
                      <td><?=$v1->icode?></td>
                      <td><?=$v1->status?></td>
                      <td><?php if($v1->status == "دانشجو") { print_r($v1->std_number);}
                              elseif($v1->status == "کارمند") { print_r($v1->emp_number);}
                                else{print_r("-");}?></td>
                      <td><?=$v1->phnumber?></td>
                      <td><?=$v1->email?></td>
                      <td><?=$v1->interested?></td>
                      <td><?=$v1->last_login?></td>
                    </tr>
                  		<?php } ?>

                  </tbody>
                </table>
              </div>

				<input class='btn btn-danger pull-left' type='submit' name='Submit' value='حذف' />
				</form>
        <button class='btn btn-info' id="btnExport" onclick="">خروجی اکسل</button><br><br>

		  </div>
		</div>


  </div>
</div>


	<!-- End of main content section -->

<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

    <!-- Place any per-page javascript here -->
<script src="js/search.js" charset="utf-8"></script>

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>


<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#btnExport").click(function(e) {
      e.preventDefault();

      //getting data from our table
      var data_type = 'data:application/vnd.ms-excel';
      var table_div = document.getElementById('user_data');
      var table_html = table_div.outerHTML.replace(/ /g, '%20');

      var a = document.createElement('a');
      a.href = data_type + ', ' + table_html;
      a.download = 'exported_table_' + Math.floor((Math.random() * 9999999) + 1000000) + '.xls';
      a.click();
    });
  });

  /*function fnExcelReport()
  {
      var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
      var textRange; var j=0;
      tab = document.getElementById('user_data_table'); // id of table

      for(j = 0 ; j < tab.rows.length ; j++) 
      {     
          tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
          //tab_text=tab_text+"</tr>";
      }

      tab_text=tab_text+"</table>";
      tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
      tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
      tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

      var ua = window.navigator.userAgent;
      var msie = ua.indexOf("MSIE "); 

      if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
      {
          txtArea1.document.open("txt/html","replace");
          txtArea1.document.write(tab_text);
          txtArea1.document.close();
          txtArea1.focus(); 
          sa=txtArea1.document.execCommand("SaveAs",true,"Say Thanks to Sumit.xls");
      }  
      else                 //other browser not tested on IE 11
          sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  

      return (sa);
  }*/
</script>
