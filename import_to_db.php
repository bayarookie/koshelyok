<article><h1>Импорт</h1>
<input type="button" value="Закрыть" onclick="id_close('import_form')">
<?php
$impo = "INSERT INTO money (op_date, op_summ, servs_id, grups_id, walls_id, users_id, comment) VALUES
";
$i = 0;
while (isset($_POST["imp_$i"])) {
	if ($i > 0) $impo .= ",\n";
	list($date, $summ, $s_id, $g_id, $w_id) = explode(";", $_POST["imp_$i"]);
	$impo .= "(STR_TO_DATE('$date', '%Y-%m-%d'), $summ, $s_id, $g_id, $w_id, $user_id, '')";
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
