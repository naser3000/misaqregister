<?php require_once 'init.php'; ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/header.php'; ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; ?>

<?php if (!securePage($_SERVER['PHP_SELF'])){die();} ?>
<?php

//PHP Goes Here!
delete_user_online(); //Deletes sessions older than 24 hours

//Find users who have logged in in X amount of time.
$date = date("Y-m-d H:i:s");

$plansData = fetchAllPlans(); //Fetch information for all plans
if(!empty($_POST)) {
  if(isset($_POST['send_sms'])){
  	echo "***********************************************************************";
    $phone_numbers = explode(',', $_POST['send_sms']);
	$text = $_POST['message'];
	$receiver = $phone_numbers;
    $res = send_group_sms($text, $receiver);
    echo '<div class="alert alert-success" role="alert"><?=$res?></div><br/>';
    echo '<div class="alert alert-success" role="alert"><?=$res?></div><br/>';
    echo '<div class="alert alert-success" role="alert"><?=$res?></div><br/>';
    echo $res;
    echo "***********************************************************************";
    // foreach ($phone_numbers as $phn) {
    // 	echo $phn;
    // }
    // echo $text;
  }
  if(isset($_POST['send_email'])){
    $mail_address = explode(',', $_POST['send_email']);
    foreach ($mail_address as $mail) {
	    $to = $mail;
		$subject = $_POST['subject'];
		$body = $_POST['message'];
		$mail_result = email($to,$subject,$body);
		if($mail_result){
		    echo '<div class="alert alert-success" role="alert">Mail sent successfully</div><br/>';
		}else{
		    echo '<div class="alert alert-danger" role="alert">Mail ERROR</div><br/>';
		}
    }
  }
}
?>
<div id="page-wrapper"> <!-- leave in place for full-screen backgrounds etc -->

  <!--send email PLANE MODAL -->
  <div class="modal fade" id="send_email">
    <div class="modal-dialog">
      <div class="modal-content">
      	<form class="form" name="send_email" action="admin_plans.php" method="post">
	        <div class="modal-header">
	          <h1>ارسال ایمیل</h1>
	        </div>
	        <div class="modal-body">
	        	<label >موضوع</label>
	            <input  class="form-control" type="text" name="subject" id="title" placeholder="موضوع">       
	            <label>متن پیام</label>
	            <textarea class="form-control" id="description" name="message" placeholder="پیام ..." rows="5"></textarea>
	        </div>
	        <div class="modal-footer">
	          <span class="pull-left" data-dismiss="modal" name="send_email" onclick=""><a class="btn btn-default btn-xs" data-toggle="modal" >انصراف</a></span>
	          <input type="hidden" name="send_email" id="mail_address">
	          <button type="submit" class="btn btn-warning btn-xs pull-left" style="margin-left: 10px;">ارسال ایمیل</button>
	        </div>
	    </form>
      </div><!-- end .modal-content -->
    </div><!-- end .modal-dialog -->
  </div><!-- end .modal -->
  <!-- END MODAL -->

  <!--send SMS PLANE MODAL -->
  <div class="modal fade" id="send_sms">
    <div class="modal-dialog">
      <div class="modal-content">
      	<form class="form" name="send_sms" action="admin_plans.php" method="post">
	        <div class="modal-header">
	          <h1>ارسال پیامک</h1>
	        </div>
	        <div class="modal-body">      
	            <label>متن پیام</label>
	            <textarea class="form-control" id="description" name="message" placeholder="پیام ..." rows="5"></textarea>
	        </div>
	        <div class="modal-footer">
	          <span class="pull-left" data-dismiss="modal" name="send_sms" onclick=""><a class="btn btn-default btn-xs" data-toggle="modal" >انصراف</a></span>
	          <input type="hidden" id="phone_numbers" name="send_sms">
	          <button type="submit" class="btn btn-info btn-xs pull-left" style="margin-left: 10px;" >ارسال پیامک</button>
	        </div>
	    </form>
      </div><!-- end .modal-content -->
    </div><!-- end .modal-dialog -->
  </div><!-- end .modal -->
  <!-- END MODAL -->



	<div class="container"> <!-- -fluid -->
		<div class="row">
		    <div class="col-xs-12 col-md-6 pull-right">
			    <h1>مدیریت برنامه ها</h1>
		    </div>
		    <!-- <div class="col-xs-12 col-md-6">
	            <form class="">
	                <label for="system-search">جستجو:</label>
	                <div class="input-group">
	                    <input class="form-control" id="system-search" name="q" placeholder="جستجو..." type="text">
	                    <span class="input-group-btn">
						      <button type="submit" class="btn btn-default"><i class="fa fa-times"></i></button>
	                    </span>
	                </div>
	            </form>
        	</div>  -->
	    </div>
