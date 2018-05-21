<?php
$host       = DB_ADRES;
$rootname   = "root";
$rootpass   = "1";
$username   = DB_LOGIN;
$userpass   = DB_PASSW;
$dbname     = DB_DATAB;
$filename   = "data.sql";

echo 'Подключение к ' . $host . '<br>';
$dbh = new mysqli($host, $rootname, $rootpass);
if ($dbh->connect_error) die("Подключение не установлено: " . $dbh->connect_error);
$dbh->set_charset(DB_CHARS);

echo 'Создание пользователя ' . $username . '<br>';
$sql = "CREATE USER '$username'@'$host' IDENTIFIED BY '$userpass'";
if (!$dbh->query($sql)) die("Пользователь не создан: " . $dbh->error);

echo 'Права пользователю ' . $username . '<br>';
$sql = "GRANT USAGE ON *.* TO '$username'@'$host' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0";
if (!$dbh->query($sql)) die("Права пользователю не выданы: " . $dbh->error);

echo 'Создание базы данных ' . $dbname . '<br>';
$sql = "CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8 COLLATE utf8_general_ci";
if (!$dbh->query($sql)) die("База данных не создана: " . $dbh->error);

echo 'Права на базу данных ' . $dbname . ' пользователю ' . $username . '<br>';
$sql = "GRANT ALL PRIVILEGES ON `$dbname`.* TO '$username'@'$host'";
if (!$dbh->query($sql)) die("Права на базу данных пользователю не выданы: " . $dbh->error);

$dbh->select_db($dbname);

echo 'Импорт данных из ' . $filename . '<br>';
$sql = '';
$lines = file($filename);
foreach ($lines as $line) {
	if (substr($line, 0, 2) == '--' || $line == '') continue;
	$sql .= $line;
	if (substr(trim($line), -1, 1) == ';') {
		echo 'execute sql<br>';
		$dbh->query($sql) or print('Ошибка в запросе \'<strong>' . $sql . '\': ' . $dbh->error . '<br><br>');
		$sql = '';
	}
}
?>
<input type="button" value="Обновить страницу" onclick="location.reload()">
