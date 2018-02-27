<?php
session_start();
$debug = false;
$user_id = intval($_SESSION['user_id'] ?? -1);
$wall_id = intval($_SESSION['wall_id'] ?? -1);
$errm = '';
$arr = array();
$tbl_nam = array(
'money' => 'Транзакции',
'servs_v' => 'Конторы',
'grups_v' => 'Подгруппы',
'bgrup' => 'Группы',
'walls' => 'Кошельки',
'users' => 'Пользователи',
'money_order' => 'Сортировки',
);
$fld_nam = array(
'id' => 'id',
'name' => 'Наименование',
'comment' => 'Описание',
'op_date' => 'Дата',
'op_summ' => 'Сумма',
'servs_id' => 'Контора',
'bgrup_id' => 'Группа',
'grups_id' => 'Подгруппа',
'walls_id' => 'Кошелёк',
'users_id' => 'Пользователь',
'username' => 'Имя',
'password' => 'Пароль',
'order_by' => 'ORDER BY',
'grups_name' => 'Подгруппа',
'bgrup_name' => 'Группа',
'cnt' => 'опер.',
'money_order_id' => 'Сортировка',
);

if (isset($_POST['logout'])) {
	$_SESSION['user_id'] = '';
	session_destroy();
	$user = $_COOKIE['user'] ?? '';
	$pass = $_COOKIE['pass'] ?? '';
	$reme = ($user != '') ? 'true' : 'false';
	setcookie("user", '', time()-60);
	setcookie("pass", '', time()-60);
	$errm = 'Вышел? Можешь зайти обратно.';
	include 'login.php';
	die();
}

include 'config.php';

function byQu($query) {
	$mysqli = $GLOBALS['mysqli'];
	$result = $mysqli->query($query);
	if (!$result) die('Неверный запрос: ' . $mysqli->error . "<pre>Запрос целиком:\n" . $query .'</pre>');
	return $result;
}

