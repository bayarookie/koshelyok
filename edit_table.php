<?php
$tbl = isset($_POST['tbl']) ? $mysqli->real_escape_string($_POST['tbl']) : '';
if ($tbl == 'goods') {
	$title = 'Конторы';
	$th = '<tr><th><th>Наименование<th>Опер.<th>Группа<th>Описание';
} elseif ($tbl == 'groups') {
	$title = 'Группы';
	$th = '<tr><th><th>Наименование<th>Описание';
} elseif ($tbl == 'walls') {
	$title = 'Кошельки';
	$th = '<tr><th><th>Наименование<th>Описание';
} else {
	$title = 'Пользователи';
	$th = '<tr><th><th>Имя<th>Пароль<th>Наименование';
}
$t = '<p>' . $title . '
<input type="button" value="Добавить" onclick="get_form(\'edit_form\',-1,\'' . $tbl . '\')">
<input type="button" value="Закрыть" onclick="id_close(\'edit_table\')"></p>';
echo '<article>' . $t . '<table>' . $th;
if ($tbl == 'goods') {
	$result = byQu($mysqli, "SELECT goods.id, goods.name, COUNT(money.id) as cnt, groups.name as groups_name, goods.comment
		FROM goods
		LEFT JOIN groups ON goods.groups_id=groups.id
		LEFT JOIN money ON goods.id=money.goods_id
		GROUP BY goods.id
		ORDER BY groups.name, goods.name");
} else {
	$result = byQu($mysqli, "SELECT * FROM $tbl");
}
while ($row = $result->fetch_row()) {
	echo '<tr>';
	echo '<td class="edit" onclick="get_form(\'edit_form\',' . $row[0] . ',\'' . $tbl . '\')">Редактировать';
	echo '<td>' . $row[1];
	if ($tbl == 'goods') {
		echo '<td class="edit num" onclick="money_table(3,' . $row[0] . ')">' . $row[2];
		echo '<td>' . $row[3];
		echo '<td>' . $row[4];
	} elseif ($tbl == 'users') {
		echo '<td>' . $row[2];
		echo '<td>' . $row[3];
	} else {
		echo '<td>' . $row[2];
	}
}
echo '</table>';
if ($result->num_rows > 30) echo $t;
echo '</article>';
?>
