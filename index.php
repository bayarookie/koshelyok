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
	if (null == id) {
		xhr.send();
	} else {
		xhr.send("id=" + encodeURIComponent(id)
			+ "&tbl=" + encodeURIComponent(s));
	}
}

//money
function money_table(fltr, id, s) {
	if (fltr === 1) {
		var date_from = document.getElementById("date_from").value;
		var date_to = document.getElementById("date_to").value;
		var f_goods_id = document.getElementById("f_goods_id").value;
		var f_groups_id = document.getElementById("f_groups_id").value;
		var f_walls_id = document.getElementById("f_walls_id").value;
	} else if (fltr === 2) {
		var mo = s;
		var f_groups_id = id;
	} else if (fltr === 3) {
		var f_goods_id = id;
	}
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
	if (fltr === 1) {
		xhr.send("f=" + encodeURIComponent(fltr)
			+ "&from=" + encodeURIComponent(date_from)
			+ "&to=" + encodeURIComponent(date_to)
			+ "&f_goods_id=" + encodeURIComponent(f_goods_id)
			+ "&f_groups_id=" + encodeURIComponent(f_groups_id)
			+ "&f_walls_id=" + encodeURIComponent(f_walls_id)
		);
	} else if (fltr === 2) {
		xhr.send("f=" + encodeURIComponent(fltr)
			+ "&mo=" + encodeURIComponent(mo)
			+ "&f_groups_id=" + encodeURIComponent(f_groups_id)
		);
	} else if (fltr === 3) {
		xhr.send("f=" + encodeURIComponent(fltr)
			+ "&f_goods_id=" + encodeURIComponent(f_goods_id)
		);
	} else {
		xhr.send();
	}
}
//money save changes to db
function money_to_db() {
	var m_id = document.getElementById("m_id").value;
	var o_da = document.getElementById("m_op_date").value;
	var o_su = document.getElementById("m_op_summ").value;
	var g_id = document.getElementById("m_goods_id").value;
	var komm = document.getElementById("m_comment").value;
	var w_id = document.getElementById("m_walls_id").value;
	var dt_1 = document.getElementById("date_from").value;
	var dt_2 = document.getElementById("date_to").value;
	var f_gi = document.getElementById("f_goods_id").value;
	var f_ri = document.getElementById("f_groups_id").value;
	var f_wi = document.getElementById("f_walls_id").value;
	id_close('money_form');
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'money_to_db.php', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {
		if ((xhr.readyState == 4) && (xhr.status == 200)) {
			document.getElementById("money_table").innerHTML = xhr.responseText;
		}
	}
	xhr.send("m_id=" + encodeURIComponent(m_id)
		+ "&m_op_date=" + encodeURIComponent(o_da)
		+ "&m_op_summ=" + encodeURIComponent(o_su)
		+ "&m_goods_id=" + encodeURIComponent(g_id)
		+ "&m_comment=" + encodeURIComponent(komm)
		+ "&m_walls_id=" + encodeURIComponent(w_id)
		+ "&from=" + encodeURIComponent(dt_1)
		+ "&to=" + encodeURIComponent(dt_2)
		+ "&f_goods_id=" + encodeURIComponent(f_gi)
		+ "&f_groups_id=" + encodeURIComponent(f_ri)
		+ "&f_walls_id=" + encodeURIComponent(f_wi)
	);
}

//save changes to db
function edit_to_db(tbl) {
	var e_id = document.getElementById("e_id").value;
	var name = document.getElementById("e_name").value;
	var komm = document.getElementById("e_comment").value;
	var r_id = document.getElementById("e_groups_id").value;
	id_close("edit_form");
	var xhr = new XMLHttpRequest();
	xhr.open("POST", "edit_to_db.php", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.onreadystatechange = function() {
		if ((xhr.readyState == 4) && (xhr.status == 200)) {
			document.getElementById("edit_table").innerHTML = xhr.responseText;
		}
	}
	xhr.send("tbl=" + encodeURIComponent(tbl)
		+ "&e_id=" + encodeURIComponent(e_id)
		+ "&e_name=" + encodeURIComponent(name)
		+ "&e_comment=" + encodeURIComponent(komm)
		+ "&e_groups_id=" + encodeURIComponent(r_id)
	);
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
function get_report(rpt) {
	var form_id = (rpt == 2) ? 'report2' : 'report';
	var date_fr = document.getElementById("p_date_from").value;
	var date_to = document.getElementById("p_date_to").value;
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
	xhr.send("from=" + encodeURIComponent(date_fr)
			+ "&to=" + encodeURIComponent(date_to));
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
			<a href="javascript:void(0)" onclick="get_form('report_form',1)">Отчёт помесячно</a>
			<a href="javascript:void(0)" onclick="get_form('report_form',2)">Отчёт средний</a>
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
