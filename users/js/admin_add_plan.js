

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
                li[i].setAttribute('style', 'width: 20%;');
                li[i+1].setAttribute('style', 'width: 25%;');
                li[i+2].setAttribute('style', 'width: 30%;');
                li[i+3].setAttribute('style', 'width: 20%;');
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
        yinter.innerHTML = "بدون اهمیت";
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


    /////////////////////////////////////////////////////////////////////////////

function leap_gregorian(year)
{
    return ((year % 4) == 0) &&
            (!(((year % 100) == 0) && ((year % 400) != 0)));
}
var GREGORIAN_EPOCH = 1721425.5;
function gregorian_to_jd(year, month, day)
{
    return (GREGORIAN_EPOCH - 1) +
           (365 * (year - 1)) +
           Math.floor((year - 1) / 4) +
           (-Math.floor((year - 1) / 100)) +
           Math.floor((year - 1) / 400) +
           Math.floor((((367 * month) - 362) / 12) +
           ((month <= 2) ? 0 :
                               (leap_gregorian(year) ? -1 : -2)
           ) +
           day);
}
function jd_to_gregorian(jd) {
    var wjd, depoch, quadricent, dqc, cent, dcent, quad, dquad,
        yindex, dyindex, year, yearday, leapadj;

    wjd = Math.floor(jd - 0.5) + 0.5;
    depoch = wjd - GREGORIAN_EPOCH;
    quadricent = Math.floor(depoch / 146097);
    dqc = mod(depoch, 146097);
    cent = Math.floor(dqc / 36524);
    dcent = mod(dqc, 36524);
    quad = Math.floor(dcent / 1461);
    dquad = mod(dcent, 1461);
    yindex = Math.floor(dquad / 365);
    year = (quadricent * 400) + (cent * 100) + (quad * 4) + yindex;
    if (!((cent == 4) || (yindex == 4))) {
        year++;
    }
    yearday = wjd - gregorian_to_jd(year, 1, 1);
    leapadj = ((wjd < gregorian_to_jd(year, 3, 1)) ? 0
                                                  :
                  (leap_gregorian(year) ? 1 : 2)
              );
    month = Math.floor((((yearday + leapadj) * 12) + 373) / 367);
    day = (wjd - gregorian_to_jd(year, month, 1)) + 1;

    return new Array(year, month, day);
}

function leap_persian(year)
{
    return ((((((year - ((year > 0) ? 474 : 473)) % 2820) + 474) + 38) * 682) % 2816) < 682;
}
var PERSIAN_EPOCH = 1948320.5;
function persian_to_jd(year, month, day)
{
    var epbase, epyear;

    epbase = year - ((year >= 0) ? 474 : 473);
    epyear = 474 + mod(epbase, 2820);

    return day +
            ((month <= 7) ?
                ((month - 1) * 31) :
                (((month - 1) * 30) + 6)
            ) +
            Math.floor(((epyear * 682) - 110) / 2816) +
            (epyear - 1) * 365 +
            Math.floor(epbase / 2820) * 1029983 +
            (PERSIAN_EPOCH - 1);
}
function jd_to_persian(jd)
{
    var year, month, day, depoch, cycle, cyear, ycycle,
        aux1, aux2, yday;


    jd = Math.floor(jd) + 0.5;

    depoch = jd - persian_to_jd(475, 1, 1);
    cycle = Math.floor(depoch / 1029983);
    cyear = mod(depoch, 1029983);
    if (cyear == 1029982) {
        ycycle = 2820;
    } else {
        aux1 = Math.floor(cyear / 366);
        aux2 = mod(cyear, 366);
        ycycle = Math.floor(((2134 * aux1) + (2816 * aux2) + 2815) / 1028522) +
                    aux1 + 1;
    }
    year = ycycle + (2820 * cycle) + 474;
    if (year <= 0) {
        year--;
    }
    yday = (jd - persian_to_jd(year, 1, 1)) + 1;
    month = (yday <= 186) ? Math.ceil(yday / 31) : Math.ceil((yday - 6) / 30);
    day = (jd - persian_to_jd(year, month, 1)) + 1;
    return new Array(year, month, day);
}

    function jalali_to_gregorian(d) {
        var adjustDay = 0;
        if(d[1]<0){
            adjustDay = leap_persian(d[0]-1)? 30: 29;
            d[1]++;
        }
        var gregorian = jd_to_gregorian(persian_to_jd(d[0], d[1] + 1, d[2])-adjustDay);
        gregorian[1]--;
        return gregorian;
    }

    function gregorian_to_jalali(d) {
        var jalali = jd_to_persian(gregorian_to_jd(d[0], d[1] + 1, d[2]));
        jalali[1]--;
        return jalali;
    }