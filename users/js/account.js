
//$("#register_modal1").modal({"backdrop": "static"});

	$(document).on('submit', '#register-plan-form', function(event){
		
		event.preventDefault();
		$form = $(this);
		var id = $(this).find('button.submit').attr('id');



		$.ajax({
			url: 'account.php',
			type: "POST",
			data: $(this).serialize(),
			success: function(data){

			  	console.log(id);
			  	console.log($('<div>').html(data).find('.modal-success-feedback' + id));
			    $feedback = $('<div>').html(data).find('.modal-error-feedback' + id);
			    $feedback2 = $('<div>').html(data).find('.modal-success-feedback' + id);
			    $('.modal-error-feedback' + id).remove();
			    $form.prepend($feedback[0]);

			    if ($feedback[0].childElementCount == 1) {
			    	console.log("|||||||||||||||||||||||||||||||||");
			    }
			    if ($feedback2[0].childElementCount == 1) {
			    	$('.modal-success-feedback' + id).remove();
			    	$('#edit_register_modal'+id+' .modal-body').prepend($feedback2[0]);
			    	$('#register_modal'+id).modal('toggle');
			    	$('#edit_register_modal'+id).modal('show');
			    }

				console.log($feedback[0].childElementCount);
				console.log($feedback[0]);
				console.log($feedback2[0].childElementCount);
				console.log($feedback2[0]);
				//console.log($form.prepend($feedback));
				//location.reload();
			},
			error: function(){
				alert('failure');
			}
		});

		console.log("+++++++++++++++++++++");
		
	});

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
	function remove_participant_register(prgs_id, id){
		
		$.ajax({
		 	url: 'account.php',
		 	type: "POST",
		 	data: {'prgs_id' : prgs_id, 'participant_number'  : id},
		 	success: function(){
		 		location.reload();
		 	},
		 	error: function(){
		 		alert('failure');
		 	}
		});
	}

	function add_participant(plan_id, account_charge, paid_cost) {

		if ($('table.plan'+plan_id+' tr.hidden').length != 0) {
			$('table.plan'+plan_id+' tr.hidden').find('input#participant_choise')[0].value = "1";
			$('table.plan'+plan_id+' tr.hidden')[0].classList.remove('hidden');
			changeOneParticipantCostToTotalCost(plan_id, +1, account_charge, paid_cost)
		}
		if ($('table.plan'+plan_id+' tr.hidden').length == 0)
			$('table.plan'+plan_id+' tr#add_participant').addClass('hidden');
	}

	function remove_participant(plan_id, i, account_charge, paid_cost) {
		var l1 = $('table.plan'+plan_id+' tr.hidden.dont-hidden').length;
		var l2 = $('table.plan'+plan_id+' tr.dont-hidden').length
		console.log(l1 + "jjj" + l2);
		console.log($('table.plan'+plan_id+' tr.dont-hidden'));
		$('table.plan'+plan_id+' tr.dont-hidden')[l2 - l1 - 1].classList.add('hidden');
		if(i == 1){
			console.log(i + "***");
			if(l2 > 1){
				$('table.plan'+plan_id+' #participant_name1')[0].value = $('table.plan'+plan_id+' #participant_name2')[0].value;
				$('table.plan'+plan_id+' #participant_code1')[0].value = $('table.plan'+plan_id+' #participant_code2')[0].value;
				$('table.plan'+plan_id+' #participant_gender1')[0].value = $('table.plan'+plan_id+' #participant_gender2')[0].value;
			}
			if (l2 > 2) {
				$('table.plan'+plan_id+' #participant_name2')[0].value = $('table.plan'+plan_id+' #participant_name3')[0].value;
				$('table.plan'+plan_id+' #participant_code2')[0].value = $('table.plan'+plan_id+' #participant_code3')[0].value;
				$('table.plan'+plan_id+' #participant_gender2')[0].value = $('table.plan'+plan_id+' #participant_gender3')[0].value;
			}
		}
		if(i == 2 & l2 > 2){
			$('table.plan'+plan_id+' #participant_name2')[0].value = $('table.plan'+plan_id+' #participant_name3')[0].value;
			$('table.plan'+plan_id+' #participant_code2')[0].value = $('table.plan'+plan_id+' #participant_code3')[0].value;
			$('table.plan'+plan_id+' #participant_gender2')[0].value = $('table.plan'+plan_id+' #participant_gender3')[0].value;
		}
		changeOneParticipantCostToTotalCost(plan_id, -1, account_charge, paid_cost);
		deleteText($('table.plan'+plan_id+' tr.dont-hidden')[l2 - 1]);
		if ($('table.plan'+plan_id+' tr.hidden').length > 0)
			$('table.plan'+plan_id+' tr#add_participant').removeClass('hidden');
	}


	function changeOneParticipantCostToTotalCost(plan_id, sign, account_charge, paid_cost) {
		var total_cost = document.querySelector('#register_modal'+plan_id+' #total_cost');
		var participant_cost = document.querySelector('#register_modal'+plan_id+' #participant_cost1');
		var i = total_cost.value.indexOf(':');
		var j = total_cost.value.indexOf('تومان');
		var total = parseInt(total_cost.value.substring(i+2, j-1));

		total += parseInt(participant_cost.value) * sign;

		total_cost.value = "مجموع هزینه ها: " + total +" تومان";
		if (total-paid_cost > account_charge) {
			$('#register_modal'+plan_id+' #charge_error').removeClass('hidden');
			if($('button.register-plan#'+plan_id)[0] != undefined)
				$('button.register-plan#'+plan_id)[0].disabled = true;
			if($('button.register-update#'+plan_id)[0] != undefined)
				$('button.register-update#'+plan_id)[0].disabled = true;
		}else{
			$('#register_modal'+plan_id+' #charge_error').addClass('hidden');
			if($('button.register-plan#'+plan_id)[0] != undefined)
				$('button.register-plan#'+plan_id)[0].disabled = false;
			if($('button.register-update#'+plan_id)[0] != undefined)
				$('button.register-update#'+plan_id)[0].disabled = false;
		}
	}
	/*function removeOneParticipantCostToTotalCost(plan_id) {
		
		var total_cost = document.querySelector('#register_modal'+plan_id+' #total_cost');
		var participant_cost = document.querySelector('#register_modal'+plan_id+' #participant_cost1');
		var i = total_cost.value.indexOf(':');
		var j = total_cost.value.indexOf('تومان');
		var total = parseInt(total_cost.value.substring(i+2, j-1));

		total -= parseInt(participant_cost.value);

		total_cost.value = "مجموع هزینه ها: " + total +" تومان";
	}*/
	function deleteText(el){
		var x = el.querySelectorAll('input');
		x[0].value = "";
		x[1].value = "";
		var y = el.querySelector('select');
		y.selectedIndex  = 0;
	}

	function passId(plan_id, prgs_id, id){
		var modal = document.querySelector('#delete_participant_register'+plan_id+' span');
		//modal.attr('onclick','').unbind('click');
		console.log(modal);
		console.log(modal.onclick);
		modal.onclick = function(event) {remove_participant_register(prgs_id, id)};
		console.log(modal);
	}