

$(document).ready(function() {

    $("#register_start_date, #register_end_date, #confirm_end_date, #plan_start_date, #plan_end_date").datepicker();
    $("#register_start_date_btn, #register_end_date_btn, #confirm_end_date_btn, #plan_start_date_btn, #plan_end_date_btn").click(function(event) {
        event.preventDefault();
        $("#"+this.id.replace("_btn","")).focus();
    })


    $('#register_start_time, #register_end_time, #confirm_end_time, #plan_start_time, #plan_end_time').datetimepicker({
        format : 'HH:mm',
    });
    $("#register_start_time_btn, #register_end_time_btn, #confirm_end_time_btn, #plan_start_time_btn, #plan_end_time_btn").click(function(event) {
        event.preventDefault();
        $("#"+this.id.replace("_btn","")).focus();
    })

    $('#status').multiselect({
        includeSelectAllOption: false
    });
    //$('#status').remove();

    $('#yinter').multiselect({
        includeSelectAllOption: false
    });

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