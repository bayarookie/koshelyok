<?php
session_start();

if (isset($_POST['logout'])) {
	$_SESSION['user_id'] = '';
	session_destroy();
	$user = isset($_COOKIE['user']) ? $_COOKIE['user'] : '';
	$pass = isset($_COOKIE['pass']) ? $_COOKIE['pass'] : '';
	$reme = ($user != '') ? 'true' : 'false';
	setcookie("user", '', time()-60);
	setcookie("pass", '', time()-60);
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
	$user = isset($_POST['username']) ? $mysqli->real_escape_string($_POST['username']) : '';
	$pass = isset($_POST['password']) ? $mysqli->real_escape_string($_POST['password']) : '';
	$reme = isset($_POST['remember']) ? $_POST['remember'] : 'false';
	if ($user == '') {
		$user = isset($_COOKIE['user']) ? $mysqli->real_escape_string($_COOKIE['user']) : '';
		$pass = isset($_COOKIE['pass']) ? $mysqli->real_escape_string($_COOKIE['pass']) : '';
		$reme = ($user != '') ? 'true' : 'false';
	}
	if ($user != '') {
		$result = byQu($mysqli, "SELECT id FROM users WHERE username='$user' AND password='$pass'");
		if ($row = $result->fetch_row()) {
			$_SESSION['user_id'] = $row[0];
			if ($reme == 'true') {
				setcookie("user", $user, time()+60*60*24*30);
				setcookie("pass", $pass, time()+60*60*24*30);
			} else {
				setcookie("user", '', time()-60);
				setcookie("pass", '', time()-60);
			}
			include 'menu.php';
			die();
		}
	}
	include 'login.php';
	die();
}

$frm = isset($_POST['frm']) ? $_POST['frm'] : '';
if (isset($_SESSION['user_id'])) {
	$user_id = $_SESSION['user_id'];
	if ($frm != '') include $frm . '.php';
} else {
	echo '<article>Сессия окончена. <input type="button" value="Закрыть" onclick="logout()"></article>';
}
?>
