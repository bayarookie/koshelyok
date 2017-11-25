<article><h1><a href="">Импорт</a></h1>
<input type="button" value="Закрыть" onclick="id_close('import_form')">
<?php
$w_id = isset($_POST['w_id']) ? intval($_POST['w_id']) : 1; //1 = Сбербанк
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

function byTr($c2, $date, $nm, $summ) {
	global $mysqli, $w_id, $impo;
	if ($summ < 0) echo '<tr class="minus">'; else echo '<tr class="plus">';
	echo '<td class="num">' . $c2;
	echo '<td>' . $date;
	echo '<td>' . $nm;
	echo '<td class="num">' . number_format($summ, 2, '.', ' ');
	$g_id = -1;
	$nm = $mysqli->real_escape_string($nm);
	$result = byQu($mysqli, "SELECT id, name FROM goods WHERE name LIKE '$nm'");
	if ($row = $result->fetch_row()) {
		$g_id = $row[0];
		$name = $row[1];
	} else {
		$result = byQu($mysqli, "SELECT id, name FROM goods WHERE name LIKE '$nm%'");
		if ($result->num_rows == 1 && $row = $result->fetch_row()) {
			$g_id = $row[0];
			$name = $row[1];
		} elseif ($result->num_rows == 0) {
			$result = byQu($mysqli, "INSERT INTO goods (name, groups_id, comment) VALUES ('$nm', -1, '')");
			$result = byQu($mysqli, "SELECT id, name FROM goods WHERE name LIKE '$nm'");
			if ($row = $result->fetch_row()) {
				$g_id = $row[0];
				$name = $row[1];
			}
			$name = "не найден";
		}
	}
	echo '<td>' . $name;
	$result = byQu($mysqli, "SELECT id FROM money
	WHERE op_date=STR_TO_DATE('$date', '%Y-%m-%d') AND op_summ=$summ AND goods_id=$g_id AND walls_id=$w_id");
	if ($result->num_rows > 0 && $row = $result->fetch_row()) {
		echo '<td class="edit" onclick="get_form(\'edit_form\',' . $row[0] . ',\'money\')">Уже есть';
	} else {
		echo '<td>Новая тр';
		$impo .= "INSERT INTO money (op_date, op_summ, goods_id, comment, walls_id)
		VALUES (STR_TO_DATE('$date', '%Y-%m-%d'), $summ, $g_id, '', $w_id);\n";
	}
}


$c2 = 0;
$impo = '';

$path_parts = pathinfo($uploadfile);
echo $path_parts['extension'];

if ($fh = fopen($uploadfile,'r')) {
	echo '<table><tr><th>№<th>Дата<th>Имя<th>Сумма<th>найденное в БД';
	if ($path_parts['extension'] == 'csv') {
		$line = fgetcsv($fh, 1000, ';'); //header
		while ($line = fgetcsv($fh, 1000, ';')) {
			$c2++;
			//if (count($line) < 11) die('неформат');
			$date = date('Y-m-d', strtotime($line[2]));
			$nm = trim($line[8]);
			$summ = floatval($line[11]);
			byTr($c2, $date, $nm, $summ);
		}
	} elseif ($path_parts['extension'] == 'txt') {
		while ($line = fgets($fh)) {
			$ye = intval(substr($line, 31, 2));
			if ($ye == 0) continue;
			$c2++;
			$mo = byMo(mb_convert_encoding(substr($line, 22, 3), 'UTF-8', 'Windows-1251'));
			$date = '20' . $ye . '-' . $mo . '-' . substr($line, 20, 2);
			$nm = trim(mb_convert_encoding(substr($line, 41, 22), 'UTF-8', 'Windows-1251'));
			$summ = floatval(substr($line, 84, 11));
			if (substr($line, 95, 2) != 'CR') $summ = -$summ;
			byTr($c2, $date, $nm, $summ);
		}
	}
	fclose($fh);
	echo '</table>';
} else {
	echo 'Ошибка открытия файла: ' . $uploadfile . '<br>';
}
if ($impo == '') {
	echo 'Нечего импортировать<br>';
} elseif ($mysqli->multi_query($impo)) {
	echo 'Выписка успешно импортирована<br>';
} else {
	echo 'Ошибка во время импортирования выписки:<pre>' . $mysqli->error . '</pre>';
	echo 'Запрос:<pre>$impo</pre>';
}
?>
<input type="button" value="Закрыть" onclick="id_close('import_form')"></article>
