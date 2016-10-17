<?php
include 'db.php';
echo '<div><h1><a href="">Импорт</a></h1>';
if (isset($_POST['w_id'])) {$w_id = intval($_POST['w_id']);} else {$w_id = 1;} //1 = Сбербанк
$uploadfile = '/tmp/' . basename($_FILES['bankstate']['name']);
echo '<pre>';
if (move_uploaded_file($_FILES['bankstate']['tmp_name'], $uploadfile)) {
	echo "Файл корректен и был успешно загружен.\n";
} else {
	echo "Возможная атака с помощью файловой загрузки!\n";
}
echo 'Некоторая отладочная информация:';
print_r($_FILES);
echo '</pre>';

function byMo($m) {
	switch ($m) {
		case "ЯНВ":
			return "01";
		case "ФЕВ":
			return "02";
		case "МАР":
			return "03";
		case "АПР":
			return "04";
		case "МАЙ":
			return "05";
		case "ИЮН":
			return "06";
		case "ИЮЛ":
			return "07";
		case "АВГ":
			return "08";
		case "СЕН":
			return "09";
		case "ОКТ":
			return "10";
		case "НОЯ":
			return "11";
		case "ДЕК":
			return "12";
	}
}

function byFg($mysqli, $name) {
	$g_id = -1;
	$name = $mysqli->real_escape_string($name);
	$query = "SELECT id FROM goods WHERE name LIKE '$name'";
	$result = byQu($mysqli, $query);
	if ($row = $result->fetch_row()) {
		$g_id = $row[0];
	}
	return $g_id;
}

$c1 = 0;
$c2 = 0;
$impo = '';
echo '<table>';
$fh = fopen($uploadfile,'r');
while ($line = fgets($fh)) {
	$ye = intval(substr($line, 31, 2));
	if ($ye > 0) {
		$mo = byMo(mb_convert_encoding(substr($line, 22, 3), 'UTF-8', 'Windows-1251'));
		$date = '20' . $ye . '-' . $mo . '-' . substr($line, 20, 2);
		$nm = trim(mb_convert_encoding(substr($line, 41, 22), 'UTF-8', 'Windows-1251'));
		$summ = floatval(substr($line, 84, 11));
		if (substr($line, 95, 2) != 'CR') $summ = -$summ;
		echo '<tr><td>' . $date . '<td>' . $nm . '<td>' . $summ;
		$g_id = byFg($mysqli, $nm);
		if ($g_id < 0) {
			$query = "INSERT INTO goods (name, groups_id) VALUES ('$nm', -1)";
			byQu($mysqli, $query);
		}
		$g_id = byFg($mysqli, $nm);
		if ($g_id < 0) {
			die('какая то непонятная ошибка');
		} else {
			$query = "SELECT id FROM money"
					." WHERE op_date=STR_TO_DATE('$date', '%Y-%m-%d') AND op_summ=$summ AND goods_id=$g_id AND walls_id=$w_id";
			$result = byQu($mysqli, $query);
			if ($result->num_rows > 0) $c1++;
			$impo .= "INSERT INTO money (op_date, op_summ, goods_id, comment, walls_id)"
					." VALUES (STR_TO_DATE('$date', '%Y-%m-%d'), $summ, $g_id, '', $w_id);\n";
		}
		echo '<br>';
		$c2++;
	}
}
fclose($fh);
echo '</table>';
if ($c1 > 0) {
	echo 'Есть похожие записи: ' . $c1 . ' из ' . $c2;
} elseif ($mysqli->multi_query($impo)) {
	echo 'Выписка импортирована';
} else {
	echo "Ошибка во время импортирования выписки:<pre>" . $mysqli->error . "</pre>";
}
//echo '<pre>' . print_r($_POST) . '</pre>';
//echo '<pre>' . print_r($_FILES) . '</pre>';
echo '<input type="button" value="Закрыть" onclick="import_form_close()"></div>';
?>
