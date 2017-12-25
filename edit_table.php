<?php
$tbl = isset($_POST['tbl']) ? $mysqli->real_escape_string($_POST['tbl']) : '';
if ($tbl == '') die('table?');
$table = $tbl;
$th = '<tr><th><th>Наименование<th>Описание';
if ($tbl == 'goods_v') {
	$title = 'Конторы';
	$th .= '<th>Группа<th>Опер.';
	$table = 'goods';
} elseif ($tbl == 'groups') {
	$title = 'Группы';
} elseif ($tbl == 'walls') {
	$title = 'Кошельки';
} elseif ($tbl == 'money_order') {
	$title = 'Сортировка';
	$th = '<tr><th><th>Наименование<th>ORDER BY';
} else {
	$title = 'Пользователи';
	$th = '<tr><th><th>Имя<th>Пароль<th>Наименование';
}
$t = '<p>' . $title . '
<input type="button" value="Добавить" onclick="get_form(\'edit_form\',-1,\'' . $table . '\')">
<input type="button" value="Закрыть" onclick="id_close(\'edit_table\')"></p>';
echo '<article>' . $t . '<table>' . $th;
$result = byQu($mysqli, "SELECT * FROM $tbl");
$imax = $result->field_count;
while ($row = $result->fetch_row()) {
	echo '<tr><td class="edit" onclick="get_form(\'edit_form\',' . $row[0] . ',\'' . $table . '\')">Редактировать';
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
