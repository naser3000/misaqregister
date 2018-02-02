$(document).ready(function(){
	console.log("**********");
});


function cancel_plan(id){
	console.log("**********");
	console.log("id=" + id);
	$.ajax({
	 	url: 'admin_plan.php',
	 	type: 'POST',
	 	data: {'plan_id' : id},
	 	success: function(data, f, k){
	 		console.log("----------");
	 		// console.log(data);
	 		// location.reload();
	 		// console.log(data);
	 		console.log(f);
	 		console.log(k);
	 	},
	 	error: function(vvv , e){
	 		console.log(vvv);
	 		alert(vvv.responseText);
	 	}
	});
}


	function remove_user_register(prgs_id){
		
		$.ajax({
		 	url: 'account.php',
		 	type: "POST",
		 	data: {'prgs_id' : prgs_id},
		 	success: function(){
		 		location.reload();
		 	},
		 	error: function(){
		 		alert('failure');
		 	}
		});
	}