<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Кошелёк</title>
<link rel="shortcut icon" type="image/png" href="favicon.png">
<link rel="stylesheet" href="css.php" id="css">
<script type="text/javascript" src="Chart.min.js"></script>
</head><body>
<script>
RegExp.escape = function(s){
	return s.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
};

function ajaxsend(data, elem){
	var xhr = new XMLHttpRequest();
	xhr.open("POST", "db.php", true);
	if (typeof(data) == "string") xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.onreadystatechange = function(){
		if((xhr.readyState == 4) && (xhr.status == 200)){
			elem.innerHTML = xhr.responseText;
			var js = document.getElementById("js");
			if (js) {
				eval(js.innerHTML);
				js.parentNode.removeChild(js);
			}
			var els = elem.querySelectorAll("input[type=text], input[type=number]");
			if(els.length > 0){
				el = els[0];
				el.focus();
				el.select();
			}
		}
	}
	xhr.send(data);
}

function load(){
	var data = "load=1";
	ajaxsend(data, document.body);
}

function login(){
	var data = "login=1",
		el_1 = document.getElementById("username");
	if(el_1) data += "&username=" + encodeURIComponent(el_1.value)
	+ "&password=" + encodeURIComponent(document.getElementById("password").value)
	+ "&remember=" + document.getElementById("remember").checked;
	ajaxsend(data, document.body);
}

function logout(){
	var data = "logout=1";
	ajaxsend(data, document.body);
}

function ch_css(id){
	document.getElementById("css").href="css.php?CSSID="+id;
}

function id_close(id){
	var sect = document.getElementById(id);
	if(sect) sect.parentNode.removeChild(sect);
}

function get_new_sect(id){
	id_close(id);
	var sect = document.createElement("section");
	sect.id = id;
	document.getElementsByTagName("main")[0].appendChild(sect);
	return sect;
}

function get_inputs(e, s){
	var data = "",
		elem = e.querySelectorAll(s),
		imax = elem.length;
	for(var i = 0; i < imax; i++){
		el = elem[i];
		if(el.name) data += "&" + el.name + "=" + encodeURIComponent(el.value);
	}
	return data;
}

//get content
function get_form(form_id, id, s){
	var data = "frm=" + form_id;
	if(id != null) data += "&id=" + id + "&tbl=" + s;
	ajaxsend(data, get_new_sect(form_id));
}

//money
function money_table(fltr, id, s){
	var data = "frm=money_table";
	if(fltr == 3){
		data += "&f=3&f_servs_id=" + id + s;
	}else if(fltr == 4){
		data += "&f=3&f_grups_id=" + id + s;
	}else if(fltr == 5){
		data += "&f=5&f_servs_id=" + id + s;
		var el_2 = document.getElementById("report");
		if(el_2) data += get_inputs(el_2, "input[type=date]");
	}else if(fltr == 6){
		data += "&f=5&f_grups_id=" + id + s;
		var el_2 = document.getElementById("report");
		if(el_2) data += get_inputs(el_2, "input[type=date], input[type=hidden], select");
	}else if(fltr == 7){
		data += "&f=5&f_bgrup_id=" + id + s;
		var el_2 = document.getElementById("report");
		if(el_2) data += get_inputs(el_2, "input[type=date]");
	}else if(fltr == 10){
		data += "&f=5&f_bgrup_id=" + id + s;
		var el_2 = document.getElementById("report");
		if(el_2) data += get_inputs(el_2, "input[type=hidden], select");
	}else if(fltr >= 0){
		var el_1 = document.getElementById("o_money_order_id");
		if(fltr == 0) if(el_1) el_1.value = id;
		var o = el_1 ? el_1.value : 1;
		data += "&o_money_order_id=" + o;
		var el_2 = document.getElementById("money_table");
		if(el_2) data += get_inputs(el_2, "input[type=date], input[type=hidden], select");
	}
	ajaxsend(data, get_new_sect("money_table"));
}

