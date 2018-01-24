<?php
$tbl = isset($_POST['tbl']) ? $mysqli->real_escape_string($_POST['tbl']) : '';
if ($tbl == '') die('table?');
$th = '<tr><th>№<th><th>Наименование<th>Описание';
if ($tbl == 'servs_v') {
	$title = 'Конторы';
	$th .= '<th>Подгруппа<th>Опер.';
} elseif ($tbl == 'grups_v') {
	$title = 'Подгруппы';
	$th .= '<th>Группа';
} elseif ($tbl == 'bgrup') {
	$title = 'Группы';
} elseif ($tbl == 'walls') {
	$title = 'Кошельки';
} elseif ($tbl == 'money_order') {
	$title = 'Сортировка';
	$th = '<tr><th>№<th><th>Наименование<th>ORDER BY<th>Описание';
} else {
	$title = 'Пользователи';
	$th = '<tr><th>№<th><th>Имя<th>Пароль<th>Наименование<th>Описание';
}
$t = '<p>' . $title . '
<input type="button" value="Добавить" onclick="get_form(\'edit_form\',-1,\'' . $tbl . '\')">
<input type="button" value="Закрыть" onclick="id_close(\'edit_table\')"></p>';
echo '<article>' . $t . '<table>' . $th;
$result = byQu("SELECT * FROM $tbl");
$imax = $result->field_count;
$cnt = 0;
while ($row = $result->fetch_row()) {
	$cnt++;
	echo '<tr><td>' . $cnt . '<td class="edit" onclick="get_form(\'edit_form\',' . $row[0] . ',\'' . $tbl . '\')">Редактировать';
	for ($i = 1; $i < $imax; $i++) {
		if ($i == 4) {
			echo '<td class="num' . ((intval($row[$i]) > 0) ? ' edit" onclick="money_table(3,' . $row[0] . ')' : '') . '">' . $row[$i];
		} elseif (($i == 2) && ($tbl == 'users')) {
			echo '<td>*******';
		} else echo '<td>' . $row[$i];
	}
}
echo '</table>';
if ($result->num_rows > 30) echo $t;
echo '</article>';
?>
