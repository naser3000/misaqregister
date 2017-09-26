<?php require_once 'init.php'; ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/header.php'; ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; ?>

<?php if (!securePage($_SERVER['PHP_SELF'])){die();}?>
<?php

//dealing with if the user is logged in
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
$plansData = fetchAllPlans(); //Fetch information for all plans
 ?>

<div id="page-wrapper">
<div class="container">



	<?php
		//Cycle through plans
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

						if ($related_capacity_n == 0) {

							?>
							</div>
							<div class="modal-body">شما نمی توانید در این برنامه شرکت کنید.</div>
							<div class="modal-footer"></div>
						</div>
					</div>
				</div>
				<?php
								continue;
						}
					?>
					<br>
					<h2 class="modal-title">ثبت نام در <?=$pld->title?> .</h2>
					<input class="form-control" type="text" name="" id="" readonly="" value="هزینه 10000 تومان">
				</div>
				<div class="modal-body">
					<form>
						<div class="col-md-3 col-sm-3 col-xs-12 pull-right">
							<label>نام</label>
							<input class="form-control" type="text" name="" id="name" readonly="" value="<?=ucfirst($user->data()->fname)." ".ucfirst($user->data()->lname)?>">
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12 pull-right">
							<label>کدملی</label>
							<input class="form-control" type="text" name="" id="code" readonly="" value="<?=ucfirst($user->data()->status)?>">
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12 pull-right">
							<label>شماره دانشجویی</label>
							<input class="form-control" type="text" name="" id="stdn" readonly="" value="<?=ucfirst($user->data()->std_number)?>">
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12 pull-right">
							<label>شماره تماس</label>
							<input class="form-control" type="text" name="" id="stdn" readonly="" value="<?=ucfirst($user->data()->std_number)?>">
						</div>
			
						<div class="col-md-3 col-sm-3 col-xs-12 pull-right">
							<label>انتخاب</label><br>
							<input type="checkbox" name="" id="firs_participant">
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12 pull-right">
							<label>نام همراه اول</label>
							<input class="form-control" type="text" name="" id="name" placeholder="نام">
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12 pull-right">
							<label>کدملی همراه اول</label>
							<input class="form-control" type="text" name="" id="code" placeholder="کدملی">
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12 pull-right">
							<label>هزینه همراه</label>
							<input class="form-control" type="text" name="" id="stdn" readonly="" value="<?=$related_capacity->participant_cost?> تومان">
						</div>
			
						<div class="col-md-3 col-sm-3 col-xs-12 pull-right">
							<label>انتخاب</label><br />
							<input type="checkbox" name="" id="second_participant">
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12 pull-right">
							<label>نام همراه دوم</label>
							<input class="form-control" type="text" name="" id="name" placeholder="نام">
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12 pull-right">
							<label>کدملی همراه دوم</label>
							<input class="form-control" type="text" name="" id="code" placeholder="کدملی">
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12 pull-right">
							<label>هزینه همراه</label>
							<input class="form-control" type="text" name="" id="stdn" readonly="" value="<?=$related_capacity->participant_cost?> تومان">
						</div>
						<div class="clearfix"></div>
						<input class="form-control" type="text" name="" id="" readonly="" value="مجموع هزینه ها: 10000 تومان" style="margin: 0px;">
						
					</form>
				</div>
				<div class="modal-footer">
					<span><a href="#" class="btn btn-success mini-btn">تأیید اطلاعات</a></span>
					<span class="close pull-left" data-dismiss="modal"><a href="#" class="btn btn-warning mini-btn">انصراف</a></span>
				</div>
				
			</div><!-- end .modal-content -->
		</div><!-- end .modal-dialog -->
	</div><!-- end .modal -->

	<!-- END MODAL -->

	<?php } ?>


<div class="well">
<div class="row">
	<div class="col-xs-12 col-sm-4 col-md-3 pull-right">
		<p><img src="<?=$grav; ?>" class="img-thumbnail" alt="Generic placeholder thumbnail"></p>
		<p><a href="user_settings.php" class="btn btn-primary equal-btn">ویرایش اطلاعات</a></p>
		<p ><a class="btn btn-primary equal-btn" href="profile.php?id=<?=$get_info_id;?>" role="button">پروفایل</a></p>

	</div>
	<div class="col-xs-12 col-sm-4 col-md-3 pull-right">
		<h1><?=$userdetails->username?></h1>
		<p><?=$userdetails->fname." ".$userdetails->lname?></p>
		
		<p>وضعیت: <?=$userdetails->status?></p>
		<?php if ($userdetails->status == "دانشجو") {?>
			<p>مقطع: <?=$userdetails->grade?></p>
		<?php } ?>
		
		<p>کد ملی: <?=$userdetails->icode?></p>
		<p>شماره تماس: <?=$userdetails->phnumber?></p>
		<?php if ($userdetails->status == "دانشجو") {?>
			<p>شماره دانشجویی: <?=$userdetails->std_number?></p>
		<?php } ?>
		<?php if ($userdetails->status == "کارمند") {?>
			<p>کد پرسنلی: <?=$userdetails->emp_number?></p>
		<?php } ?>

	</div>
	<div class="col-xs-12 col-sm-4 col-md-3 pull-right">
		<h1>اطلاعات حساب کاربری</h1>
		<p>ایمیل: <?=$userdetails->email?></p>
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
		//Cycle through plans
		foreach ($plansData as $v1) {
	?>
	<!-- Plan Panel -->
	<div class="col-xs-12 col-sm-6 col-md-4 pull-right">
		<div class="panel panel-default">
			<div class="panel-heading">
				<strong><?=$v1->title?></strong>
				<span class="pull-left"><?=$v1->id?></span>
			</div>
			<div class="panel-body text-center"><div class="huge" style="font-size: 16px; text-align: justify;"><span><?=$v1->description?>	</span></div></div>	
			<div class="panel-footer">	
				<span class="pull-left" ><a class="btn btn-info mini-btn" href="user_plan.php?id=<?=$v1->id?>">بیشتر</a></span>	
				<span class="pull-right margin-left"><a class="btn btn-success mini-btn" href="#" data-toggle="modal" data-target="#register_modal<?=$v1->id?>" >ثبت نام</a></span>
				<span class="pull-right margin-left"><a class="btn btn-warning mini-btn" href="">لغو ثبت نام</a></span>
				<div class="clearfix"></div>	
			</div> <!-- /panel-footer -->
		</div><!-- /panel -->		
	</div><!-- /col -->

	<?php } ?>

	

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
	.modal-header input{
		text-align: center;
		margin-top: 30px;
	}
	.modal-body input[type="checkbox"]{
		margin-top: 20px;
		margin-bottom: 0px;
	}
	.modal-body div.pull-right{
		text-align: center;
	}


</style>
