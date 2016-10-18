<?php include 'db.php';?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Кошелёк</title>
<link rel="stylesheet" href="style.css">
<script type="text/javascript">
function getxhr() {
	var xhr;
	try {
		xhr = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			xhr = false;
		}
	}
	if (!xhr && typeof XMLHttpRequest!='undefined') {
		xhr = new XMLHttpRequest();
	}
	return xhr;
}
function id_close(id) {
	var iDiv = document.getElementById(id);
	if (iDiv) {iDiv.parentNode.removeChild(iDiv);}
}

//money
function money_table(fltr) {
	if (fltr == 1) {
		var date_from = document.getElementById("date_from").value;
		var date_to = document.getElementById("date_to").value;
		var f_goods_id = document.getElementById("f_goods_id").value;
		var f_groups_id = document.getElementById("f_groups_id").value;
		var f_walls_id = document.getElementById("f_walls_id").value;
	}
	id_close('money_table');
	var iDiv = document.createElement('div');
	iDiv.id = 'money_table';
	iDiv.className = 'hide';
	document.getElementsByTagName('body')[0].appendChild(iDiv);
	var xhr = getxhr();
	xhr.open('POST', 'money_table.php', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4) {
			if(xhr.status == 200) {
				iDiv.innerHTML = xhr.responseText;
			}
		}
	}
	if (fltr == 1) {
		xhr.send("&from=" + encodeURIComponent(date_from)
		+ "&to=" + encodeURIComponent(date_to)
		+ "&f_goods_id=" + encodeURIComponent(f_goods_id)
		+ "&f_groups_id=" + encodeURIComponent(f_groups_id)
		+ "&f_walls_id=" + encodeURIComponent(f_walls_id)
		);
	} else {
		xhr.send();
	}
}
//money form
function money_form(m_id) {
	id_close('money_form');
	var iDiv = document.createElement('div');
	iDiv.id = 'money_form';
	iDiv.className = 'hide';
	document.getElementsByTagName('body')[0].appendChild(iDiv);
	var xhr = getxhr();
	xhr.open('POST', 'money_form.php', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4) {
			if(xhr.status == 200) {
				iDiv.innerHTML = xhr.responseText;
				inp = document.getElementById("m_op_summ");
				inp.focus();
				inp.select();
			}
		}
	}
	xhr.send("m_id=" + encodeURIComponent(m_id));
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
	var xhr = getxhr();
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

