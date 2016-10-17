<?php include 'db.php';?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Кошелёк</title>
<link rel="stylesheet" href="style.css">
<script type="text/javascript">
function getXmlHttp() {
	var xmlhttp;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			xmlhttp = false;
		}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

//money
function money_table() {
	var date_from = document.getElementById("date_from").value;
	var date_to = document.getElementById("date_to").value;
	var f_goods_id = document.getElementById("f_goods_id").value;
	var f_groups_id = document.getElementById("f_groups_id").value;
	var f_walls_id = document.getElementById("f_walls_id").value;
	money_table_close();
	var iDiv = document.createElement('div');
	iDiv.id = 'money_table';
	iDiv.className = 'hide';
	document.getElementsByTagName('body')[0].appendChild(iDiv);
	var xmlhttp = getXmlHttp();
	xmlhttp.open('POST', 'money_table_db.php', true);
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4) {
			if(xmlhttp.status == 200) {
				iDiv.innerHTML = xmlhttp.responseText;
			}
		}
	}
	xmlhttp.send("&from=" + encodeURIComponent(date_from)
		+ "&to=" + encodeURIComponent(date_to)
		+ "&f_goods_id=" + encodeURIComponent(f_goods_id)
		+ "&f_groups_id=" + encodeURIComponent(f_groups_id)
		+ "&f_walls_id=" + encodeURIComponent(f_walls_id)
	);
}
function money_table_close() {
	var iDiv = document.getElementById('money_table');
	if (iDiv) {iDiv.parentNode.removeChild(iDiv);}
}
//money form
function money_form(m_id) {
	money_form_close();
	var iDiv = document.createElement('div');
	iDiv.id = 'money_form';
	iDiv.className = 'hide';
	document.getElementsByTagName('body')[0].appendChild(iDiv);
	var xmlhttp = getXmlHttp();
	xmlhttp.open('POST', 'money_form.php', true);
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4) {
			if(xmlhttp.status == 200) {
				iDiv.innerHTML = xmlhttp.responseText;
				inp = document.getElementById("m_op_summ");
				inp.focus();
				inp.select();
			}
		}
	}
	xmlhttp.send("m_id=" + encodeURIComponent(m_id));
}
function money_form_close() {
	var iDiv = document.getElementById('money_form');
	if (iDiv) {iDiv.parentNode.removeChild(iDiv);}
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
	money_form_close();
	var xmlhttp = getXmlHttp();
	xmlhttp.open('POST', 'money_to_db.php', true);
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4) {
			if(xmlhttp.status == 200) {
				document.getElementById("money_table").innerHTML = xmlhttp.responseText;
			}
		}
	}
	xmlhttp.send("m_id=" + encodeURIComponent(m_id)
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
	goods_table_close();
	var iDiv = document.createElement('div');
	iDiv.id = 'goods_table';
	iDiv.className = 'hide';
	document.getElementsByTagName('body')[0].appendChild(iDiv);
	var xmlhttp = getXmlHttp();
	xmlhttp.open('POST', 'goods_table_db.php', true);
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4) {
			if(xmlhttp.status == 200) {
				iDiv.innerHTML = xmlhttp.responseText;
			}
		}
	}
	xmlhttp.send();
}
function goods_table_close() {
	var iDiv = document.getElementById('goods_table');
	if (iDiv) {iDiv.parentNode.removeChild(iDiv);}
}
//goods form
function goods_form(g_id) {
	goods_form_close();
	var iDiv = document.createElement('div');
	iDiv.id = 'goods_form';
	iDiv.className = 'hide';
	document.getElementsByTagName('body')[0].appendChild(iDiv);
	var xmlhttp = getXmlHttp();
	xmlhttp.open('POST', 'goods_form.php', true);
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4) {
			if(xmlhttp.status == 200) {
				iDiv.innerHTML = xmlhttp.responseText;
				inp = document.getElementById("g_name");
				inp.focus();
				inp.select();
			}
		}
	}
	xmlhttp.send("g_id=" + encodeURIComponent(g_id));
}
function goods_form_close() {
	var iDiv = document.getElementById('goods_form');
	if (iDiv) {iDiv.parentNode.removeChild(iDiv);}
}
//goods save changes to db
function goods_to_db() {
	var g_id = document.getElementById("g_id").value;
	var name = document.getElementById("g_name").value;
	var komm = document.getElementById("g_comment").value;
	var r_id = document.getElementById("g_groups_id").value;
	goods_form_close();
	var xmlhttp = getXmlHttp();
	xmlhttp.open('POST', 'goods_to_db.php', true);
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4) {
			if(xmlhttp.status == 200) {
				document.getElementById("goods_table").innerHTML = xmlhttp.responseText;
			}
		}
	}
	xmlhttp.send("g_id=" + encodeURIComponent(g_id)
		+ "&g_name=" + encodeURIComponent(name)
		+ "&g_comment=" + encodeURIComponent(komm)
		+ "&g_groups_id=" + encodeURIComponent(r_id)
	);
}

