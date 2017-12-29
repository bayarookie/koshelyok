<?php
include 'db.php';
header('Content-type: text/plain; charset=utf-8');
header('Content-Disposition: attachment; filename=' . DB_DATAB . '.sql');

echo "-- Koshelyok SQL Dump
-- version 0.0.2
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


$res1 = byQu("SHOW FULL TABLES");
while($ro1 = $res1->fetch_row()) {
	$table = $ro1[0];
if ($ro1[1] == 'VIEW') {
	echo "
-- --------------------------------------------------------

--
-- Структура для представления `$table`
--

";
	$res2 = byQu("SHOW CREATE TABLE " . $table);
	$ro2 = $res2->fetch_row();
	echo $ro2[1] . ";
";
} else {
	echo "
-- --------------------------------------------------------

--
-- Структура таблицы `$table`
--

";
	$res2 = byQu("SHOW CREATE TABLE " . $table);
	$ro2 = $res2->fetch_row();
	echo $ro2[1] . ";

--
-- Дамп данных таблицы `$table`
--

";
	$res3 = byQu("SELECT * FROM " . $table);
	$num_fields = $mysqli->field_count;
	if ($ro3 = $res3->fetch_row()) {
		$finfo = $res3->fetch_fields();
		echo "INSERT INTO `$table` (";
		for($i=0; $i<$num_fields; $i++) {
			echo "`" . $finfo[$i]->name . "`";
			if ($i<($num_fields-1)) echo ", ";
		}
		echo ") VALUES";
		while (true) {
			echo "\n(";
			for($i=0; $i<$num_fields; $i++) {
				$ro3[$i] = $mysqli->real_escape_string($ro3[$i]);
				if (in_array($finfo[$i]->type, array(1, 2, 3, 8, 9), true)) echo $ro3[$i];
				else echo "'" . $ro3[$i] . "'";
				if ($i<($num_fields-1)) echo ", ";
			}
			echo ")";
			if ($ro3 = $res3->fetch_row()) echo ","; else break;
		}
	echo ";\n";
	}
}}
?>
