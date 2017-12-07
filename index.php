<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Кошелёк</title>
<link id="css" rel="stylesheet" href="css.php">
<script>
function ajaxsend(data) {
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'db.php', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {
		if ((xhr.readyState == 4) && (xhr.status == 200)) {
			document.body.innerHTML = xhr.responseText;
		}
	}
	xhr.send(data);
}

function load() {
	var data = "load=1";
	ajaxsend(data);
}

function login() {
	var data = "login=1";
	var el_1 = document.getElementById("username");
	if (el_1) {
		data += "&username=" + encodeURIComponent(el_1.value);
		data += "&password=" + encodeURIComponent(document.getElementById("password").value);
		data += "&remember=" + document.getElementById("remember").checked;
	}
	ajaxsend(data);
}

function logout() {
	var data = "logout=1";
	ajaxsend(data);
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

//get content
function get_form(form_id, id, s) {
	var data = "frm=" + form_id;
	if (id != null) data += "&id=" + id + "&tbl=" + s;
	var sect = get_new_sect(form_id);
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'db.php', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {
		if ((xhr.readyState == 4) && (xhr.status == 200)) {
			sect.innerHTML = xhr.responseText;
			var el = document.getElementById("e_name");
			if (el == null) var el = document.getElementById("e_op_summ");
			if (el == null) var el = sect.getElementsByTagName("input")[1];
			if (el != null) {
				el.focus();
				el.select();
			}
		}
	}
	xhr.send(data);
}

//money
function money_table(fltr, id, s) {
	var el_1 = document.getElementById("o");
	if (fltr == 0) {fltr = 1; if (el_1) el_1.value = id;}
	if (el_1) var o = el_1.value; else var o = 1;
	var data = "frm=money_table&o=" + o;
	if (fltr === 1) {
		data += "&f=1";
		var elem = document.querySelectorAll("#money_table input[type=date], #money_table select");
		for (var i = 0; i < elem.length; i++) data += "&" + elem[i].id + "=" + encodeURIComponent(elem[i].value);
	} else if (fltr == 2) {
		data += "&f=2&f_groups_id=" + id + "&mo=" + s;
	} else if ([3,5].includes(fltr)) {
		data += "&f=3&f_goods_id=" + id;
	} else if (fltr == 4) {
		data += "&f=3&f_groups_id=" + id;
	} else if (fltr == 6) {
		data += "&f=3&f_groups_id=" + id + "&f_users_id=" + s;
	} else {
		data += "&f=1";
	}
	var sect = get_new_sect('money_table');
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'db.php', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {
		if ((xhr.readyState == 4) && (xhr.status == 200)) {
			sect.innerHTML = xhr.responseText;
		}
	}
	xhr.send(data);
}

//save changes to db
function edit_to_db(tbl) {
	var data = "frm=edit_to_db&tbl=" + encodeURIComponent(tbl);
	var elem = document.querySelectorAll("#edit_form input[type=hidden], #edit_form input[type=text], #edit_form input[type=date], #edit_form input[type=number], #edit_form input[type=password], #edit_form select");
	for (var i = 0; i < elem.length; i++) data += "&" + elem[i].id + "=" + encodeURIComponent(elem[i].value);
	if (tbl == "money") {
		var elem = document.querySelectorAll("#money_table input[type=date], #money_table select");
		for (var i = 0; i < elem.length; i++) data += "&" + elem[i].id + "=" + encodeURIComponent(elem[i].value);
	}
	id_close("edit_form");
	var xhr = new XMLHttpRequest();
	xhr.open("POST", "db.php", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.onreadystatechange = function() {
		if ((xhr.readyState == 4) && (xhr.status == 200)) {
			if (tbl == "money") document.getElementById("money_table").innerHTML = xhr.responseText;
			else document.getElementById("edit_table").innerHTML = xhr.responseText;
		}
	}
	xhr.send(data);
}

//import from bankstate
function import_form2() {
	var i_id = document.getElementById("i_id").value;
	var i_fn = document.getElementById("bankstate");
	if (i_fn.files.length === 0) {return;}
	var fdat = new FormData();
	fdat.append('i_id', i_id);
	fdat.append('bankstate', i_fn.files[0]);
	fdat.append('frm', 'import_form2');
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'db.php', true);
	xhr.onreadystatechange = function() {
		if ((xhr.readyState == 4) && (xhr.status == 200)) {
			document.getElementById("import_form2").innerHTML = xhr.responseText;
		}
	}
	xhr.send(fdat);
}

//save bankstate to db
function import_to_db() {
	var data = "frm=import_to_db";
	var elem = document.querySelectorAll("#import_form2 input:checked");
	for (var i = 0; i < elem.length; i++)
		data += "&imp_" + i + "=" + encodeURIComponent(elem[i].value);
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'db.php', true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.onreadystatechange = function() {
		if ((xhr.readyState == 4) && (xhr.status == 200)) {
			document.getElementById("import_form2").innerHTML = xhr.responseText;
		}
	}
	xhr.send(data);
}

//get report
function get_report(form_id) {
	var data = "frm=" + form_id;
	var el_1 = document.getElementById("p_date_from");
	var el_2 = document.getElementById("p_date_to");
	if (el_1) data += "&from=" + encodeURIComponent(el_1.value);
	if (el_2) data += "&to=" + encodeURIComponent(el_2.value);
	var sect = get_new_sect('report');
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'db.php', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {
		if ((xhr.readyState == 4) && (xhr.status == 200)) {
			sect.innerHTML = xhr.responseText;
		}
	}
	xhr.send(data);
}

window.onload = load();
</script>
</head><body>
</body></html>