function byCb($idn) {
	$fld_nam = $GLOBALS['fld_nam'];
	$arr = $GLOBALS['arr'];
	$tbl = substr($idn, 2, -3);
	$txt = $fld_nam[$tbl . '_id'];
	$id = $arr[$idn];
	$nam = '';
	if ($idn == 'e_servs_id') {
		$ret = 'var ar_e_servs_id = ([[-1, "", -1, ""],';
		$result = byQu("SELECT servs.id, servs.name, servs.comment, grups_id, grups.name as grups_name
		FROM servs LEFT JOIN grups ON grups_id=grups.id ORDER BY name");
		while ($row = $result->fetch_assoc()) {
			$n = $row['name'] . (($row['comment']) ? ' - ' . $row['comment'] : '');
			$ret .= '[' . $row['id'] . ', "' . $n . '", ' . $row['grups_id'] . ', "' . $row['grups_name'] . '"],';
			if ($row['id'] == $id) $nam = $n;
		}
		$ret .= ']);
var id_e_servs_id = document.getElementById("id_e_servs_id");

var va_e_servs_id = document.getElementById("e_servs_id");
var tx_e_servs_id = document.getElementById("tx_e_servs_id");
var bt_e_servs_id = document.getElementById("bt_e_servs_id");
var dv_e_servs_id = document.getElementById("dv_e_servs_id");
var sl_e_servs_id = document.getElementById("sl_e_servs_id");
function fn_e_servs_id(){
	dv_e_servs_id.style.display = "none";
	va_e_servs_id.value = sl_e_servs_id.value.split("\t")[0];
	va_e_grups_id.value = sl_e_servs_id.value.split("\t")[1];
	tx_e_grups_id.value = sl_e_servs_id.value.split("\t")[2];
	tx_e_servs_id.value = sl_e_servs_id.options[sl_e_servs_id.selectedIndex].text;
	tx_e_grups_id.focus();
}
tx_e_servs_id.onkeydown = function(e){
	if(e.keyCode == "9"){
		dv_e_servs_id.style.display = "none";
	}
}
tx_e_servs_id.onkeyup = function(e){
	dv_e_servs_id.style.display = "none";
	sl_e_servs_id.options.length = 0;
	if((tx_e_servs_id.value)||(e.keyCode == "40")){
		dv_e_servs_id.style.display = "block";
		sl_e_servs_id.size = 2;
		var testRegExp = new RegExp(RegExp.escape(tx_e_servs_id.value),"i");
		for(var i = 0; i < ar_e_servs_id.length; i++){
			var s = ar_e_servs_id[i][1];
			if(s.match(testRegExp)){
				var opt = document.createElement("option");
				opt.text = s;
				opt.value = ar_e_servs_id[i][0] + "\t" + ar_e_servs_id[i][2] + "\t" + ar_e_servs_id[i][3];
				sl_e_servs_id.appendChild(opt);
				sl_e_servs_id.size = Math.max(2,Math.min(sl_e_servs_id.options.length,10));
			}
		}
		if(e.keyCode == "40"){
			sl_e_servs_id.focus();
			if(sl_e_servs_id.options.length > 0){
				sl_e_servs_id.selectedIndex = 0;
			}
		}
		if(sl_e_servs_id.options.length == 1){
			sl_e_servs_id.selectedIndex = 0;
			fn_e_servs_id();
		}
	}
}
bt_e_servs_id.onclick = function(){
	if(dv_e_servs_id.style.display == "none"){
		dv_e_servs_id.style.display = "block";
		sl_e_servs_id.options.length = 0;
		var sel = -1;
		for(var i = 0; i < ar_e_servs_id.length; i++){
			var opt = document.createElement("option");
			opt.value = ar_e_servs_id[i][0] + "\t" + ar_e_servs_id[i][2] + "\t" + ar_e_servs_id[i][3];
			opt.text = ar_e_servs_id[i][1];
			sl_e_servs_id.appendChild(opt);
			if(ar_e_servs_id[i][0] == tx_e_servs_id.value){
				sel = i;
			}
		}
		sl_e_servs_id.size = Math.max(2,Math.min(sl_e_servs_id.options.length,10));
		sl_e_servs_id.selecteIndex = sel;
	}else{
		dv_e_servs_id.style.display = "none";
		tx_e_servs_id.focus();
	}
}
sl_e_servs_id.onclick = fn_e_servs_id;
sl_e_servs_id.onkeyup = function(e){
	if(e.keyCode == "13"){
		fn_e_servs_id();
	}else if(e.keyCode == "27"){
		dv_e_servs_id.style.display = "none";
		tx_e_servs_id.focus();
	}
}
document.onclick = function(e){
	if(dv_e_servs_id.style.display == "block" && e.target.id !== "dv_e_servs_id" && e.target.id !== "bt_e_servs_id"){
		dv_e_servs_id.style.display = "none";
		tx_e_servs_id.focus();
	}
}

';
	} else {
		$ret = 'var ar_' . $idn . ' = ([[-1, ""],';
		$result = byQu("SELECT id, name, comment FROM $tbl ORDER BY name");
		while ($row = $result->fetch_assoc()) {
			$n = $row['name'] . (($row['comment']) ? ' - ' . $row['comment'] : '');
			$ret .= '[' . $row['id'] . ', "' . $n . '"],';
			if ($row['id'] == $id) $nam = $n;
		}
		$ret .= ']);
var va_' . $idn . ' = document.getElementById("' . $idn . '");
var tx_' . $idn . ' = document.getElementById("tx_' . $idn . '");
var bt_' . $idn . ' = document.getElementById("bt_' . $idn . '");
var dv_' . $idn . ' = document.getElementById("dv_' . $idn . '");
var sl_' . $idn . ' = document.getElementById("sl_' . $idn . '");
function fn_' . $idn . '(){
	dv_' . $idn . '.style.display = "none";
	va_' . $idn . '.value = sl_' . $idn . '.value;
	tx_' . $idn . '.value = sl_' . $idn . '.options[sl_' . $idn . '.selectedIndex].text;
	tx_' . $idn . '.parentElement.nextElementSibling.children[1].focus();
}
tx_' . $idn . '.onkeydown = function(e){
	if(e.keyCode == "9"){
		dv_' . $idn . '.style.display="none";
	}
}
tx_' . $idn . '.onkeyup = function(e){
	dv_' . $idn . '.style.display = "none";
	sl_' . $idn . '.options.length = 0;
	if((tx_' . $idn . '.value)||(e.keyCode == "40")){
		dv_' . $idn . '.style.display = "block";
		sl_' . $idn . '.size = 2;
		var testRegExp = new RegExp(RegExp.escape(tx_' . $idn . '.value),"i");
		for(var i = 0; i < ar_' . $idn . '.length; i++){
			var s = ar_' . $idn . '[i][1];
			if(s.match(testRegExp)){
				var opt = document.createElement("option");
				opt.text = s;
				opt.value = ar_' . $idn . '[i][0];
				sl_' . $idn . '.appendChild(opt);
				sl_' . $idn . '.size = Math.max(2,Math.min(sl_' . $idn . '.options.length,10));
			}
		}
		if(e.keyCode == "40"){
			sl_' . $idn . '.focus();
			if(sl_' . $idn . '.options.length > 0){
				sl_' . $idn . '.selectedIndex = 0;
			}
		}
	}
}
bt_' . $idn . '.onclick = function(){
	if(dv_' . $idn . '.style.display == "none"){
		dv_' . $idn . '.style.display = "block";
		sl_' . $idn . '.options.length = 0;
		for(var i = 0; i < ar_' . $idn . '.length; i++){
			var opt = document.createElement("option");
			opt.value = ar_' . $idn . '[i][0];
			opt.text = ar_' . $idn . '[i][1];
			sl_' . $idn . '.appendChild(opt);
		}
		sl_' . $idn . '.size = Math.max(2,Math.min(sl_' . $idn . '.options.length,10));
	}else{
		dv_' . $idn . '.style.display = "none";
	}
}
sl_' . $idn . '.onclick = fn_' . $idn . ';
sl_' . $idn . '.onkeyup = function(e){
	if(e.keyCode == "13"){
		dv_' . $idn . '.style.display = "none";
		va_' . $idn . '.value = sl_' . $idn . '.value;
		tx_' . $idn . '.value = sl_' . $idn . '.options[sl_' . $idn . '.selectedIndex].text;
		tx_' . $idn . '.focus();
	}else if(e.keyCode == "27"){
		dv_' . $idn . '.style.display = "none";
		tx_' . $idn . '.focus();
	}
}

';
	}
	echo '<div><label>' . $txt . '</label>
	<input type="text" value="' . $nam . '" id="tx_' . $idn . '" class="combobox_input">
	<input type="button" value="&#9662;" id="bt_' . $idn . '" class="combobox_button" tabindex="-1">
	<input type="hidden" value="' . $id . '" id="' . $idn . '" name="' . $idn . '">
	<div id="dv_' . $idn . '" style="display: none; position: absolute; z-index: 10;">
	<select id="sl_' . $idn . '" class="combobox_list"></select></div></div>';
	return $ret;
}

function byCo() {
	return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}

function byDt($m) {
	$result = byQu("SELECT $m(op_date) FROM money");
	$f_dtfr = ($row = $result->fetch_row()) ? $row[0] : '2015-01-01';
	return $f_dtfr;
}

//подключение к базе
$mysqli = new mysqli(DB_ADRES, DB_LOGIN, DB_PASSW, DB_DATAB);
if ($mysqli->connect_errno) {
	die('Не удалось подключиться: ' . $mysqli->connect_error);
}
$mysqli->set_charset(DB_CHARS);

//проверка на вшивость
$user = isset($_COOKIE['user']) ? $mysqli->real_escape_string($_COOKIE['user']) : '';
$pass = isset($_COOKIE['pass']) ? $mysqli->real_escape_string($_COOKIE['pass']) : '';
$reme = ($user != '') ? 'true' : 'false';
if (isset($_POST['login'])) {
	$user = isset($_POST['username']) ? $mysqli->real_escape_string($_POST['username']) : '';
	$pass = isset($_POST['password']) ? $mysqli->real_escape_string($_POST['password']) : '';
	$reme = isset($_POST['remember']) ? $_POST['remember'] : 'false';
}
if ($user != '') {
	$result = byQu("SELECT id, walls_id FROM users WHERE username='$user' AND password='$pass'");
	if ($row = $result->fetch_row()) {
		$user_id = intval($row[0]);
		$wall_id = intval($row[1]);
		$_SESSION['user_id'] = $user_id;
		$_SESSION['wall_id'] = $wall_id;
		if ($reme == 'true') {
			setcookie("user", $user, time()+60*60*24*30);
			setcookie("pass", $pass, time()+60*60*24*30);
		} else {
			setcookie("user", '', time()-60);
			setcookie("pass", '', time()-60);
		}
	} else $user_id = -1;
}
if ($user_id > 0) {
	if (isset($_POST['load']) || isset($_POST['login'])) include 'menu.php';
	$frm = isset($_POST['frm']) ? $_POST['frm'] : '';
	if ($frm != '') include $frm . '.php';
	if ($debug) {
		echo '<pre>';
		print_r($_POST);
		echo '</pre>';
	}
} else {
	$errm = 'Имя или пароль не подошли.';
	include 'login.php';
}
?>
