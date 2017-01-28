<?php include 'db.php';?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Кошелёк</title>
<link rel="stylesheet" href="style.css">
<script type="text/javascript">
function id_close(id) {
	var iDiv = document.getElementById(id);
	if (iDiv) {iDiv.parentNode.removeChild(iDiv);}
}

//get content
function get_form(form_id, id, s) {
	var data = (id == null) ? '' : "id=" + encodeURIComponent(id) + "&tbl=" + encodeURIComponent(s);
	id_close(form_id);
	var iDiv = document.createElement('div');
	iDiv.id = form_id;
	iDiv.className = 'hide';
	document.getElementsByTagName('section')[0].appendChild(iDiv);
	var xhr = new XMLHttpRequest();
	xhr.open('POST', form_id.concat('.php'), true);
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
	if (fltr === 1) {
		var data = "f=" + encodeURIComponent(fltr)
			+ "&from=" + encodeURIComponent(document.getElementById("date_from").value)
			+ "&to=" + encodeURIComponent(document.getElementById("date_to").value)
			+ "&f_goods_id=" + encodeURIComponent(document.getElementById("f_goods_id").value)
			+ "&f_groups_id=" + encodeURIComponent(document.getElementById("f_groups_id").value)
			+ "&f_walls_id=" + encodeURIComponent(document.getElementById("f_walls_id").value);
	} else if (fltr === 2) {
		var data = "f=" + encodeURIComponent(fltr)
			+ "&f_groups_id=" + encodeURIComponent(id)
			+ "&mo=" + encodeURIComponent(s);
	} else if (fltr === 3) {
		var data = "f=" + encodeURIComponent(fltr)
			+ "&f_goods_id=" + encodeURIComponent(id);
	} else return;
	id_close('money_table');
	var iDiv = document.createElement('div');
	iDiv.id = 'money_table';
	iDiv.className = 'hide';
	document.getElementsByTagName('section')[0].appendChild(iDiv);
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'money_table.php', true);
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
	id_close('money_form');
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'money_to_db.php', true);
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
		+ "&e_groups_id=" + encodeURIComponent(document.getElementById("e_groups_id").value);
	id_close("edit_form");
	var xhr = new XMLHttpRequest();
	xhr.open("POST", "edit_to_db.php", true);
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
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'import_to_db.php', true);
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
		var data = "";
	} else {
		var data = "from=" + encodeURIComponent(el_1.value) + "&to=" + encodeURIComponent(el_2.value);
	}
	id_close('report');
	id_close('report2');
	var iDiv = document.createElement('div');
	iDiv.id = form_id;
	iDiv.className = 'hide';
	document.getElementsByTagName('section')[0].appendChild(iDiv);
	var xhr = new XMLHttpRequest();
	xhr.open('POST', form_id.concat('.php'), true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {
		if ((xhr.readyState == 4) && (xhr.status == 200)) {
			iDiv.innerHTML = xhr.responseText;
		}
	}
	xhr.send(data);
}

</script>
</head>
<body>
<menu>
	<li><a href="./">Операции</a>
	<li>
		<a href="javascript:void(0)">Списки</a>
		<div>
			<a href="javascript:void(0)" onclick="get_form('edit_table',-1,'goods')">Категории</a>
			<a href="javascript:void(0)" onclick="get_form('edit_table',-1,'groups')">Группы</a>
			<a href="javascript:void(0)" onclick="get_form('edit_table',-1,'walls')">Кошельки</a>
		</div>
	<li>
		<a href="javascript:void(0)">Отчёты</a>
		<div>
			<a href="javascript:void(0)" onclick="get_report('report')">Отчёт помесячно</a>
			<a href="javascript:void(0)" onclick="get_report('report2')">Отчёт средний</a>
		</div>
	<li>
		<a href="javascript:void(0)">Служебные</a>
		<div>
			<a href="javascript:void(0)" onclick="get_form('import_form')">Импорт</a>
			<a href="backup.php">Резервное копирование</a>
		</div>
</menu>
<section>
	<div class="hide" id="money_table"><?php include 'money_table.php';?></div>
</section>
</body></html>
