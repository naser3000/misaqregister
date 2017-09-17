<?php
/*
UserSpice 4
An Open Source PHP User Management System
by the UserSpice Team at http://UserSpice.com

Feb 02 2016 - Ported US3.2.1 top-nav

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

// Signup
$lang = array_merge($lang,array(
	"SIGNUP_TEXT"			=> "ثبت نام",
	"SIGNUP_BUTTONTEXT"		=> "مرا ثبت نام کن",
	"SIGNUP_AUDITTEXT"		=> "ثبت نام انجام شد",
	));

// Signin
$lang = array_merge($lang,array(
	"SIGNIN_FAIL"			=> "** ورود ناموفق بود **",
	"SIGNIN_TITLE"			=> "لطفاً وارد شوید",
	"SIGNIN_TEXT"			=> "ورود",
	"SIGNOUT_TEXT"			=> "خروج",
	"SIGNIN_BUTTONTEXT"		=> "ورود",
	"SIGNIN_AUDITTEXT"		=> "ورود انجام شد",
	"SIGNOUT_AUDITTEXT"		=> "خروج انجام شد",
	));

//Navigation
$lang = array_merge($lang,array(
	"NAVTOP_HELPTEXT"		=> "راهنما",
	));

$query = $db->query("SELECT * FROM email");
$results = $query->first();

//Value of email_act used to determine whether to display the Resend Verification link
$email_act=$results->email_act;

?>

<!-- Navigation -->
<div class="navbar navbar-fixed-top navbar-inverse" role="navigation">
	<div class="container" >
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header ">
			<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-top-menu-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="" href="<?=$us_url_root?>"><img class="img-responsive" src="<?=$us_url_root?>users/images/logo.png" alt="" /></a>
		</div>
		<div class="collapse navbar-collapse navbar-top-menu-collapse navbar-left" >
			<ul class="nav navbar-nav " >
				<?php if($user->isLoggedIn()){ //anyone is logged in?>
					<li><a href="<?=$us_url_root?>users/account.php"><i class="fa fa-fw fa-user"></i> <?php echo ucfirst($user->data()->username);?></a></li> <!-- Common for Hamburger and Regular menus link -->

					<?php if($settings->messaging == 1){ ?>
					<li><a href="<?=$us_url_root?>users/messages.php"><i class="fa fa-fw fa-envelope"></i><?=$msgC?> <?=$grammar?></a></li>
					<?php } ?>
					<li class="hidden-sm hidden-md hidden-lg"><a href="<?=$us_url_root?>"><i class="fa fa-fw fa-home"></i> خانه</a></li> <!-- Hamburger menu link -->
					<?php if (checkMenu(2,$user->data()->id)){  //Links for permission level 2 (default admin) ?>
						<li class="hidden-sm hidden-md hidden-lg"><a href="<?=$us_url_root?>users/admin.php"><i class="fa fa-fw fa-cogs"></i> داشبورد مدیر سایت</a></li> <!-- Hamburger menu link -->
					<?php } // is user an admin ?>
					<li class="dropdown hidden-xs"><a class="dropdown-toggle" href="#" data-toggle="dropdown"><i class="fa fa-fw fa-cog"></i>راهنما<b class="caret"></b></a> <!-- regular user menu -->
						<ul class="dropdown-menu"> <!-- open tag for User dropdown menu -->
							<li ><a href="<?=$us_url_root?>"><i class="fa fa-fw fa-home"></i> خانه</a></li> <!-- regular user menu link -->
							<li><a href="<?=$us_url_root?>users/account.php"><i class="fa fa-fw fa-user"></i> حساب کاربری</a></li>
						<?php if($settings->messaging == 1){ ?>
							<li><a href="<?=$us_url_root?>users/messages.php"><i class="fa fa-fw fa-envelope"></i><?=$msgC?> پیامها</a></li>
						<?php } ?>

									 <!-- regular user menu link -->

							<?php if (checkMenu(2,$user->data()->id)){  //Links for permission level 2 (default admin) ?>
								<li class="divider"></li>
								<li><a href="<?=$us_url_root?>users/admin.php"><i class="fa fa-fw fa-cogs"></i> داشبورد مدیر سایت</a></li> <!-- regular Admin menu link -->
							<?php } // is user an admin ?>
							<li class="divider"></li>
							<li><a href="<?=$us_url_root?>users/logout.php"><i class="fa fa-fw fa-sign-out"></i> خروج</a></li> <!-- regular Logout menu link -->
						</ul> <!-- close tag for User dropdown menu -->
					</li>

					<li class="hidden-sm hidden-md hidden-lg"><a href="<?=$us_url_root?>users/logout.php"><i class="fa fa-fw fa-sign-out"></i> خروج</a></li> <!-- regular Hamburger logout menu link -->

				<?php }else{ // no one is logged in so display default items ?>
					<li><a href="<?=$us_url_root?>users/login.php" class=""><i class="fa fa-sign-in"></i> ورود</a></li>
					<li><a href="<?=$us_url_root?>users/join.php" class=""><i class="fa fa-plus-square"></i> ثبت نام</a></li>
					<li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown"><i class="fa fa-life-ring"></i> راهنما <b class="caret"></b></a>
					<ul class="dropdown-menu">
					<li><a href="<?=$us_url_root?>users/forgot_password.php"><i class="fa fa-wrench"></i> فراموشی رمز عبور</a></li>
					<?php if ($email_act){ //Only display following menu item if activation is enabled ?>
					<li><a href="<?=$us_url_root?>users/verify_resend.php"><i class="fa fa-exclamation-triangle"></i> دوباره فرستادن فعالسازی ایمیل</a></li>
					<?php }?>
					</ul>
					</li>
				<?php } //end of conditional for menu display ?>
			</ul> <!-- End of UL for navigation link list -->
		</div> <!-- End of Div for right side navigation list -->

	<?php require_once $abs_us_root.$us_url_root.'usersc/includes/navigation.php';?>

	</div> <!-- End of Div for navigation bar -->
</div> <!-- End of Div for navigation bar styling -->


<style type="text/css">
@font-face {
    font-family: "IRANSans";
    font-weight: 300;
	src: url("../users/css/fonts/IRANSans.eot") format("eot"),
url("../users/css/fonts/IRANSans.ttf") format("ttf"),
url("../users/css/fonts/IRANSans.woff") format("woff");
}
	.navbar-header{
		float: right;
	}
	.container{
		font-family: 'IRANSans';
		direction: rtl;
	}
	.container i{
		margin-left: 3px;
	}
	.navbar-left li{
		float: right !important;
	}
	h1, h2, h3{
		font-family: 'IRANSans';
	}
	th, td, li{
		text-align: right;
	}

	[class*='col-'] { /* contains col-lg in class name */

  		float: righ;
  		clear: righ;
	}


	.input-group {
		direction: ltr !important;
	}
	.input-group .form-control{
		direction: rtl;
	}

	.input-group-addon {
		background-color: rgb(92, 184, 92);
	}
	.col-lg-4 .input-group-addon{
		background-color: rgb(180, 230, 180);
	}
	.capacity {
		margin-bottom: 10px;
	}
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="http://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/a549aa8780dbda16f6cff545aeabc3d71073911e/src/js/bootstrap-datetimepicker.js"></script>

<script type="text/javascript">
    $(function () {
        $('#datepicker, #datepicker, #datepicker #datepicker, #datepicker').datetimepicker({
            format: 'YYYY/MM/DD',
            locale: 'fa',
        });
        $('#timepicker, #timepicker, #timepicker, #timepicker, #timepicker').datetimepicker({
            format : 'HH:mm',
        });
    });

    function changeStatusItems(){
			var input = document.getElementById('yinter');
			var select = document.getElementById('status');
			if (select.value == 'دانشجو') {
				input.disabled = '';
			}else{
				input.disabled = 'disabled';
			}
		}
</script>