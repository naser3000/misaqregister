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

</div> <!-- /container -->

</div> <!-- /#page-wrapper -->

<!-- footers -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

<!-- Place any per-page javascript here -->


<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
