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

?>
<div id="page-wrapper"> <!-- leave in place for full-screen backgrounds etc -->
	<div class="container"> <!-- -fluid -->


		<div class="row">
		    <div class="col-xs-12 col-md-6 pull-right">
			    <h1>مدیریت برنامه ها</h1>
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
        <div class="col-xs-12">
			<form name="adminUsers" action="" method="post">
				<div class="allutable table-responsive" id="plan_registered_data">
				<table class='table table-bordered' id="plan_registered_data">
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
<!-- Place any per-page javascript here -->
<script type="text/javascript">
	$(document).ready(function(){
		/*$("#times").load("times.php" );

		var timesRefresh = setInterval(function(){
			$("#times").load("times.php" );
		}, 30000);


  		$('[data-toggle="tooltip"]').tooltip();
		$('[data-toggle="popover"]').popover();
	*/
// -------------------------------------------------------------------------
		$("#btnExport").click(function(e) {
			e.preventDefault();

			//getting data from our table
			var data_type = 'data:application/vnd.ms-excel';
			var table_div = document.getElementById('plan_registered_data');
			var table_html = table_div.outerHTML.replace(/ /g, '%20');

			var a = document.createElement('a');
			a.href = data_type + ', ' + table_html;
			a.download = 'exported_table_' + Math.floor((Math.random() * 9999999) + 1000000) + '.xls';
			a.click();
	    });

	});

	function showRegistered(users, regiters, plan_title, plan_id){
		var body = document.querySelector('tbody');
		$('a#showRegistered'+plan_id).addClass('hidden');
		$('a#dontShowRegistered'+plan_id).removeClass('hidden');
		$('#plan_registered_data').removeClass('hidden');

		// add header of plan column
		var head_row = document.querySelector('thead tr');
		title = plan_title + "  " + plan_id;
		head_row.innerHTML += "<th id='plan"+plan_id+"'>"+title+"</th>";

		// add a cell to end of each exist rows (if exist!)
		var rows = body.querySelectorAll('tr');
		for (j in rows){
			rows[j].innerHTML +="<td></td>";
		}

		// add new user rows
		for (i in users){
			u = users[i];
			r = regiters[i];
			

			td = "";
			for (j=7; j<head_row.children.length-1; j++){
				td +="<td></td>";
			}
			td +="<td>*</td>";

			// check user existence			
			per_u = body.querySelector('tr#user'+u.id);
			if (per_u == null){
				// user dont exist
				var u_row = '<tr class="bg-default" id="user'+u.id+'"><td>'+u.fname+' '+u.lname+'</td><td>'+u.icode+'</td><td>'+u.gender+'</td><td>'+u.status+'</td><td>'+u.std_number+'</td><td>'+u.phnumber+'</td><td>'+u.email+'</td>'+td+'</tr>';
				body.innerHTML += u_row;

				if (r.participant_name1 != ""){
					var p1_row = '<tr id="part'+u.id+'"><td>'+r.participant_name1+'</td><td>'+r.participant_code1+'</td><td>'+r.participant_gender1+'</td><td>'+'همراه'+'</td><td></td><td></td><td></td>'+td+'</tr>';
					body.innerHTML += p1_row;
				}
				if (r.participant_name2 != ""){
					var p2_row = '<tr id="part'+u.id+'"><td>'+r.participant_name2+'</td><td>'+r.participant_code2+'</td><td>'+r.participant_gender2+'</td><td>'+'همراه'+'</td><td></td><td></td><td></td>'+td+'</tr>';
					body.innerHTML += p2_row;
				}
				if (r.participant_name3 != ""){
					body.innerHTML += p3_row;
					var p3_row = '<tr id="part'+u.id+'"><td>'+r.participant_name3+'</td><td>'+r.participant_code3+'</td><td>'+r.participant_gender3+'</td><td>'+'همراه'+'</td><td></td><td></td><td></td>'+td+'</tr>';
				}
				
			}else{
				// user exist
				per_u.lastChild.innerHTML = "*";
				
				
				if (r.participant_name1 != ""){
					var per_p = body.querySelectorAll('tr#part'+u.id);
					var p1_row = '<td>'+r.participant_name1+'</td><td>'+r.participant_code1+'</td><td>'+r.participant_gender1+'</td><td>'+'همراه'+'</td>';
					for(j=0; j<per_p.length; j++){
						if (per_p[j].innerHTML.includes(p1_row)){
							per_p[j].lastChild.innerHTML = "*";
							break;
						}
						if(j == per_p.length-1){
							//p1_row = '<tr  id="part'+u.id+'">'+p1_row+'<td></td><td></td><td></td>'+td+'</tr>';
							//body.innerHTML += p1_row;							
							var prt1 = document.createElement('tr');
							prt1.setAttribute('id', 'part'+u.id);
							prt1.innerHTML = p1_row+'<td></td><td></td><td></td>'+td;
							per_p[j].after(prt1);
						}
					}
				}
				if (r.participant_name2 != ""){
					var per_p = body.querySelectorAll('tr#part'+u.id);
					var p2_row = '<td>'+r.participant_name2+'</td><td>'+r.participant_code2+'</td><td>'+r.participant_gender2+'</td><td>'+'همراه'+'</td>';
					for(j=0; j<per_p.length; j++){
						if (per_p[j].innerHTML.includes(p2_row)){
							per_p[j].lastChild.innerHTML = "*";
							break;
						}
						if(j == per_p.length-1){
							//p2_row = '<tr  id="part'+u.id+'">'+p2_row+'<td></td><td></td><td></td>'+td+'</tr>';
							//body.innerHTML += p2_row;							
							var prt2 = document.createElement('tr');
							prt2.setAttribute('id', 'part'+u.id );
							prt2.innerHTML = p2_row+'<td></td><td></td><td></td>'+td;
							per_p[j].after(prt2);
						}
					}
				}
				if (r.participant_name3 != ""){
					var per_p = body.querySelectorAll('tr#part'+u.id);
					var p3_row = '<td>'+r.participant_name3+'</td><td>'+r.participant_code3+'</td><td>'+r.participant_gender3+'</td><td>'+'همراه'+'</td>';
					for(j=0; j<per_p.length; j++){
						if (per_p[j].innerHTML.includes(p3_row)){
							per_p[j].lastChild.innerHTML = "*";
							break;
						}
						if(j == per_p.length-1){
							//p3_row = '<tr  id="part'+u.id+'">'+p3_row+'<td></td><td></td><td></td>'+td+'</tr>';
							//body.innerHTML += p3_row;
							var prt3 = document.createElement('tr');
							prt3.setAttribute('id', 'part'+u.id );
							prt3.innerHTML = p3_row+'<td></td><td></td><td></td>'+td;
							per_p[j].after(prt3);
						}
					}
				}

			}
		}// end of user loop
	}
	function dontShowRegistered(users, plan_id){
		// change buttons showing
		$('a#showRegistered'+plan_id).removeClass('hidden');
		$('a#dontShowRegistered'+plan_id).addClass('hidden');
		
		// delete column of plan
		var index = document.querySelector('table tr th#plan'+plan_id).cellIndex;
		var selector = 'td:eq('+index+'), th#plan'+plan_id;
		$('table tr').find(selector).remove();

		// delete rows that dont have any *
		var table = document.querySelector('table');
		for (var i = 0; i < users.length; i++) {
			var id = users[i].id;
			var rows = table.querySelectorAll('tr#user'+id+', tr#part'+id);

			for (var j = 0; j < rows.length; j++) {
				if (!rows[j].innerHTML.includes('*'))
					rows[j].remove();
			}
		}

		// hidde table if dont exist rows
		rows = table.querySelectorAll('tbody tr');
		if (rows.length == 0)
			$('#plan_registered_data').addClass('hidden');
		
	}
</script>

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