<div class="row"> <!-- row for Users, Permissions, Pages, Email settings panels -->
	<hr>
	<?php
		//Cycle through plans
		foreach ($plansData as $v1) {
			$plan_registered = fetchPlanRegisteredUsers($v1->id);
			$users_registered = [];
			foreach ($plan_registered as $rgs) {
				//array_push($users_registered, fetchUserDetails(null, null, $rgs->user_id));
				$users_registered[] = fetchUserDetails(null, null, $rgs->user_id);
			}
			$users = json_encode($users_registered);
			$regiters = json_encode($plan_registered);
			
	?>
	<!-- Plan Panel -->
	<div class="col-xs-6 col-md-4 pull-right">
		<div class="panel panel-default">
			<div class="panel-heading">
				<strong><?=$v1->title?></strong>
				<span class="pull-left"><?=$v1->id?></span>
			</div>
			<div class="panel-body text-center"><div class="huge" style="font-size: 16px; text-align: justify;"><span><?=$v1->description?>	</span></div></div>	
			<div class="panel-footer">	
				<a class="btn btn-info btn-xs" href="admin_plan.php?id=<?=$v1->id?>" target="blank"><span class="pull-right">مشاهده جزئیات</span></a>
				<a class="btn btn-primary btn-xs" id="showRegistered<?=$v1->id?>" onclick='showRegistered(<?=$users?>, <?=$regiters?>, "<?=$v1->title?>", "<?=$v1->id?>")' ><span class="pull-right">مشاهده شرکت کنندگان</span></a>
				<a class="btn btn-warning btn-xs hidden" id="dontShowRegistered<?=$v1->id?>" onclick='dontShowRegistered(<?=$users?>, "<?=$v1->id?>")' ><span class="pull-right">عدم مشاهده شرکت کنندگان</span></a>
				<span class="pull-left"><i class='fa fa-arrow-circle-left'></i></span>
				<div class="clearfix"></div>	
			</div> <!-- /panel-footer -->
		</div><!-- /panel -->		
	</div><!-- /col -->

	<?php } ?>

	<!-- Plan Panel -->
	<div class="col-xs-3 col-md-2 pull-right">
		<div class="panel panel-default">
			<div class="panel-heading"><strong>اضافه کردن برنامه جدید</strong></div>
			<div class="panel-body text-center"><div class="huge"><i class='fa fa-plus fa-x1'></i></div></div>	
			<div class="panel-footer">
				<a class="btn btn-success btn-xs" id="" href="admin_add_plan.php" target="blank"><span class="pull-right">اضافه کردن</span></a>
				<span class="pull-left"><i class='fa fa-arrow-circle-left'></i></span>	
				<div class="clearfix"></div>	
			</div> <!-- /panel-footer -->
		</div><!-- /panel -->
	</div><!-- /col -->

</div> <!-- /.row -->

	<br><hr><hr>
	<div class="row hidden" id="plan_registered_data">
		<div class="col-xs-12 pull-right">
            <form class="">
                <label for="system-search">جستجو:</label>
                <div class="input-group">
                    <input class="form-control" id="system-search" name="q" placeholder="جستجوی کاربران..." type="text">
                    <span class="input-group-btn">
					      <button type="submit" class="btn btn-default"><i class="fa fa-times"></i></button>
                    </span>
                </div>
            </form>
    	</div><div class="clearfix"></div><br>
        <div class="col-xs-12">
			<form name="adminUsers" action="" method="post">
				<div class="allutable table-responsive" id="plan_registered_data">
				<table class='table table-bordered table-list-search' id="plan_registered_data">
					<thead>
						<tr>
							<th>نام و نام خانوادگی</th><th>کدملی</th><th>جنسیت</th><th>وضعیت</th><th>شماره دانشجویی</th><th>شماره تماس</th><th>ایمیل</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
				</div>
			</form>
        <button class='btn btn-info' id="btnimportmail" data-toggle="modal" data-target="#send_sms" onclick="importPhoneNumbers()">ارسال پیامک</button>
        <button class='btn btn-warning' id="btnimportphone" data-toggle="modal" data-target="#send_email" onclick="importEmailAddress()">ارسال ایمیل</button>
        <button class='btn btn-primary pull-left' id="btnExport" onclick="">خروجی اکسل</button><br><br>

		</div>
	</div>

<!-- CHECK IF ADDITIONAL ADMIN PAGES ARE PRESENT AND INCLUDE IF AVAILABLE -->

<?php
if(file_exists($abs_us_root.$us_url_root.'usersc/includes/admin_panels.php')){
	require_once $abs_us_root.$us_url_root.'usersc/includes/admin_panels.php';
}
?>

<!-- /CHECK IF ADDITIONAL ADMIN PAGES ARE PRESENT AND INCLUDE IF AVAILABLE -->


</div> <!-- /container -->
</div> <!-- /#page-wrapper -->

<!-- footers -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

<script src="js/jquery.min.js"></script>
<script src="js/search.js" charset="utf-8"></script>
<script src="js/admin_plans.js"></script>

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
