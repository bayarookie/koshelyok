<article><h1><a href="">Импорт</a></h1>
<input type="button" value="Закрыть" onclick="id_close('import_form')">
<?php
$impo = '';
$i = 0;
while (isset($_POST["imp_$i"])) {
	list($date, $summ, $s_id, $g_id, $w_id) = explode(";", $_POST["imp_$i"]);
	$impo .= "INSERT INTO money (op_date, op_summ, servs_id, grups_id, walls_id, users_id, comment)
	VALUES (STR_TO_DATE('$date', '%Y-%m-%d'), $summ, $s_id, $g_id, $w_id, $user_id, '');\n";
	$i++;
}

if ($impo == '') {
	echo 'Нечего импортировать<br>';
} elseif ($mysqli->multi_query($impo)) {
	echo 'Выписка успешно импортирована<br>';
} else {
	echo 'Ошибка во время импортирования выписки:<pre>' . $mysqli->error . '</pre>';
	echo "Запрос:<pre>$impo</pre>";
}
?>
</article>
