<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Кошелёк</title>
<link id="css" rel="stylesheet" href="css.php">
</head><body>
<script>
function loadScript(url, callback){
	var script = document.createElement("script")
	script.onload = function(){callback();}
	script.src = url;
	script.id = url;
	document.getElementsByTagName("head")[0].appendChild(script);
}

function ajaxsend(data, elem) {
	var xhr = new XMLHttpRequest();
	xhr.open("POST", "db.php", true);
	if (typeof(data) == 'string') xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.onreadystatechange = function() {
		if ((xhr.readyState == 4) && (xhr.status == 200)) {
			elem.innerHTML = xhr.responseText;
			var js = document.getElementById("chartjs");
			if (js) {
				var ch = "Chart.min.js";
				if (document.getElementById(ch)) eval(js.innerHTML);
				else loadScript(ch, function(){eval(js.innerHTML);});
			}
			var els = elem.querySelectorAll("input[type=text], input[type=number]");
			if (els.length > 0) {
				el = els[0];
				el.focus();
				el.select();
			}
		}
	}
	xhr.send(data);
}

function load() {
	var data = "load=1";
	ajaxsend(data, document.body);
}

function login() {
	var data = "login=1";
	var el_1 = document.getElementById("username");
	if (el_1) data += "&username=" + encodeURIComponent(el_1.value)
	+ "&password=" + encodeURIComponent(document.getElementById("password").value)
	+ "&remember=" + document.getElementById("remember").checked;
	ajaxsend(data, document.body);
}

function logout() {
	var data = "logout=1";
	ajaxsend(data, document.body);
}

function ch_css(id) {
	document.getElementById('css').href='css.php?CSSID='+id;
}

function id_close(id) {
	var sect = document.getElementById(id);
	if (sect) sect.parentNode.removeChild(sect);
}

function get_new_sect(id) {
	id_close(id);
	var sect = document.createElement('section');
	sect.id = id;
	document.getElementsByTagName('main')[0].appendChild(sect);
	return sect;
}

function get_inputs(e, s) {
	var data = '';
	var elem = e.querySelectorAll(s);
	var imax = elem.length;
	for (var i = 0; i < imax; i++) {
		el = elem[i];
		data += "&" + el.id + "=" + encodeURIComponent(el.value);
	}
	return data;
}

//get content
function get_form(form_id, id, s) {
	var data = "frm=" + form_id;
	if (id != null) data += "&id=" + id + "&tbl=" + s;
	ajaxsend(data, get_new_sect(form_id));
}

//money
function money_table(fltr, id, s) {
	var data = "frm=money_table";
	if (fltr == 3) {
		data += "&f=3&f_servs_id=" + id + s;
	} else if (fltr == 4) {
		data += "&f=3&f_grups_id=" + id + s;
	} else if (fltr == 5) {
		data += "&f=5&f_servs_id=" + id + s;
		var el_2 = document.getElementById("report");
		if (el_2) data += get_inputs(el_2, "input[type=date]");
	} else if (fltr == 6) {
		data += "&f=5&f_grups_id=" + id + s;
		var el_2 = document.getElementById("report");
		if (el_2) data += get_inputs(el_2, "input[type=date]");
	} else if (fltr >= 0) {
		var el_1 = document.getElementById("o");
		if (fltr == 0) if (el_1) el_1.value = id;
		var o = el_1 ? el_1.value : 1;
		data += "&o=" + o;
		var el_2 = document.getElementById("money_table");
		if (el_2) data += get_inputs(el_2, "input[type=date], select");
	}
	ajaxsend(data, get_new_sect('money_table'));
}

//save changes to db
function edit_to_db(tbl) {
	var data = "frm=edit_to_db&tbl=" + encodeURIComponent(tbl);
	var efrm = document.getElementById("edit_form");
	data += get_inputs(efrm, "input[type=hidden], input[type=text], input[type=date], input[type=number], input[type=password], select");
	if (tbl == "money") {
		var sect = document.getElementById("money_table");
		data += get_inputs(sect, "input[type=date], select");
	} else var sect = document.getElementById("edit_table");
	id_close("edit_form");
	ajaxsend(data, sect);
}

//import from bankstate
function import_form2() {
	var w_id = document.getElementById("w_id").value;
	var i_fn = document.getElementById("bankstate");
	if (i_fn.files.length === 0) return;
	var fdat = new FormData();
	fdat.append('w_id', w_id);
	fdat.append('bankstate', i_fn.files[0]);
	fdat.append('frm', 'import_form2');
	ajaxsend(fdat, document.getElementById("import_form2"));
}

//save bankstate to db
function import_to_db() {
	var data = "frm=import_to_db";
	var elem = document.querySelectorAll("#import_form2 input:checked");
	var imax = elem.length;
	for (var i = 0; i < imax; i++) data += "&imp_" + i + "=" + encodeURIComponent(elem[i].value);
	ajaxsend(data, document.getElementById("import_form2"));
}

//get report
function get_report(form_id) {
	var data = "frm=" + form_id;
	var el_1 = document.getElementById("p_date_from");
	var el_2 = document.getElementById("p_date_to");
	if (el_1) data += "&from=" + encodeURIComponent(el_1.value);
	if (el_2) data += "&to=" + encodeURIComponent(el_2.value);
	ajaxsend(data, get_new_sect('report'));
}

window.onload = load();
</script>
</body></html>
