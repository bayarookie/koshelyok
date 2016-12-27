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
function get_form(form_id, id) {
	id_close(form_id);
	var iDiv = document.createElement('div');
	iDiv.id = form_id;
	iDiv.className = 'hide';
	document.getElementsByTagName('section')[0].appendChild(iDiv);
	var xhr = new XMLHttpRequest();
	xhr.open('POST', form_id.concat('.php'), true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4) {
			if(xhr.status == 200) {
				iDiv.innerHTML = xhr.responseText;
				if (null != id) {
					inp = iDiv.children[0].children[1].children[0];
					inp.focus();
					inp.select();
				}
			}
		}
	}
	if (null == id) {xhr.send();} else {xhr.send("id=" + encodeURIComponent(id));}
}

//money
function money_table(fltr, s, id) {
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
		if (xhr.readyState == 4) {
			if(xhr.status == 200) {
				iDiv.innerHTML = xhr.responseText;
			}
		}
	}
	if (fltr === 1) {
		xhr.send("&f=" + encodeURIComponent(fltr)
			+ "&from=" + encodeURIComponent(date_from)
			+ "&to=" + encodeURIComponent(date_to)
			+ "&f_goods_id=" + encodeURIComponent(f_goods_id)
			+ "&f_groups_id=" + encodeURIComponent(f_groups_id)
			+ "&f_walls_id=" + encodeURIComponent(f_walls_id)
		);
	} else if (fltr === 2) {
		xhr.send("&f=" + encodeURIComponent(fltr)
			+ "&mo=" + encodeURIComponent(mo)
			+ "&f_groups_id=" + encodeURIComponent(f_groups_id)
		);
	} else if (fltr === 3) {
		xhr.send("&f=" + encodeURIComponent(fltr)
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
		if (xhr.readyState == 4) {
			if(xhr.status == 200) {
				document.getElementById("money_table").innerHTML = xhr.responseText;
			}
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

//goods save changes to db
function goods_to_db() {
	var g_id = document.getElementById("g_id").value;
	var name = document.getElementById("g_name").value;
	var komm = document.getElementById("g_comment").value;
	var r_id = document.getElementById("g_groups_id").value;
	id_close("goods_form");
	var xhr = new XMLHttpRequest();
	xhr.open("POST", "goods_to_db.php", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4) {
			if(xhr.status == 200) {
				document.getElementById("goods_table").innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send("g_id=" + encodeURIComponent(g_id)
		+ "&g_name=" + encodeURIComponent(name)
		+ "&g_comment=" + encodeURIComponent(komm)
		+ "&g_groups_id=" + encodeURIComponent(r_id)
	);
}

//groups save changes to db
function groups_to_db() {
	var r_id = document.getElementById("r_id").value;
	var name = document.getElementById("r_name").value;
	var komm = document.getElementById("r_comment").value;
	id_close('groups_form');
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'groups_to_db.php', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4) {
			if(xhr.status == 200) {
				document.getElementById("groups_table").innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send("r_id=" + encodeURIComponent(r_id)
		+ "&r_name=" + encodeURIComponent(name)
		+ "&r_comment=" + encodeURIComponent(komm)
	);
}

//walls save changes to db
function walls_to_db() {
	var w_id = document.getElementById("w_id").value;
	var name = document.getElementById("w_name").value;
	var komm = document.getElementById("w_comment").value;
	id_close('walls_form');
	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'walls_to_db.php', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4) {
			if(xhr.status == 200) {
				document.getElementById("walls_table").innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send("w_id=" + encodeURIComponent(w_id)
		+ "&w_name=" + encodeURIComponent(name)
		+ "&w_comment=" + encodeURIComponent(komm)
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
		if (xhr.readyState == 4) {
			if(xhr.status == 200) {
				document.getElementById("import_form").innerHTML = xhr.responseText;
			}
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
			<a href="javascript:void(0)" onclick="get_form('goods_table')">Категории</a>
			<a href="javascript:void(0)" onclick="get_form('groups_table')">Группы</a>
			<a href="javascript:void(0)" onclick="get_form('walls_table')">Кошельки</a>
		</div>
	<li>
		<a href="javascript:void(0)">Отчёты</a>
		<div>
			<a href="javascript:void(0)" onclick="get_form('report')">Отчёт помесячно</a>
			<a href="javascript:void(0)" onclick="get_form('report2')">Отчёт средний</a>
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
