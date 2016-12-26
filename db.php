<?php
if (!function_exists('byQu')) {
	include 'config.php';

	function byQu($mysqli, $query) {
		$result = $mysqli->query($query);
		if (!$result) die('Неверный запрос: ' . $mysqli->error . "<pre>Запрос целиком:\n" . $query .'</pre>');
		return $result;
	}

	//подключение к базе
	$mysqli = new mysqli(DB_ADRES, DB_LOGIN, DB_PASSW, DB_DATAB);
	if ($mysqli->connect_errno) {
		header('Content-type: text/html; charset=UTF-8');
		die('Не удалось подключиться: ' . $mysqli->connect_error);
	}
	$mysqli->set_charset(DB_CHARS);

	//проверка на вшивость
	if (isset($_SERVER['PHP_AUTH_USER'])) $user = $_SERVER['PHP_AUTH_USER']; else $user = 'install';
	if (isset($_SERVER['PHP_AUTH_PW'])) $pass = $_SERVER['PHP_AUTH_PW']; else $pass = 'it';
	$user = $mysqli->real_escape_string($user);
	$pass = $mysqli->real_escape_string($pass);
	$result = byQu($mysqli, "SELECT id FROM users WHERE username='$user' AND password='$pass'");
	if ($row = $result->fetch_row()) {
		$u_id = $row[0];
	} else {
		header('WWW-Authenticate: Basic realm="' . DB_DATAB . '"');
		header('HTTP/1.0 401 Unauthorized');
		die ("Not authorized");
	}
}
?>
