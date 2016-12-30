<?php
include 'db.php';
//$tbl = $_POST['tbl'] ?? 'goods';
$tbl = isset($_POST['tbl']) ? $mysqli->real_escape_string($_POST['tbl']) : '';
$title = $tbl == 'goods' ? 'Категории' : ($tbl == 'groups' ? 'Группы' : 'Кошельки');
echo '<article>
<p>' . $title . '
<input type="button" value="Добавить" onclick="get_form(\'edit_form\',-1,\'' . $tbl . '\')">
<input type="button" value="Закрыть" onclick="id_close(\'edit_table\')"></p>
<table><tr><th><th>Наименование';
if ($tbl == 'goods') {
	echo '<th>Опер.<th>Группа';
	$result = byQu($mysqli, "SELECT goods.*, groups.name as groups_name, COUNT(money.id) as cnt
		FROM goods
		LEFT JOIN groups ON goods.groups_id=groups.id
		LEFT JOIN money ON goods.id=money.goods_id
		GROUP BY goods.id
		ORDER BY groups.name, goods.name");
} else {
	$result = byQu($mysqli, "SELECT * FROM $tbl");
}
echo '<th>Комментарий';
while ($row = $result->fetch_assoc()) {
	echo ' <tr>';
	echo '<td class="edit" onclick="get_form(\'edit_form\',' . $row['id'] . ',\'' . $tbl . '\')">Редактировать';
	if ($tbl == 'goods') {
		echo '<td>' . $row['name'];
		echo '<td class="edit" onclick="money_table(3, ' . $row['id'] . ')" align="right">' . $row['cnt'];
		echo '<td>' . $row['groups_name'];
	} else {
		echo '<td>' . $row['name'];
	}
	echo '<td>' . $row['comment'];
}
echo '</table>
<p>' . $title . '
<input type="button" value="Добавить" onclick="get_form(\'edit_form\',-1,\'' . $tbl . '\')">
<input type="button" value="Закрыть" onclick="id_close(\'edit_table\')"></p>
</article>';
?>
