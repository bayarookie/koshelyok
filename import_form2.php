<?php
$w_id = intval($_POST['i_walls_id'] ?? $wall_id);
$uploadfile = '/tmp/' . basename($_FILES['bankstate']['name']);
if (move_uploaded_file($_FILES['bankstate']['tmp_name'], $uploadfile)) {
	$s = 'Файл корректен и был успешно загружен.';
} else {
	$s = 'Возможная атака с помощью файловой загрузки!';
}
$path_parts = pathinfo($uploadfile);
if ($debug) {
echo '<pre>';
echo $s . '
Некоторая отладочная информация:';
print_r($_FILES);
echo $path_parts['extension'];
echo '</pre>';
}
function byMo($m) {
	switch ($m) {
		case "ЯНВ": return "01";
		case "ФЕВ": return "02";
		case "МАР": return "03";
		case "АПР": return "04";
		case "МАЙ": return "05";
		case "ИЮН": return "06";
		case "ИЮЛ": return "07";
		case "АВГ": return "08";
		case "СЕН": return "09";
		case "ОКТ": return "10";
		case "НОЯ": return "11";
		case "ДЕК": return "12";
	}
}

function byTr($date, $nm, $summ) {
	if ($summ === 0.00) return;
	global $c2, $sr, $mysqli, $w_id, $tb;
	$tb .= '<tr class="' . (($summ < 0) ? 'minus' : 'plus') . '">';
	$s_id = -1;
	$name = 'не найден';
	$nm = $mysqli->real_escape_string($nm);
	$result = byQu("SELECT id, name FROM servs WHERE name LIKE '$nm'");
	if ($row = $result->fetch_row()) {
		$s_id = $row[0];
		$name = $row[1];
	} else {
		$result = byQu("SELECT id, name FROM servs WHERE name LIKE '$nm%'");
		if (($result->num_rows == 1) && ($row = $result->fetch_row())) {
			$s_id = $row[0];
			$name = $row[1];
		} elseif ($result->num_rows == 0) {
			if (!in_array($nm, $sr)) $sr[] = $nm;
		}
	}
	$result = byQu("SELECT id FROM money
	WHERE op_date=STR_TO_DATE('$date', '%Y-%m-%d') AND op_summ=$summ AND servs_id=$s_id");
	if ($result->num_rows > 0 && $row = $result->fetch_row()) {
		$chkd = '';
		$trnz = '<td class="edit" onclick="get_form(\'edit_form\',' . $row[0] . ',\'money\')">Уже есть';
	} else {
		$chkd = ' checked';
		$trnz = '<td>Новая тр';
	}
	$arr = $date . ";" . $summ . ";" . $s_id . ";" . $w_id;
	$tb .= '<td><input type="checkbox" id="imp_' . $c2 . '" value="' . $arr . '"' . $chkd . '>';
	$tb .= '<td class="num">' . $c2;
	$tb .= '<td>' . $date;
	$tb .= '<td>' . $nm;
	$tb .= '<td class="num">' . number_format($summ, 2, '.', ' ');
	$tb .= '<td>' . $name;
	$tb .= $trnz;
}

$c2 = 0;
$tb = '';
$sr = array();

if ($fh = fopen($uploadfile,'r')) {
	if ($path_parts['extension'] == 'csv') {
		while ($line = fgetcsv($fh, 1000, ';')) {
			$c2++;
			if (count($line) == 6) { //Yandex money
				list($nm, $t) = explode(": ", trim($line[5]));
				$nm = str_replace(array('Перевод на счет ', 'Перевод от ', 'Возврат:', ', пополнение'), '', $nm);
				$summ = floatval(str_replace(',', '.', $line[2]));
				if ($line[0] == '-') $summ = -$summ;
				byTr(date('Y-m-d', strtotime($line[1])), $nm, $summ);
			} elseif (count($line) == 13) { //Сбербанк онлайн
				byTr(date('Y-m-d', strtotime($line[2])), trim($line[8]), floatval($line[11]));
			}
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
			byTr($date, $nm, $summ);
		}
	}
	fclose($fh);
} else {
	echo 'Ошибка открытия файла: ' . $uploadfile . '<br>';
}
$iMax = count($sr);
if ($iMax == 0) {
	echo '<table><tr><th><th>№<th>Дата<th>Имя<th>Сумма<th>Имя найденное в БД<th>Есть';
	echo $tb;
	echo '</table>';
	echo '<input type="button" value="Сохранить" onclick="import_to_db()">';
} else {
	echo '<table><tr><th><th>№<th>Имя';
	for ($i = 0 ; $i < $iMax; $i++) {
		echo '<tr><td><input type="checkbox" id="srv_' . ($i+1) . '" value="' . $sr[$i] . '" checked>';
		echo '<td class="num">' . ($i+1) . '<td>' . $sr[$i];
	}
	echo '</table>';
	echo '<input type="button" value="Сохранить" onclick="imp_to_serv()">';
}
?>
<input type="button" value="Закрыть" onclick="id_close('import_form')">
