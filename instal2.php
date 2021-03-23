<?php
$adm_name   = $_POST['adm_name'] ?? '';
$adm_pass   = $_POST['adm_pass'] ?? '';
$host       = $_POST['DB_ADRES'] ?? '';
$db_datab   = $_POST['DB_DATAB'] ?? '';
$db_login   = $_POST['DB_LOGIN'] ?? '';
$db_passw   = $_POST['DB_PASSW'] ?? '';
$db_chars   = $_POST['DB_CHARS'] ?? 'utf8';
$usr_name   = $_POST['usr_name'] ?? '';
$usr_pass   = $_POST['usr_pass'] ?? '';
$usr_disp   = $_POST['usr_disp'] ?? '';
if (file_exists('koshelyok.sql')) {
	$data_sql = 'koshelyok.sql';
} else {
	$data_sql = 'data.sql';
}

$myfile = fopen("config.php", "w") or die("Не открыть файл config.php для записи");
$txt = '<?php
define("DB_ADRES", "'.$host.'");
define("DB_DATAB", "'.$db_datab.'");
define("DB_LOGIN", "'.$db_login.'");
define("DB_PASSW", "'.$db_passw.'");
define("DB_CHARS", "'.$db_chars.'");
?>';
if (fwrite($myfile, $txt)) include 'config.php'; else die('Ошибка записи в config.php');
fclose($myfile);

$user = "'" . DB_LOGIN . "'@'" . DB_ADRES . "'";

echo 'Подключение к ' . DB_ADRES . '<br>';
$dbh = new mysqli(DB_ADRES, $adm_name, $adm_pass);
if ($dbh->connect_error) die("Подключение не установлено: " . $dbh->connect_error);
$dbh->set_charset(DB_CHARS);

$dbh->select_db('mysql');
$sql = "SELECT * FROM user WHERE user='" . DB_LOGIN . "'";
echo $sql . '<br>';
$result = $dbh->query($sql);
if (!$result) die("Ошибка открытия таблицы mysql.user: " . $dbh->error);
if ($result->num_rows == 1) die("Пользователь уже есть: " . DB_LOGIN);

echo 'Создание пользователя ' . $user . '<br>';
$sql = "CREATE USER " . $user . " IDENTIFIED BY '" . DB_PASSW . "'";
echo $sql . '<br>';
if (!$dbh->query($sql)) die("Пользователь не создан: " . $dbh->error);

echo 'Создание базы данных ' . DB_DATAB . '<br>';
$sql = "CREATE DATABASE IF NOT EXISTS `" . DB_DATAB . "` CHARACTER SET utf8 COLLATE utf8_general_ci";
echo $sql . '<br>';
if (!$dbh->query($sql)) die("База данных не создана: " . $dbh->error);

echo 'Права на базу данных ' . DB_DATAB . ' пользователю ' . $user . '<br>';
$sql = "GRANT ALL PRIVILEGES ON `" . DB_DATAB . "`.* TO " . $user;
echo $sql . '<br>';
if (!$dbh->query($sql)) die("Права на базу данных пользователю не выданы: " . $dbh->error);

$dbh->select_db(DB_DATAB);

echo 'Импорт данных из ' . $data_sql . '<br>';
$sql = '';
$lines = file($data_sql);
foreach ($lines as $line) {
	if (substr($line, 0, 2) == '--' || $line == '') continue;
	$sql .= $line;
	if (substr(trim($line), -1, 1) == ';') {
		echo 'execute ' . $data_sql . '<br>';
		$dbh->query($sql) or print('Ошибка в запросе \'<strong>' . $sql . '\': ' . $dbh->error . '<br><br>');
		$sql = '';
	}
}
$sql = "REPLACE INTO `users` (`id`, `username`, `password`, `name`, `walls_id`) VALUES
(1, '$usr_name', '$usr_pass', '$usr_disp', 0);";
echo "execute sql, add user to db table users<br>";
echo $sql . '<br>';
$dbh->query($sql) or print("Ошибка в запросе '<strong>" . $sql . "': " . $dbh->error . "<br><br>");
?>
<input type="button" value="Обновить страницу" onclick="location.reload()">
