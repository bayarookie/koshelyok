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
	$user = isset($_SERVER['PHP_AUTH_USER']) ? $mysqli->real_escape_string($_SERVER['PHP_AUTH_USER']) : 'install';
	$pass = isset($_SERVER['PHP_AUTH_PW']) ? $mysqli->real_escape_string($_SERVER['PHP_AUTH_PW']) : 'it';
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
