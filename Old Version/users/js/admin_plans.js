
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

	     /*function fnExcelReport()
		  {
		      var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
		      var textRange; var j=0;
		      tab = document.getElementById('user_data_table'); // id of table

		      for(j = 0 ; j < tab.rows.length ; j++) 
		      {     
		          tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
		          //tab_text=tab_text+"</tr>";
		      }

		      tab_text=tab_text+"</table>";
		      tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
		      tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
		      tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

		      var ua = window.navigator.userAgent;
		      var msie = ua.indexOf("MSIE "); 

		      if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
		      {
		          txtArea1.document.open("txt/html","replace");
		          txtArea1.document.write(tab_text);
		          txtArea1.document.close();
		          txtArea1.focus(); 
		          sa=txtArea1.document.execCommand("SaveAs",true,"Say Thanks to Sumit.xls");
		      }  
		      else                 //other browser not tested on IE 11
		          sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  

		      return (sa);
		  }*/

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