//groups
function groups_table() {
	groups_table_close();
	var iDiv = document.createElement('div');
	iDiv.id = 'groups_table';
	iDiv.className = 'hide';
	document.getElementsByTagName('body')[0].appendChild(iDiv);
	var xmlhttp = getXmlHttp();
	xmlhttp.open('POST', 'groups_table_db.php', true);
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4) {
			if(xmlhttp.status == 200) {
				iDiv.innerHTML = xmlhttp.responseText;
			}
		}
	}
	xmlhttp.send();
}
function groups_table_close() {
	var iDiv = document.getElementById('groups_table');
	if (iDiv) {iDiv.parentNode.removeChild(iDiv);}
}
//groups form
function groups_form(r_id) {
	groups_form_close();
	var iDiv = document.createElement('div');
	iDiv.id = 'groups_form';
	iDiv.className = 'hide';
	document.getElementsByTagName('body')[0].appendChild(iDiv);
	var xmlhttp = getXmlHttp();
	xmlhttp.open('POST', 'groups_form.php', true);
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4) {
			if(xmlhttp.status == 200) {
				iDiv.innerHTML = xmlhttp.responseText;
				inp = document.getElementById("r_name");
				inp.focus();
				inp.select();
			}
		}
	}
	xmlhttp.send("r_id=" + encodeURIComponent(r_id));
}
function groups_form_close() {
	var iDiv = document.getElementById('groups_form');
	if (iDiv) {iDiv.parentNode.removeChild(iDiv);}
}
//groups save changes to db
function groups_to_db() {
	var r_id = document.getElementById("r_id").value;
	var name = document.getElementById("r_name").value;
	var komm = document.getElementById("r_comment").value;
	groups_form_close();
	var xmlhttp = getXmlHttp();
	xmlhttp.open('POST', 'groups_to_db.php', true);
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4) {
			if(xmlhttp.status == 200) {
				document.getElementById("groups_table").innerHTML = xmlhttp.responseText;
			}
		}
	}
	xmlhttp.send("r_id=" + encodeURIComponent(r_id)
		+ "&r_name=" + encodeURIComponent(name)
		+ "&r_comment=" + encodeURIComponent(komm)
	);
}

//walls
function walls_table() {
	walls_table_close();
	var iDiv = document.createElement('div');
	iDiv.id = 'walls_table';
	iDiv.className = 'hide';
	document.getElementsByTagName('body')[0].appendChild(iDiv);
	var xmlhttp = getXmlHttp();
	xmlhttp.open('POST', 'walls_table_db.php', true);
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4) {
			if(xmlhttp.status == 200) {
				iDiv.innerHTML = xmlhttp.responseText;
			}
		}
	}
	xmlhttp.send();
}
function walls_table_close() {
	var iDiv = document.getElementById('walls_table');
	if (iDiv) {iDiv.parentNode.removeChild(iDiv);}
}
//walls form
function walls_form(w_id) {
	walls_form_close();
	var iDiv = document.createElement('div');
	iDiv.id = 'walls_form';
	iDiv.className = 'hide';
	document.getElementsByTagName('body')[0].appendChild(iDiv);
	var xmlhttp = getXmlHttp();
	xmlhttp.open('POST', 'walls_form.php', true);
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4) {
			if(xmlhttp.status == 200) {
				iDiv.innerHTML = xmlhttp.responseText;
				inp = document.getElementById("w_name");
				inp.focus();
				inp.select();
			}
		}
	}
	xmlhttp.send("w_id=" + encodeURIComponent(w_id));
}
function walls_form_close() {
	var iDiv = document.getElementById('walls_form');
	if (iDiv) {iDiv.parentNode.removeChild(iDiv);}
}
//walls save changes to db
function walls_to_db() {
	var w_id = document.getElementById("w_id").value;
	var name = document.getElementById("w_name").value;
	var komm = document.getElementById("w_comment").value;
	walls_form_close();
	var xmlhttp = getXmlHttp();
	xmlhttp.open('POST', 'walls_to_db.php', true);
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4) {
			if(xmlhttp.status == 200) {
				document.getElementById("walls_table").innerHTML = xmlhttp.responseText;
			}
		}
	}
	xmlhttp.send("w_id=" + encodeURIComponent(w_id)
		+ "&w_name=" + encodeURIComponent(name)
		+ "&w_comment=" + encodeURIComponent(komm)
	);
}

