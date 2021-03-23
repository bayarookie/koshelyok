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

$frm = isset($_POST['frm']) ? $_POST['frm'] : '';

if (!file_exists('config.php')) {
	if ($frm == 'instal2') {
		include 'instal2.php';
	} else {
		include 'install.php';
	}
	die();
}
if ($frm == 'install') {
	include 'install.php';
	die();
}
if ($frm == 'instal2') {
	include 'instal2.php';
	die();
}

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

/* Filtered ComboBox html5 javascript css
 * tx_idn - input[type=text]
 * bt_idn - input[type=button]
 *    idn - input[type=hidden] id storage, if value = -1 {add tx_idn.value to database}
 * dv_idn - div display: block or none
 * sl_idn - select
 * fn_idn - function, get value from select, focus next element
 * sh_idn - function, show select
 * ar_idn - array
 * echo byCb('e_table_id'); fieldname = tablename . '_id';
 */
function byCb($idn) {
	$fld_nam = $GLOBALS['fld_nam'];
	$arr = $GLOBALS['arr'];
	$tbl = substr($idn, 2, -3);
	$txt = $fld_nam[$tbl . '_id'];
	$id = $arr[$idn];
	$nam = '';
	$ret = 'var ar_' . $idn . ' = ([[-1, ""],';
	$result = byQu("SELECT id, name, comment FROM $tbl ORDER BY name");
	while ($row = $result->fetch_assoc()) {
		$n = $row['name'] . (($row['comment']) ? ' - ' . $row['comment'] : '');
		$ret .= '[' . $row['id'] . ', "' . $n . '"],';
		if ($row['id'] == $id) $nam = $n;
	}
	$ret .= ']);
function fn_' . $idn . '(){
	dv_' . $idn . '.style.display = "none";
	if(sl_' . $idn . '.value){
		tx_' . $idn . '.value = sl_' . $idn . '.options[sl_' . $idn . '.selectedIndex].text;
		' . $idn . '.value = sl_' . $idn . '.value;
	}else{
		' . $idn . '.value = -1;
	}
	var l = tx_' . $idn . '.parentElement.parentElement.nextElementSibling.children[1];
	if(l.children[0]) l.children[0].focus(); else l.focus();
}
function sh_' . $idn . '(a){
	dv_' . $idn . '.style.display = "block";
	sl_' . $idn . '.options.length = 0;
	var sel = -1;
	var testRegExp = new RegExp(RegExp.escape(tx_' . $idn . '.value),"i");
	for(var i = 0; i < ar_' . $idn . '.length; i++){
		var s = ar_' . $idn . '[i];
		if(s[1].match(testRegExp)||a){
			var opt = document.createElement("option");
			opt.value = s[0];
			opt.text = s[1];
			if(s.length > 2) opt.value += "\t" + s[2] + "\t" + s[3];
			sl_' . $idn . '.appendChild(opt);
		}
		if(s[1].toLowerCase() == tx_' . $idn . '.value.toLowerCase()) sel = i;
	}
	sl_' . $idn . '.size = Math.max(2,Math.min(sl_' . $idn . '.options.length,10));
	sl_' . $idn . '.selectedIndex = sel;
}
tx_' . $idn . '.onkeydown = function(e){
	if(e.keyCode == "9"){
		dv_' . $idn . '.style.display="none";
		if(tx_' . $idn . '.value && ' . $idn . '.value == "-1"){
			tx_' . $idn . '.value = "";
		}
	}
}
tx_' . $idn . '.onkeyup = function(e){
	if(e.keyCode == "38" || e.keyCode == "40"){
		sh_' . $idn . '(false);
		sl_' . $idn . '.focus();
		if((sl_' . $idn . '.selectedIndex < 0)&&(sl_' . $idn . '.options.length > 0)){
			sl_' . $idn . '.selectedIndex = 0;
		}
	}else if(tx_' . $idn . '.value && e.keyCode == "13"){
		sh_' . $idn . '(false);
		if(sl_' . $idn . '.options.length == 1){
			sl_' . $idn . '.selectedIndex = 0;
			fn_' . $idn . '();
		}else{
			' . $idn . '.value = tx_' . $idn . '.value;
			dv_' . $idn . '.style.display = "none";
			var l = tx_' . $idn . '.parentElement.parentElement.nextElementSibling.children[1];
			if(l.children[0]) l.children[0].focus(); else l.focus();
		}
	}else if(tx_' . $idn . '.value){
		sh_' . $idn . '(false);
	}else{
		dv_' . $idn . '.style.display = "none";
		sl_' . $idn . '.value = "";
		' . $idn . '.value = "-1";
		if(e.keyCode == "13"){
			fn_' . $idn . '();
		}
	}
}
bt_' . $idn . '.onclick = function(){
	if(dv_' . $idn . '.style.display == "none"){
		sh_' . $idn . '(true);
		sl_' . $idn . '.focus();
	}else{
		dv_' . $idn . '.style.display = "none";
	}
}
sl_' . $idn . '.onclick = fn_' . $idn . ';
sl_' . $idn . '.onkeyup = function(e){
	if(e.keyCode == "13"){
		fn_' . $idn . '();
	}else if(e.keyCode == "27"){
		dv_' . $idn . '.style.display = "none";
		tx_' . $idn . '.focus();
	}
}
if(combos.indexOf("' . $idn . '") < 0) combos.push("' . $idn . '");

';
	echo '<div><label>' . $txt . '</label> <div id="cb_' . $idn . '">
<input type="text" value="' . $nam . '" id="tx_' . $idn . '" class="combobox_input" autocomplete="off" placeholder="Все">
<input type="button" value="&#9662;" id="bt_' . $idn . '" class="combobox_button" tabindex="-1">
<input type="hidden" value="' . $id . '" id="' . $idn . '" name="' . $idn . '">
<div id="dv_' . $idn . '" style="display: none; position: absolute; z-index: 10;">
<select id="sl_' . $idn . '" class="combobox_list"></select></div></div></div>';
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
$i = $mysqli->connect_errno;
if ($i) {
	$s = 'Не удалось подключиться: ' . $mysqli->connect_errno . ' ' . $mysqli->connect_error;
	if (($i == 1045)||($i == 1049)) {
		die('<main>' . $s . '<br>Возможно, это первый запуск, запустить установку?
<input type="button" value="Установить" onclick="get_form(\'install\',0,\'\')"></main>');
	} else {
		die($s);
	}
}
$mysqli->set_charset(DB_CHARS);

//проверка на вшивость
$user = $mysqli->real_escape_string($_COOKIE['user'] ?? '');
$pass = $mysqli->real_escape_string($_COOKIE['pass'] ?? '');
$reme = ($user != '') ? 'true' : 'false';
if (isset($_POST['login'])) {
	$user = $mysqli->real_escape_string($_POST['username'] ?? '');
	$pass = $mysqli->real_escape_string($_POST['password'] ?? '');
	$reme = $_POST['remember'] ?? 'false';
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
