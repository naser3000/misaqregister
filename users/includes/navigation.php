<?php

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
		margin-left: 10px;
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
	input[type=checkbox] {
		padding-lef: 5px !important;
	}
	.capacity-row [class*='col-'], 
	.datetime-group [class*='col-'], 
	.spec-row [class*='col-'] { /* contains col-lg in class name */

  		float: right;
	}
	div.scrollmenu {
   		overflow: auto;
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
	table th, table td{
		text-align: center;
	}
}
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>






<script type="text/javascript">
    
		// disable or enable 'std_number' input according to status selections
		function disableInput(){
			var select = document.getElementById('status');
			var std_number = document.getElementById('std_number');
			var dorms = document.getElementById('dorms');
			var emp_number = document.getElementById('emp_number');
			if (select.value == 'دانشجو') {
				std_number.readOnly = false;
				dorms.disabled = '';
			}else{
				std_number.value = '';
				std_number.readOnly = true;
				dorms.value = '';
				dorms.disabled = 'disabled';
			}
			if (select.value == 'کارمند') {
				emp_number.readOnly = false;
			}else{
				emp_number.value = '';
				emp_number.readOnly = true;
			}
		}

    function changeStatusItems(){

			var btn_multiselect = document.querySelectorAll('button.multiselect');
			var spn_multiselect = document.querySelectorAll('span.multiselect-selected-text');
			var std_checkbox = document.querySelector('ul.multiselect-container input[value="دانشجو"]');

			if (std_checkbox.checked)
				btn_multiselect[1].disabled = false;
			else{
				btn_multiselect[1].title = 'انتخاب کنید';
				spn_multiselect[1].innerHTML = 'انتخاب کنید';
				btn_multiselect[1].disabled = true;
			}
		}

	function addCapacity(){

		var btn_multiselect = document.querySelectorAll('button.multiselect');
		var inp_multiselect = document.querySelectorAll('span.multiselect-selected-text');
		var table_rows = document.querySelectorAll('tbody tr input[type="radio"');
		var i = table_rows.length;
		var radio_name = "send_to_db" + i;
		var inp_gender = document.querySelector('select#gender');
		var inp_cost = document.querySelector('input#cost');
		var inp_participant_cost = document.querySelector('input#participant_cost');
		var inp_participant_number = document.querySelector('input#participant_number');
		var inp_capacity_number = document.querySelector('input#capacity_number');

		var trow = document.createElement('tr');
		var select_input = document.createElement('input');
		select_input.setAttribute('id', 'delete');
		select_input.setAttribute('type', 'checkbox');

		var send_data_input = document.createElement('input');
		send_data_input.setAttribute('name', radio_name);
		send_data_input.setAttribute('id', radio_name);
		send_data_input.setAttribute('type', 'radio');
		send_data_input.setAttribute('checked', 'checked');

		var hr = document.createElement('hr');
		hr.setAttribute('style', 'border: 0.1px solid gray;');
		
		var choice = document.createElement('td');
		var status = document.createElement('td');
		var yinter = document.createElement('td');
        var gender = document.createElement('td');
        var cost = document.createElement('td');
        var participant_cost = document.createElement('td');
        var participant_number = document.createElement('td');
        var capacity_number = document.createElement('td');
		var tbody = document.querySelector('tbody');

		tbody.appendChild(trow);
		trow.appendChild(choice);
		trow.appendChild(status);
		trow.appendChild(yinter);
		trow.appendChild(gender);
		trow.appendChild(cost);
		trow.appendChild(participant_cost);
		trow.appendChild(participant_number);
		trow.appendChild(capacity_number);
		choice.appendChild(select_input);
		trow.appendChild(send_data_input);
		//var status_input = document.createElement('input');
		//status_input.setAttribute('name', 'status1');
		//status_input.setAttribute('type', 'text');
		//status.appendChild(status_input);


        status.innerHTML = "همه موارد"
        yinter.innerHTML = "---------";
        cost.innerHTML = 0;
        participant_cost.innerHTML = 0;
		participant_number.innerHTML = 0;
		capacity_number.innerHTML = 0;

		if(inp_multiselect[0].innerHTML != "انتخاب کنید")
         	status.innerHTML = btn_multiselect[0].getAttribute('title')
        if(inp_multiselect[1].innerHTML != "انتخاب کنید")
         	yinter.innerHTML = btn_multiselect[1].getAttribute('title')
        if (true)
        gender.innerHTML = inp_gender.value;
        if (inp_cost.value != 0)
        	cost.innerHTML = inp_cost.value;
        if (inp_participant_cost.value != 0)
        	participant_cost.innerHTML = inp_participant_cost.value;
        if (inp_participant_number.value != 0)
        	participant_number.innerHTML = inp_participant_number.value;
        if (inp_capacity_number.value != 0)
        	capacity_number.innerHTML = inp_capacity_number.value;



        var data = "" + status.innerHTML
        			+ "|" + yinter.innerHTML
        			+ "|" + gender.innerHTML
        			+ "|" + cost.innerHTML
        			+ "|" + capacity_number.innerHTML
        			+ "|" + participant_number.innerHTML
        			+ "|" + participant_cost.innerHTML + "|";
		send_data_input.setAttribute('value', data);

	}

	function removeCapacity(){
		var i = 0;
		var j = 0;
		var trow = document.querySelectorAll('tbody tr input');
		var trow_checkbox = document.querySelectorAll('tbody tr input[type="checkbox"]');
		var span = document.querySelector('span#remove_capacity_message');

		for (i = 0; i < trow_checkbox.length; i++) {
			if (trow_checkbox[i].checked){
				trow_checkbox[i].parentNode.parentNode.remove();
				j++
			}
		}

		if(j == 0)
			span.innerHTML = "موردی انتخاب نشده است.";
		else
			span.innerHTML = "";
	}

	$(document).ready(function() {
		$('#status').multiselect({
        	includeSelectAllOption: false
    	});
    	//$('#status').remove();

    	$('#yinter').multiselect({
        	includeSelectAllOption: false
    	});
    	//$('#yinter').remove();

    	/*
    	$('#register_modal').on('show.bs.modal', function(e) {
    		var id = $(e.relatedTarget).data('id');
    		var spn_plan_id = document.querySelector('span#plan_id');
			$("input#plan_id").val(id);
			spn_plan_id.innerHTML = id;
			//$(".modal-header input#plan_id").val(id);
    		//$(".modal-content h2").innerHTML += id;
    		//alert(id);
  		});
  		*/


    	var yinter = document.querySelectorAll('button.multiselect');
    	if (yinter.length > 1)
    		yinter[1].disabled = true;


    	var ul = document.querySelectorAll('ul.multiselect-container');
    	if (ul.length > 1) {
    		ul[1].setAttribute('style', 'width: 470px;');
    		var li = ul[1].querySelectorAll('li');
    			for (var i = 0; i < li.length; i+=4) {
    				li[i].setAttribute('style', 'width: 28%;');
    				li[i].setAttribute('style', 'width: 30%;');
    				li[i].setAttribute('style', 'width: 27%;');
    				li[i].setAttribute('style', 'width: 13%;');
    			}
    	}


    });
    
</script>