<?php
session_start();

if (isset($_POST['logout'])) {
	$_SESSION['user_id'] = '';
	session_destroy();
	include 'login.php';
	die();
}

include 'config.php';

function byQu($mysqli, $query) {
	$result = $mysqli->query($query);
	if (!$result) die('Неверный запрос: ' . $mysqli->error . "<pre>Запрос целиком:\n" . $query .'</pre>');
	return $result;
}

//подключение к базе
$mysqli = new mysqli(DB_ADRES, DB_LOGIN, DB_PASSW, DB_DATAB);
if ($mysqli->connect_errno) {
	die('Не удалось подключиться: ' . $mysqli->connect_error);
}
$mysqli->set_charset(DB_CHARS);

//проверка на вшивость
if (isset($_POST['login'])) {
	$user = isset($_POST['username']) ? $mysqli->real_escape_string($_POST['username']) : 'guest';
	$pass = isset($_POST['password']) ? $mysqli->real_escape_string($_POST['password']) : 'guest';
	$result = byQu($mysqli, "SELECT id FROM users WHERE username='$user' AND password='$pass'");
	if ($row = $result->fetch_row()) {
		$_SESSION['user_id'] = $row[0];
		include 'menu.php';
	} else {
		include 'login.php';
	}
	die();
}

$frm = isset($_POST['frm']) ? $_POST['frm'] : '';
if (($frm != '') && (isset($_SESSION['user_id']))) {
	$u_id = $_SESSION['user_id'];
	include $frm . '.php';
}
?>
