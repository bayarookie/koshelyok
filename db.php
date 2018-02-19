<?php
session_start();
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : -1;
$errm = '';

if (isset($_POST['logout'])) {
	$_SESSION['user_id'] = '';
	session_destroy();
	$user = isset($_COOKIE['user']) ? $_COOKIE['user'] : '';
	$pass = isset($_COOKIE['pass']) ? $_COOKIE['pass'] : '';
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

function bySe($txt, $idn, $tbl, $id, $all) {
	echo '<tr><td>' . $txt . '<td><select size="1" id="' . $idn . '" class="combobox">';
	echo '<option' . ((-1 == $id) ? ' selected' : '') . ' value="-1">' . $all . '</option>';
	if ($tbl == 'servs') $tbl = 'servs_v';
	if ($tbl == 'grups') $tbl = 'grups_v';
	$result = byQu("SELECT id, name, comment FROM $tbl ORDER BY name");
	while ($row = $result->fetch_assoc())
		echo '<option' . (($row['id'] == $id) ? ' selected' : '') . ' value="' . $row['id'] . '">'
		. $row['name'] . (($row['comment']) ? ' - ' . $row['comment'] : '') . '</option>';
	echo '</select>';
}

function byCo() {
	return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}

function byDt() {
	$result = byQu("SELECT MIN(op_date) FROM money");
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
	$result = byQu("SELECT id FROM users WHERE username='$user' AND password='$pass'");
	if ($row = $result->fetch_row()) {
		$user_id = intval($row[0]);
		$_SESSION['user_id'] = $user_id;
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
} else {
	$errm = 'Имя или пароль не подошли.';
	include 'login.php';
}
?>
