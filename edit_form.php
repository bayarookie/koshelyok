<?php
$tbl = isset($_POST['tbl']) ? $mysqli->real_escape_string($_POST['tbl']) : '';
if ($tbl == '') die('table?');
$e_id = isset($_POST['id']) ? intval($_POST['id']) : -1;
$table = $tbl;
if ($tbl == 'servs_v') {
	$title = 'Контора';
	$td = array('id', 'Наименование', 'Описание', 'Подгруппа');
	$table = 'servs';
} elseif ($tbl == 'grups_v') {
	$title = 'Подгруппа';
	$td = array('id', 'Наименование', 'Описание', 'Группа');
	$table = 'grups';
} elseif ($tbl == 'bgrup') {
	$title = 'Группа';
	$td = array('id', 'Наименование', 'Описание');
} elseif ($tbl == 'walls') {
	$title = 'Кошелёк';
	$td = array('id', 'Наименование', 'Описание');
} elseif ($tbl == 'users') {
	$title = 'Пользователь';
	$td = array('id', 'Имя', 'Пароль', 'Наименование');
} elseif ($tbl == 'money_order') {
	$title = 'Сортировка';
	$td = array('id', 'Наименование', 'ORDER BY');
} else {
	$title = 'Транзакция';
	$td = array('id', 'Дата', 'Сумма', 'Подгруппа', 'Контора', 'Кошелёк', 'Пользователь', 'Описание');
}
echo '<figure><figcaption>' . $title . '</figcaption><table class="form">';
if ($e_id >= 0)
	$result = byQu("SELECT * FROM $table WHERE id=$e_id");
else
	$result = byQu("SELECT * FROM $table LIMIT 1");
$row = $result->fetch_row();
$finfo = $result->fetch_fields();
for ($i = 1; $i < count($finfo); $i++) {
	if (substr($finfo[$i]->name,-3) == '_id') {
		if ($e_id < 0) {
			$row[$i] = -1;
			if ($tbl == 'money') {
				if ($finfo[$i]->name == 'users_id') {$row[$i] = $user_id;}
				if ($finfo[$i]->name == 'walls_id') {$row[$i] = 1;} //доделать
			}
		}
		bySe($td[$i], 'e_' . $finfo[$i]->name, substr($finfo[$i]->name, 0, -3), $row[$i], '');
	} else {
		if ($e_id < 0) {$row[$i] = '';}
		echo '<tr><td>' . $td[$i] . '<td>';
		if ($finfo[$i]->type == 10) {
			echo '<input type="date"';
			if ($e_id < 0) $row[$i] = date('Y-m-d');
		} elseif ($finfo[$i]->type == 246) {
			echo '<input type="number" step="0.01"';
		} elseif ($finfo[$i]->name == 'password') {
			echo '<input type="password"';
		} else {
			echo '<input type="text" size="45"';
		}
		echo ' id="e_' . $finfo[$i]->name . '" value="' . $row[$i] . '">';
	}
}
echo '<tr><td><td><input type="button" value="Сохранить" onclick="edit_to_db(\'' . $tbl . '\')">
<input type="button" value="Отменить" onclick="id_close(\'edit_form\')"></table>
<input type="hidden" id="e_id" value="' . $e_id . '"></figure>';
?>
<script id="combojs">$(".combobox").combobox();</script>