//save changes to db
function edit_to_db(tbl){
	var data = "frm=edit_to_db&tbl=" + encodeURIComponent(tbl),
		efrm = document.getElementById("edit_form");
	data += get_inputs(efrm, "input, select");
	if(tbl == "money"){
		var sect = document.getElementById("money_table");
		data += get_inputs(sect, "input[type=date], input[type=hidden], select");
	}else var sect = document.getElementById("edit_table");
	id_close("edit_form");
	ajaxsend(data, sect);
}

//import from bankstate
function import_form2(){
	var w_id = document.getElementById("i_walls_id").value,
		i_fn = document.getElementById("bankstate"),
		fdat = new FormData();
	if(i_fn.files.length === 0) return;
	fdat.append("i_walls_id", w_id);
	fdat.append("bankstate", i_fn.files[0]);
	fdat.append("frm", "import_form2");
	ajaxsend(fdat, document.getElementById("import_form2"));
}

//save bankstate to db
function import_to_db(){
	var data = "frm=import_to_db",
		elem = document.querySelectorAll("#import_form2 input:checked"),
		imax = elem.length;
	for(var i = 0; i < imax; i++) data += "&imp_" + i + "=" + encodeURIComponent(elem[i].value);
	ajaxsend(data, document.getElementById("import_form2"));
}

//save services from bankstate to db
function imp_to_serv(){
	var data = "frm=imp_to_serv",
		elem = document.querySelectorAll("#import_form2 input:checked"),
		imax = elem.length;
	for(var i = 0; i < imax; i++) data += "&srv_" + i + "=" + encodeURIComponent(elem[i].value);
	ajaxsend(data, document.getElementById("import_form2"));
}

//get report
function get_report(form_id){
	var data = "frm=" + form_id,
		sect = document.getElementById("report");
	if(sect) data += get_inputs(sect, "input[type=date], input[type=hidden], select");
	sect = get_new_sect("report");
	ajaxsend(data, sect);
}

function color_tr(){
	for(var i = 0; i < id_money.rows.length; i++){
		var t = id_money.rows[i];
		if(t.cells[3].innerHTML) var t3 = parseFloat(t.cells[3].innerHTML); else var t3 = 0;
		if(t.cells[4].innerHTML) var t4 = parseFloat(t.cells[4].innerHTML); else var t4 = 0;
		if((t3 + t4) < 0) t.className = "minus"; else t.className = "plus";
	}
}

var combos = [];

document.onclick = function(e){
	combos.forEach(function(s) {
		var el = document.getElementById("cb_" + s);
		if((el) && (el.lastChild.style.display == "block") && (!e.target.id.match(s))){
			el.children[0].focus();
			el.children[3].style.display = "none";
		}
	});
}

document.onkeyup = function(e) {
	if (e.ctrlKey || e.altKey || e.shiftKey || e.metaKey) exit;
	if (e.key == 'Insert') {
		if (document.getElementById("edit_table_servs_v")) {
			get_form('edit_form', -1, 'servs_v');
		} 
		else
		if (document.getElementById("edit_table_grups_v")) {
			get_form('edit_form', -1, 'grups_v');
		} 
		else
		if (document.getElementById("edit_table_bgrup")) {
			get_form('edit_form', -1, 'bgrup');
		} 
		else
		if (document.getElementById("edit_table_walls")) {
			get_form('edit_form', -1, 'walls');
		} 
		else
		if (document.getElementById("edit_table_users")) {
			get_form('edit_form', -1, 'users');
		} 
		else
		if (document.getElementById("edit_table_money_order")) {
			get_form('edit_form', -1, 'money_order');
		} 
		else
			get_form('edit_form', -1, 'money');
	}
	else
	if (e.key == 'Escape') {
		if (document.getElementById("edit_form")) {
			id_close('edit_form');
		}
		else
			id_close('edit_table');
	};
}

window.onload = load();
</script>
</body></html>
