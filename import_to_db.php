<article><h1>Импорт операций</h1>
<input type="button" value="Закрыть" onclick="id_close('import_form')">
<?php
$impo = "INSERT INTO money (op_date, op_summ, servs_id, walls_id) VALUES
";
$i = 0;
while (isset($_POST["imp_$i"])) {
	if ($i > 0) $impo .= ",\n";
	list($date, $summ, $s_id, $w_id) = explode(";", $mysqli->real_escape_string($_POST["imp_$i"]));
	$impo .= "(STR_TO_DATE('$date', '%Y-%m-%d'), $summ, $s_id, $w_id)";
	$i++;
}
$impo .= ";";

if ($i === 0) {
	echo 'Нечего импортировать<br>';
} elseif ($mysqli->multi_query($impo)) {
	echo 'Выписка успешно импортирована<br>';
} else {
	echo 'Ошибка во время импортирования выписки:<pre>' . $mysqli->error . '</pre>';
	echo "Запрос:<pre>$impo</pre>";
}
?>
</article>
