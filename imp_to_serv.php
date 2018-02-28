<article><h1>Импорт контор</h1>
<input type="button" value="Закрыть" onclick="id_close('import_form')">
<?php
$impo = "INSERT INTO servs (name, grups_id, comment) VALUES
";
$i = 0;
while (isset($_POST["srv_$i"])) {
	if ($i > 0) $impo .= ",\n";
	list($nm, $t) = explode(";", $_POST["srv_$i"]);
	$impo .= "('$nm', -1, '')";
	$i++;
}
$impo .= ";";

if ($i === 0) {
	echo 'Нечего импортировать<br>';
} elseif ($mysqli->multi_query($impo)) {
	echo 'Список контор успешно импортирован<br>';
} else {
	echo 'Ошибка во время импортирования списка контор:<pre>' . $mysqli->error . '</pre>';
	echo "Запрос:<pre>$impo</pre>";
}
?>
</article>
