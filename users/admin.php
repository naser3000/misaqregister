<?php require_once 'init.php'; ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/header.php'; ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; ?>

<?php if (!securePage($_SERVER['PHP_SELF'])){die();} ?>
<?php
$pagePermissions = fetchPagePermissions(4);


// To make this panel super admin only, uncomment out the lines below
// if($user->data()->id !='1'){
//   Redirect::to('account.php');
// }

//PHP Goes Here!
delete_user_online(); //Deletes sessions older than 24 hours

//Find users who have logged in in X amount of time.
$date = date("Y-m-d H:i:s");

$hour = date("Y-m-d H:i:s", strtotime("-1 hour", strtotime($date)));
$today = date("Y-m-d H:i:s", strtotime("-1 day", strtotime($date)));
$week = date("Y-m-d H:i:s", strtotime("-1 week", strtotime($date)));
$month = date("Y-m-d H:i:s", strtotime("-1 month", strtotime($date)));

$last24=time()-86400;

$recentUsersQ = $db->query("SELECT * FROM users_online WHERE timestamp > ? ORDER BY timestamp DESC",array($last24));
$recentUsersCount = $recentUsersQ->count();
$recentUsers = $recentUsersQ->results();

$usersHourQ = $db->query("SELECT * FROM users WHERE last_login > ?",array($hour));
$usersHour = $usersHourQ->results();
$hourCount = $usersHourQ->count();

$usersTodayQ = $db->query("SELECT * FROM users WHERE last_login > ?",array($today));
$dayCount = $usersTodayQ->count();
$usersDay = $usersTodayQ->results();

$usersWeekQ = $db->query("SELECT username FROM users WHERE last_login > ?",array($week));
$weekCount = $usersWeekQ->count();

$usersMonthQ = $db->query("SELECT username FROM users WHERE last_login > ?",array($month));
$monthCount = $usersMonthQ->count();

$usersQ = $db->query("SELECT * FROM users");
$user_count = $usersQ->count();

$pagesQ = $db->query("SELECT * FROM plans");
$plan_count = $pagesQ->count();

$settingsQ = $db->query("SELECT * FROM settings");
$settings = $settingsQ->first();


?>
<div id="page-wrapper"> <!-- leave in place for full-screen backgrounds etc -->
<div class="container"> <!-- -fluid -->

<h1 class="text-center">داشبورد مدیریت سایت</h1>
<hr>

<div class="row"> <!-- row for Users, Email settings panels , plans-->
	<h2>میز مدیریت</h2>
	<!-- Users Panel -->
	<div class="col-xs-6 col-md-2 pull-right">
		<div class="panel panel-default">
			<div class="panel-heading"><strong>کاربران</strong></div>
			<div class="panel-body text-center"><div class="huge"> <i class='fa fa-user fa-1x'></i> <?=$user_count?></div></div>
			<div class="panel-footer">
				<span class="pull-left"><a href="admin_users.php">مدیریت</a></span>
				<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
				<div class="clearfix"></div>
			</div> <!-- /panel-footer -->
		</div><!-- /panel -->
	</div><!-- /col -->

	<!-- Planes Settings Panel -->
	<div class="col-xs-6 col-md-2 pull-right">
		<div class="panel panel-default">
			<div class="panel-heading"><strong>برنامه ها</strong></div>
			<div class="panel-body text-center"><div class="huge"> <i class='fa fa-suitcase fa-1x'></i> <?=$plan_count?> </div></div>
			<div class="panel-footer">
				<span class="pull-left"><a href='admin_plans.php'>مدیریت</a></span>
				<span class="pull-right"><i class='fa fa-arrow-circle-right'></i></span>
				<div class="clearfix"></div>
			</div> <!-- /panel-footer -->
		</div> <!-- /panel -->
	</div> <!-- /col -->

</div> <!-- /.row -->
<hr>

<div class="row "> <!-- rows for Info Panels -->
	<h2>میز اطلاعات</h2>
	<div class="col-xs-12 col-md-6">
		<div class="panel panel-default">
		<div class="panel-heading"><strong>همه کاربران</strong> <span class="small">(کسانی که وارد شده اند)</span></div>
		<div class="panel-body text-center">
		<div class="row">
			<div class="col-xs-3 "><h3><?=$hourCount?></h3><p>در ساعت</p></div>
			<div class="col-xs-3"><h3><?=$dayCount?></h3><p>در روز</p></div>
			<div class="col-xs-3 "><h3><?=$weekCount?></h3><p>در هفته</p></div>
			<div class="col-xs-3 "><h3><?=$monthCount?></h3><p>در ماه</p></div>
		</div>
		</div>
		</div><!--/panel-->


		<div class="panel panel-default">
		<div class="panel-heading"><strong>همه بیننده ها</strong> <span class="small">(وارد شده باشند یا نه)</span></div>
		<div class="panel-body">
		<?php  if($settings->track_guest == 1){ ?>
		<?="تعداد بیننده های یکتا، در 30 دقیقه آخر، ".count_users() ." بوده است."."<br>";?>
		<?php }else{ ?>
		Guest tracking off. Turn "Track Guests" on below for advanced tracking statistics.
		<?php } ?>
		</div>
		</div><!--/panel-->
	</div> <!-- /col -->

	<div class="col-xs-12 col-md-6">
	<div class="panel panel-default">
	<div class="panel-heading"><strong>کاربران وارد شده</strong> <span class="small">(24 ساعت گذشته)</span></div>
	<div class="panel-body">
	<div class="uvistable table-responsive">
	<table class="table">
	<?php if($settings->track_guest == 1){ ?>
	<thead><tr><th>نام کاربری</th><th>IP</th><th>آخرین فعالیت</th></tr></thead>
	<tbody>

	<?php foreach($recentUsers as $v1){
		$user_id=$v1->user_id;
		$username=name_from_id($v1->user_id);
		$timestamp=date("Y-m-d H:i:s",$v1->timestamp);
		$ip=$v1->ip;

		if ($user_id==0){
			$username="guest";
		}

		if ($user_id==0){?>
			<tr><td><?=$username?></td><td><?=$ip?></td><td><?=$timestamp?></td></tr>
		<?php }else{ ?>
			<tr><td><a href="admin_user.php?id=<?=$user_id?>"><?=$username?></a></td><td><?=$ip?></td><td><?=$timestamp?></td></tr>
		<?php } ?>

	<?php } ?>

	</tbody>
	<?php }else{echo 'Guest tracking off. Turn "Track Guests" on below for advanced tracking statistics.';} ?>
	</table>
	</div>
	</div>
	</div><!--/panel-->

	</div> <!-- /col2/2 -->
</div> <!-- /row -->

</div> <!-- /row -->







</div> <!-- /container -->
</div> <!-- /#page-wrapper -->

<!-- footers -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

<!-- Place any per-page javascript here -->
	<script type="text/javascript">
	$(document).ready(function(){

	$("#times").load("times.php" );

	var timesRefresh = setInterval(function(){
	$("#times").load("times.php" );
	}, 30000);


  $('[data-toggle="tooltip"]').tooltip();
	$('[data-toggle="popover"]').popover();
// -------------------------------------------------------------------------
		});
	</script>

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
