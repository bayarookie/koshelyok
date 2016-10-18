<?php
if (!function_exists('byQu')) {
include 'config.php';

function byQu($mysqli, $query) {
	$result = $mysqli->query($query);
	if (!$result) die('<pre>Неверный запрос: ' . $mysqli->error . "\nЗапрос целиком:\n" . $query .'</pre>');
	return $result;
}

//подключение к базе
$mysqli = new mysqli(DB_ADRES, DB_LOGIN, DB_PASSW, DB_DATAB);
if ($mysqli->connect_errno) {die('Не удалось подключиться: ' . $mysqli->connect_error);}
$mysqli->set_charset(DB_CHARS);

//проверка на вшивость
if (isset($_SERVER['PHP_AUTH_USER'])) {$user = $_SERVER['PHP_AUTH_USER'];} else {$user = 'install';};
if (isset($_SERVER['PHP_AUTH_PW'])) {$pass = $_SERVER['PHP_AUTH_PW'];} else {$pass = 'it';};
$user = $mysqli->real_escape_string($user);
$pass = $mysqli->real_escape_string($pass);
$query = "SELECT id FROM users WHERE username='$user' AND password='$pass'";
$result = byQu($mysqli, $query);
if ($row = $result->fetch_row()) {
	$u_id = $row[0];
} else {
	header('WWW-Authenticate: Basic realm="' . DB_DATAB . '"');
	header('HTTP/1.0 401 Unauthorized');
	die ("Not authorized");
}

//фильтр по дате за последний месяц или как, по категории, по группе
if (isset($_POST['to'])) {$f_dtto = date('Y-m-d', strtotime($_POST['to']));} else {$f_dtto = date('Y-m-d');}
if (isset($_POST['from'])) {$f_dtfr = date('Y-m-d', strtotime($_POST['from']));} else {$f_dtfr = date('Y-m-d', strtotime($f_dtto . ' -1 month'));}
if (isset($_POST['mo'])) {
	$f_dtto = date('Y-m-d', strtotime($_POST['mo'] . '-01 +1 month -1 day'));
	$f_dtfr = date('Y-m-d', strtotime($_POST['mo'] . '-01'));
}
if (isset($_POST['f_goods_id'])) {$f_goods_id = intval($_POST['f_goods_id']);} else {$f_goods_id = -1;}
if (isset($_POST['f_groups_id'])) {$f_groups_id = intval($_POST['f_groups_id']);} else {$f_groups_id = -1;}
if (isset($_POST['f_walls_id'])) {$f_walls_id = intval($_POST['f_walls_id']);} else {$f_walls_id = -1;}
}
?>
