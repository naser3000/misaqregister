<?php require_once 'init.php'; ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/header.php'; ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; ?>

<?php if (!securePage($_SERVER['PHP_SELF'])){die();} ?>
<?php

//PHP Goes Here!
delete_user_online(); //Deletes sessions older than 24 hours

//Find users who have logged in in X amount of time.
$date = date("Y-m-d H:i:s");



$plansData = fetchAllPlansOrderByStartDate(); //Fetch information for all plans

?>
<div id="page-wrapper"> <!-- leave in place for full-screen backgrounds etc -->
	<div class="container"> <!-- -fluid -->
		<div class="row">
		    <div class="col-xs-12 col-md-6 pull-right">
			    <h1>مدیریت برنامه ها</h1>
		    </div>
	    </div>

<div class="row"> <!-- row for Users, Permissions, Pages, Email settings panels -->
	<hr>
	<?php
		//Cycle through plans
		$max_plans_num = 5;
		$i = 0;
		$plansData = array_reverse($plansData);

		foreach ($plansData as $v1) {
			$i++;
			if($i > $max_plans_num)
				break;
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
	<div class="col-md-12">
		<div class="panel-body">
			<strong style="font-size: 18px; padding-left: 10px;"><?=$v1->title?></strong>
			<span><?php if(strlen($v1->description) > 100) {
				echo mb_substr($v1->description, 0, 97)." ...";
				}
				else{
				 echo $v1->description;
				}?>	
			</span>
			<a class="btn btn-primary btn-xs pull-left" style="margin-right: 10px;" id="showRegistered<?=$v1->id?>" onclick='showRegistered(<?=$users?>, <?=$regiters?>, "<?=$v1->title?>", "<?=$v1->id?>")' ><span>مشاهده شرکت کنندگان</span></a>
			<a class="btn btn-warning btn-xs pull-left hidden" style="margin-right: 10px;" id="dontShowRegistered<?=$v1->id?>" onclick='dontShowRegistered(<?=$users?>, "<?=$v1->id?>")' ><span>مشاهده شرکت کنندگان</span></a>
			<a class="btn btn-info btn-xs pull-left" href="admin_plan.php?id=<?=$v1->id?>" target="blank"><span>مشاهده جزئیات</span></a>
		</div><!-- /panel body-->
	</div><!-- /col -->

	<?php } ?>

	<!-- Plan Panel -->
	<div class="col-md-12">
		<div class="panel-body">
			<a class="btn btn-success btn-xs pull-left" id="" href="admin_add_plan.php" target="blank"><span>افزودن برنامه جدید</span></a>
		</div><!-- /panel body-->
	</div><!-- /col -->

</div> <!-- /.row -->

	<div class="row hidden" id="plan_registered_data">
		<div class="col-xs-12 pull-right">
		<h2>شرکت کنندگان</h2>
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
        <button class='btn btn-info' id="btnExport" onclick="">خروجی اکسل</button><br><br>

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
