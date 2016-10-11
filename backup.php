<?php
include 'db.php';
header('Content-type: text/plain; charset=utf-8');
header('Content-Disposition: attachment; filename=' . DB_DATAB . '.sql');

echo "-- Koshelyok SQL Dump
-- version 0.0.1
-- http://bayarookie.wallst.ru
--
-- Хост: " . DB_ADRES . "
-- Время создания: " . date('F j Y, H:i') . "
-- Версия сервера: " . $mysqli->server_info . "
-- Версия PHP: " . phpversion() . "

--
-- База данных: `" . DB_DATAB . "`
--
";


$tables = array();
$result = byQu($mysqli, "SHOW TABLES");
while($row = $result->fetch_row()) {
	$tables[] = $row[0];
}

foreach($tables as $table) {
	echo "
-- --------------------------------------------------------

--
-- Структура таблицы `$table`
--

";
	$result = byQu($mysqli, "SHOW CREATE TABLE " . $table);
	$row = $result->fetch_row();
	echo $row[1] . ";

--
-- Дамп данных таблицы `$table`
--

";
	$result = byQu($mysqli, "SELECT * FROM " . $table);
	$num_fields = $mysqli->field_count;
	if ($row = $result->fetch_row()) {
		$finfo = $result->fetch_fields();
		echo "INSERT INTO `$table` (";
		for($i=0; $i<$num_fields; $i++) {
			echo "`" . $finfo[$i]->name . "`";
			if ($i<($num_fields-1)) echo ", ";
		}
		echo ") VALUES";
		while (true) {
			echo "\n(";
			for($i=0; $i<$num_fields; $i++) {
				$row[$i] = $mysqli->real_escape_string($row[$i]);
				if (in_array($finfo[$i]->type, array(1, 2, 3, 8, 9), true)) echo $row[$i];
				else echo "'" . $row[$i] . "'";
				if ($i<($num_fields-1)) echo ", ";
			}
			echo ")";
			if ($row = $result->fetch_row()) echo ","; else break;
		}
	echo ";\n";
	}
}
?>
