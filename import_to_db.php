<?php
include 'db.php';
echo '<article><h1><a href="">Импорт</a></h1>';
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

function byFg($mysqli, $name) {
	$g_id = -1;
	$name = $mysqli->real_escape_string($name);
	$result = byQu($mysqli, "SELECT id FROM goods WHERE name LIKE '$name'");
	if ($row = $result->fetch_row()) {
		$g_id = $row[0];
	}
	return $g_id;
}

$c1 = 0;
$c2 = 0;
$good = '';
$impo = '';
if ($fh = fopen($uploadfile,'r')) {
	echo '<table>';
	$line = fgetcsv($fh, 1000, ';'); //header
	while ($line = fgetcsv($fh, 1000, ';')) {
		//if (count($line) < 11) die('неформат');
		$date = date('Y-m-d', strtotime($line[2]));
		$nm = trim($line[8]);
		$summ = floatval($line[11]);
		if ($summ < 0) echo '<tr class="minus">'; else echo '<tr class="plus">';
		echo "<td>" . $date;
		echo "<td>" . $nm;
		echo "<td align=right>" . number_format($summ,2,'.',' ');
		$g_id = byFg($mysqli, $nm);
		if ($g_id < 0) {
			$good .= "INSERT INTO goods (name, groups_id, comment) VALUES ('$nm', -1, '');\n";
		}
		$result = byQu($mysqli,
			"SELECT id FROM money
				WHERE op_date=STR_TO_DATE('$date', '%Y-%m-%d') AND op_summ=$summ AND goods_id=$g_id AND walls_id=$w_id");
		if ($result->num_rows > 0) $c1++;
		$impo .= "INSERT INTO money (op_date, op_summ, goods_id, comment, walls_id)
	VALUES (STR_TO_DATE('$date', '%Y-%m-%d'), $summ, $g_id, '', $w_id);\n";
		$c2++;
	}
	fclose($fh);
	echo '</table>';
} else {
	echo 'Ошибка открытия файла: ' . $uploadfile;
}
if ($good !== '') {
	echo "Неизвестные категории:<pre>$good</pre>";
} elseif ($c1 > 0) {
	echo 'Есть похожие записи: ' . $c1 . ' из ' . $c2;
} elseif ($mysqli->multi_query($impo)) {
	echo 'Выписка успешно импортирована';
} else {
	echo "Ошибка во время импортирования выписки:<pre>" . $mysqli->error . "</pre>";
	echo "Запрос:<pre>$impo</pre>";
}
?>
<input type="button" value="Закрыть" onclick="id_close('import_form')"></article>
