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

function byQu($mysqli, $query) {
	$result = $mysqli->query($query);
	if (!$result) die('Неверный запрос: ' . $mysqli->error . "<pre>Запрос целиком:\n" . $query .'</pre>');
	return $result;
}

function bySe($mysqli, $txt, $idn, $tbl, $id, $all) {
	echo '<tr><td>' . $txt . '<td><select size="1" id="' . $idn . '">';
	echo '<option' . ((-1 == $id) ? ' selected' : '') . ' value="-1">' . $all . '</option>';
	if ($tbl == 'goods') $result = byQu($mysqli, "SELECT goods.id, CONCAT(groups.name,' - ',IF(goods.comment='',goods.name,goods.comment)) AS name
	FROM goods
	LEFT JOIN groups ON goods.groups_id=groups.id
	ORDER BY name");
	else $result = byQu($mysqli, "SELECT id, name FROM $tbl ORDER BY name");
	while ($row = $result->fetch_assoc())
		echo '<option' . (($row['id'] == $id) ? ' selected' : '') . ' value="' . $row['id'] . '">' . $row['name'] . '</option>';
	echo '</select>';
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
	$result = byQu($mysqli, "SELECT id FROM users WHERE username='$user' AND password='$pass'");
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
	$errm = 'Имя или пароль не того';
	include 'login.php';
}
?>