//goods
function goods_table() {
	id_close('goods_table');
	var iDiv = document.createElement('div');
	iDiv.id = 'goods_table';
	iDiv.className = 'hide';
	document.getElementsByTagName('body')[0].appendChild(iDiv);
	var xhr = getxhr();
	xhr.open('POST', 'goods_table.php', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4) {
			if(xhr.status == 200) {
				iDiv.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send();
}
//goods form
function goods_form(g_id) {
	id_close('goods_form');
	var iDiv = document.createElement('div');
	iDiv.id = 'goods_form';
	iDiv.className = 'hide';
	document.getElementsByTagName('body')[0].appendChild(iDiv);
	var xhr = getxhr();
	xhr.open('POST', 'goods_form.php', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4) {
			if(xhr.status == 200) {
				iDiv.innerHTML = xhr.responseText;
				inp = document.getElementById("g_name");
				inp.focus();
				inp.select();
			}
		}
	}
	xhr.send("g_id=" + encodeURIComponent(g_id));
}
//goods save changes to db
function goods_to_db() {
	var g_id = document.getElementById("g_id").value;
	var name = document.getElementById("g_name").value;
	var komm = document.getElementById("g_comment").value;
	var r_id = document.getElementById("g_groups_id").value;
	id_close('goods_form');
	var xhr = getxhr();
	xhr.open('POST', 'goods_to_db.php', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
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

//groups
function groups_table() {
	id_close('groups_table');
	var iDiv = document.createElement('div');
	iDiv.id = 'groups_table';
	iDiv.className = 'hide';
	document.getElementsByTagName('body')[0].appendChild(iDiv);
	var xhr = getxhr();
	xhr.open('POST', 'groups_table.php', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4) {
			if(xhr.status == 200) {
				iDiv.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send();
}
//groups form
function groups_form(r_id) {
	id_close('groups_form');
	var iDiv = document.createElement('div');
	iDiv.id = 'groups_form';
	iDiv.className = 'hide';
	document.getElementsByTagName('body')[0].appendChild(iDiv);
	var xhr = getxhr();
	xhr.open('POST', 'groups_form.php', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4) {
			if(xhr.status == 200) {
				iDiv.innerHTML = xhr.responseText;
				inp = document.getElementById("r_name");
				inp.focus();
				inp.select();
			}
		}
	}
	xhr.send("r_id=" + encodeURIComponent(r_id));
}
//groups save changes to db
function groups_to_db() {
	var r_id = document.getElementById("r_id").value;
	var name = document.getElementById("r_name").value;
	var komm = document.getElementById("r_comment").value;
	id_close('groups_form');
	var xhr = getxhr();
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

//walls
function walls_table() {
	id_close('walls_table');
	var iDiv = document.createElement('div');
	iDiv.id = 'walls_table';
	iDiv.className = 'hide';
	document.getElementsByTagName('body')[0].appendChild(iDiv);
	var xhr = getxhr();
	xhr.open('POST', 'walls_table.php', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4) {
			if(xhr.status == 200) {
				iDiv.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send();
}
//walls form
function walls_form(w_id) {
	id_close('walls_form');
	var iDiv = document.createElement('div');
	iDiv.id = 'walls_form';
	iDiv.className = 'hide';
	document.getElementsByTagName('body')[0].appendChild(iDiv);
	var xhr = getxhr();
	xhr.open('POST', 'walls_form.php', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4) {
			if(xhr.status == 200) {
				iDiv.innerHTML = xhr.responseText;
				inp = document.getElementById("w_name");
				inp.focus();
				inp.select();
			}
		}
	}
	xhr.send("w_id=" + encodeURIComponent(w_id));
}
//walls save changes to db
function walls_to_db() {
	var w_id = document.getElementById("w_id").value;
	var name = document.getElementById("w_name").value;
	var komm = document.getElementById("w_comment").value;
	id_close('walls_form');
	var xhr = getxhr();
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

//report
function report_table() {
	id_close('report_table');
	var iDiv = document.createElement('div');
	iDiv.id = 'report_table';
	iDiv.className = 'hide';
	document.getElementsByTagName('body')[0].appendChild(iDiv);
	var xhr = getxhr();
	xhr.open('POST', 'report.php', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4) {
			if(xhr.status == 200) {
				iDiv.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send();
}
function report_money(mo,f_groups_id) {
	id_close('money_table');
	var iDiv = document.createElement('div');
	iDiv.id = 'money_table';
	iDiv.className = 'hide';
	document.getElementsByTagName('body')[0].appendChild(iDiv);
	var xhr = getxhr();
	xhr.open('POST', 'money_table.php', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4) {
			if(xhr.status == 200) {
				iDiv.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send("&mo=" + encodeURIComponent(mo)
		+ "&f_goods_id=-1"
		+ "&f_groups_id=" + encodeURIComponent(f_groups_id)
		+ "&f_walls_id=-1"
	);
}

//import form
function import_form(w_id) {
	id_close('import_form');
	var iDiv = document.createElement('div');
	iDiv.id = 'import_form';
	iDiv.className = 'hide';
	document.getElementsByTagName('body')[0].appendChild(iDiv);
	var xhr = getxhr();
	xhr.open('POST', 'import_form.php', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4) {
			if(xhr.status == 200) {
				iDiv.innerHTML = xhr.responseText;
			}
		}
	}
	xhr.send();
}
function import_to_db() {
	var w_id = document.getElementById("w_id").value;
	var i_fn = document.getElementById("i_file");
	if (i_fn.files.length === 0) {return;}
	var data = new FormData();
	data.append('w_id', w_id);
	data.append('bankstate', i_fn.files[0]);
	var xhr = getxhr();
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
<form action="backup.php" method="post">
	<input type="button" value="Операции" onclick="money_table(0)">
	<input type="button" value="Категории" onclick="goods_table()">
	<input type="button" value="Группы" onclick="groups_table()">
	<input type="button" value="Кошельки" onclick="walls_table()">
	<input type="button" value="Отчёт помесячно" onclick="report_table()">
	<input type="button" value="Импорт" onclick="import_form()">
	<input type="submit" value="Резервное копирование">
</form>
<h1><a href="">Кошелёк</a></h1>
<div id="money_table" class="hide"><?php include 'money_table.php';?></div>
</body></html>
