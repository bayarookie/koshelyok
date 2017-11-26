<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Кошелёк</title>
<link id="css" rel="stylesheet" href="css.php">
<script type="text/javascript">
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
	if (el_1 != null) data += "&username=" + encodeURIComponent(el_1.value);
	var el_2 = document.getElementById("password");
	if (el_2 != null) data += "&password=" + encodeURIComponent(el_2.value);
	var el_3 = document.getElementById("remember");
	if (el_3 != null) data += "&remember=" + encodeURIComponent(el_3.checked);
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
	var iDiv = document.getElementById(id);
	if (iDiv) {iDiv.parentNode.removeChild(iDiv);}
}

function get_new_div(id) {
	id_close(id);
	var iDiv = document.createElement('div');
	iDiv.id = id;
	iDiv.className = 'hide';
	document.getElementsByTagName('section')[0].appendChild(iDiv);
	return iDiv;
}

//get content
function get_form(form_id, id, s) {
	if (id == null) {
		var data = "frm=" + encodeURIComponent(form_id);
	} else {
		var data = "frm=" + encodeURIComponent(form_id) + "&id=" + encodeURIComponent(id) + "&tbl=" + encodeURIComponent(s);
	}
	var iDiv = get_new_div(form_id);
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'db.php', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {
		if ((xhr.readyState == 4) && (xhr.status == 200)) {
			iDiv.innerHTML = xhr.responseText;
			var el = document.getElementById("e_name");
			if (el == null) var el = document.getElementById("e_op_summ");
			if (el == null) var el = iDiv.getElementsByTagName("input")[1];
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
	var el_1 = document.getElementById("ordr");
	if (el_1 == null) {
		var ordr = 1;
	} else {
		var ordr = el_1.value;
	}
	if (fltr == 4) {
		fltr = 1;
		ordr = id;
	}
	if (fltr === 1) {
		var data = "f=1"
		+ "&from=" + encodeURIComponent(document.getElementById("date_from").value)
		+ "&to=" + encodeURIComponent(document.getElementById("date_to").value)
		+ "&f_goods_id=" + encodeURIComponent(document.getElementById("f_goods_id").value)
		+ "&f_groups_id=" + encodeURIComponent(document.getElementById("f_groups_id").value)
		+ "&f_walls_id=" + encodeURIComponent(document.getElementById("f_walls_id").value)
		+ "&o=" + encodeURIComponent(ordr)
		+ "&frm=money_table";
	} else if (fltr == 2) {
		var data = "f=2"
		+ "&f_groups_id=" + encodeURIComponent(id)
		+ "&mo=" + encodeURIComponent(s)
		+ "&o=" + encodeURIComponent(ordr)
		+ "&frm=money_table";
	} else if (fltr == 3) {
		var data = "f=3"
		+ "&f_goods_id=" + encodeURIComponent(id)
		+ "&o=" + encodeURIComponent(ordr)
		+ "&frm=money_table";
	} else {
		var data = "f=1&frm=money_table";
	}
	var iDiv = get_new_div('money_table');
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'db.php', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {
		if ((xhr.readyState == 4) && (xhr.status == 200)) {
			iDiv.innerHTML = xhr.responseText;
		}
	}
	xhr.send(data);
}

//save changes to db
function edit_to_db(tbl) {
	var data = "tbl=" + encodeURIComponent(tbl) + "&frm=edit_to_db";
	var elem = document.querySelectorAll("#edit_form input[type=hidden], #edit_form input[type=text], #edit_form input[type=date], #edit_form input[type=number], #edit_form input[type=password], #edit_form select");
	for (var i = 0; i < elem.length; i++) {
		var input = elem[i];
		data += "&" + input.id + "=" + encodeURIComponent(input.value);
	}
	if (tbl == "money")
	data += "&from=" + encodeURIComponent(document.getElementById("date_from").value)
	+ "&to=" + encodeURIComponent(document.getElementById("date_to").value)
	+ "&f_goods_id=" + encodeURIComponent(document.getElementById("f_goods_id").value)
	+ "&f_groups_id=" + encodeURIComponent(document.getElementById("f_groups_id").value)
	+ "&f_walls_id=" + encodeURIComponent(document.getElementById("f_walls_id").value)
	+ "&o=" + encodeURIComponent(document.getElementById("ordr").value);
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
	var data = "frm=" + encodeURIComponent("import_to_db");
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
	var el_1 = document.getElementById("p_date_from");
	var el_2 = document.getElementById("p_date_to");
	if ((el_1 == null) || (el_2 == null)) {
		var data = "frm=" + encodeURIComponent(form_id);
	} else {
		var data = "frm=" + encodeURIComponent(form_id) + "&from=" + encodeURIComponent(el_1.value) + "&to=" + encodeURIComponent(el_2.value);
	}
	var iDiv = get_new_div('report');
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'db.php', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {
		if ((xhr.readyState == 4) && (xhr.status == 200)) {
			iDiv.innerHTML = xhr.responseText;
		}
	}
	xhr.send(data);
}

window.onload = load();
</script>
</head>
<body>
</body></html>
