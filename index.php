<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Кошелёк</title>
<link id="css" rel="stylesheet" href="css.php">
<script type="text/javascript">
function login() {
	var data = "login=1"
		+ "&username=" + encodeURIComponent(document.getElementById("username").value)
		+ "&password=" + encodeURIComponent(document.getElementById("password").value);
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'db.php', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {
		if ((xhr.readyState == 4) && (xhr.status == 200)) {
			document.body.innerHTML = xhr.responseText;
			money_table(0);
		}
	}
	xhr.send(data);
}

function logout() {
	var data = "logout=1";
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
	if (fltr === 1) {
		var data = "f=" + encodeURIComponent(fltr)
			+ "&from=" + encodeURIComponent(document.getElementById("date_from").value)
			+ "&to=" + encodeURIComponent(document.getElementById("date_to").value)
			+ "&f_goods_id=" + encodeURIComponent(document.getElementById("f_goods_id").value)
			+ "&f_groups_id=" + encodeURIComponent(document.getElementById("f_groups_id").value)
			+ "&f_walls_id=" + encodeURIComponent(document.getElementById("f_walls_id").value)
			+ "&o=" + encodeURIComponent(ordr)
			+ "&frm=money_table";
	} else if (fltr === 2) {
		var data = "f=" + encodeURIComponent(fltr)
			+ "&f_groups_id=" + encodeURIComponent(id)
			+ "&mo=" + encodeURIComponent(s)
			+ "&o=" + encodeURIComponent(ordr)
			+ "&frm=money_table";
	} else if (fltr === 3) {
		var data = "f=" + encodeURIComponent(fltr)
			+ "&f_goods_id=" + encodeURIComponent(id)
			+ "&o=" + encodeURIComponent(ordr)
			+ "&frm=money_table";
	} else if (fltr === 4) {
		var data = "f=" + encodeURIComponent(1)
			+ "&from=" + encodeURIComponent(document.getElementById("date_from").value)
			+ "&to=" + encodeURIComponent(document.getElementById("date_to").value)
			+ "&f_goods_id=" + encodeURIComponent(document.getElementById("f_goods_id").value)
			+ "&f_groups_id=" + encodeURIComponent(document.getElementById("f_groups_id").value)
			+ "&f_walls_id=" + encodeURIComponent(document.getElementById("f_walls_id").value)
			+ "&o=" + encodeURIComponent(id)
			+ "&frm=money_table";
	} else {
		var data = "";
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
//money save changes to db
function money_to_db() {
	var data = "m_id=" + encodeURIComponent(document.getElementById("m_id").value)
		+ "&m_op_date=" + encodeURIComponent(document.getElementById("m_op_date").value)
		+ "&m_op_summ=" + encodeURIComponent(document.getElementById("m_op_summ").value)
		+ "&m_goods_id=" + encodeURIComponent(document.getElementById("m_goods_id").value)
		+ "&m_comment=" + encodeURIComponent(document.getElementById("m_comment").value)
		+ "&m_walls_id=" + encodeURIComponent(document.getElementById("m_walls_id").value)
		+ "&from=" + encodeURIComponent(document.getElementById("date_from").value)
		+ "&to=" + encodeURIComponent(document.getElementById("date_to").value)
		+ "&f_goods_id=" + encodeURIComponent(document.getElementById("f_goods_id").value)
		+ "&f_groups_id=" + encodeURIComponent(document.getElementById("f_groups_id").value)
		+ "&f_walls_id=" + encodeURIComponent(document.getElementById("f_walls_id").value)
		+ "&o=" + encodeURIComponent(document.getElementById("ordr").value)
		+ "&frm=money_to_db";
	id_close('money_form');
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'db.php', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {
		if ((xhr.readyState == 4) && (xhr.status == 200)) {
			document.getElementById("money_table").innerHTML = xhr.responseText;
		}
	}
	xhr.send(data);
}

//save changes to db
function edit_to_db(tbl) {
	var data = "tbl=" + encodeURIComponent(tbl)
		+ "&e_id=" + encodeURIComponent(document.getElementById("e_id").value)
		+ "&e_name=" + encodeURIComponent(document.getElementById("e_name").value)
		+ "&e_comment=" + encodeURIComponent(document.getElementById("e_comment").value)
		+ "&e_groups_id=" + encodeURIComponent(document.getElementById("e_groups_id").value)
		+ "&frm=edit_to_db";
	id_close("edit_form");
	var xhr = new XMLHttpRequest();
	xhr.open("POST", "db.php", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.onreadystatechange = function() {
		if ((xhr.readyState == 4) && (xhr.status == 200)) {
			document.getElementById("edit_table").innerHTML = xhr.responseText;
		}
	}
	xhr.send(data);
}

//import from bankstate to db
function import_to_db() {
	var i_id = document.getElementById("i_id").value;
	var i_fn = document.getElementById("i_file");
	if (i_fn.files.length === 0) {return;}
	var data = new FormData();
	data.append('i_id', i_id);
	data.append('bankstate', i_fn.files[0]);
	data.append('frm', 'import_to_db');
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'db.php', true);
	xhr.onreadystatechange = function() {
		if ((xhr.readyState == 4) && (xhr.status == 200)) {
			document.getElementById("import_form").innerHTML = xhr.responseText;
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

window.onload = logout();
</script>
</head>
<body>
</body></html>
