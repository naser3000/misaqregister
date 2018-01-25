<?php

if(file_exists("install/index.php")){
	//perform redirect if installer files exist
	//this if{} block may be deleted once installed
	header("Location: install/index.php");
}

require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/header.php';
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';
?>

<div id="page-wrapper">
<div class="container">
<div class="row">
	<div class="col-xs-12">
		<div class="jumbotron">
			<h1><?php echo $settings->site_name;?></h1>
			<p class="text-muted">کانون میثاق دانشگاه صنعتی شریف <?php //print_r($_SESSION);?></p>
			<p>
			<?php if($user->isLoggedIn()){$uid = $user->data()->id;?>
				<a class="btn btn-default" href="users/account.php" role="button">حساب کاربری &raquo;</a>
			<?php }else{?>
				<a class="btn btn-warning" href="users/login.php" role="button">ورود &raquo;</a>
				<a class="btn btn-info" href="users/join.php" role="button">ثبت نام &raquo;</a>
			<?php } ?>
			</p>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">آخرین برنامه ها</div>
	<div class="panel-body">
		<!-- Plan Panel -->
		<!-- Fetch information for all plans -->
		<?php $plansData = fetchAllPlansOrderByStartDate();
		foreach($plansData as $pld){
			if( str_replace("-", "/", $pld->plan_start_date) >= gregorian_to_jalali(explode('/', date("Y/m/d"))) )
			{
				?> 
				<div class="col-md-12 pull-right">
					<div class="panel panel-default">
						<div class="panel-body">
							<div><strong><?=$pld->title?></strong></div>
							<div><strong>شروع برنامه: <?php echo str_replace("-", "/", $pld->plan_start_date)?></strong></div>
							<div><span><?=$pld->description?></span></div>
							<a class="btn btn-info btn-xs" href="users/user_plan.php?id=<?=$pld->id?>"><span class="pull-left" >بیشتر</span></a>
						</div>
						<div class="clearfix"></div>
					</div><!-- /panel -->
				</div><!-- /col -->
				<?php 
			}
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