//report
function report_table() {
	report_table_close();
	var iDiv = document.createElement('div');
	iDiv.id = 'report_table';
	iDiv.className = 'hide';
	document.getElementsByTagName('body')[0].appendChild(iDiv);
	var xmlhttp = getXmlHttp();
	xmlhttp.open('POST', 'report.php', true);
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4) {
			if(xmlhttp.status == 200) {
				iDiv.innerHTML = xmlhttp.responseText;
			}
		}
	}
	xmlhttp.send();
}
function report_table_close() {
	var iDiv = document.getElementById('report_table');
	if (iDiv) {iDiv.parentNode.removeChild(iDiv);}
}
function report_money(mo,f_groups_id) {
	money_table_close();
	var iDiv = document.createElement('div');
	iDiv.id = 'money_table';
	iDiv.className = 'hide';
	document.getElementsByTagName('body')[0].appendChild(iDiv);
	var xmlhttp = getXmlHttp();
	xmlhttp.open('POST', 'money_table_db.php', true);
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4) {
			if(xmlhttp.status == 200) {
				iDiv.innerHTML = xmlhttp.responseText;
			}
		}
	}
	xmlhttp.send("&mo=" + encodeURIComponent(mo)
		+ "&f_goods_id=-1"
		+ "&f_groups_id=" + encodeURIComponent(f_groups_id)
		+ "&f_walls_id=-1"
	);
}

//import form
function import_form(w_id) {
	import_form_close();
	var iDiv = document.createElement('div');
	iDiv.id = 'import_form';
	iDiv.className = 'hide';
	document.getElementsByTagName('body')[0].appendChild(iDiv);
	var xmlhttp = getXmlHttp();
	xmlhttp.open('POST', 'import_form.php', true);
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4) {
			if(xmlhttp.status == 200) {
				iDiv.innerHTML = xmlhttp.responseText;
			}
		}
	}
	xmlhttp.send();
}
function import_form_close() {
	var iDiv = document.getElementById('import_form');
	if (iDiv) {iDiv.parentNode.removeChild(iDiv);}
}
function import_to_db() {
	var w_id = document.getElementById("w_id").value;
	var i_fn = document.getElementById("i_file");
	if (i_fn.files.length === 0) {return;}
	var data = new FormData();
	//data.append('w_id', w_id);
	data.append('bankstate', i_fn.files[0]);
	var xmlhttp = getXmlHttp();
	xmlhttp.open('POST', 'import_to_db.php', true);
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4) {
			if(xmlhttp.status == 200) {
				document.getElementById("import_form").innerHTML = xmlhttp.responseText;
			}
		}
	}
	xmlhttp.send(data);
}
</script>
</head>
<body>
<h1><a href="">Кошелёк</a></h1>
<form action="backup.php" method="post">
	<input type="submit" name="submit" value="Backup database">
	<input type="button" value="Редактировать категории" onclick="goods_table()">
	<input type="button" value="Редактировать группы" onclick="groups_table()">
	<input type="button" value="Редактировать кошельки" onclick="walls_table()">
	<input type="button" value="Отчёт помесячно" onclick="report_table()">
	<input type="button" value="Импортировать" onclick="import_form()">
</form>
<div id="money_table" class="hide"><?php include 'money_table.php';?></div>
</body></html>
