<?php
include 'db.php';
echo '<div><h3>Категории</h3>';
echo '<table><tr><th><th>Наименование<th>Группа<th>Комментарий';
$query = 'SELECT goods.*, groups.name as groups_name FROM goods'
		.' LEFT JOIN groups ON goods.groups_id=groups.id'
		.' ORDER BY groups.name, goods.name';
$result = byQu($mysqli, $query);
while ($row = $result->fetch_assoc()) {
	echo ' <tr>';
	echo '<td><input type="button" value="Редактировать" onclick="goods_form(' . $row['id'] . ')" />';
	echo '<td>' . $row['name'];
	echo '<td>' . $row['groups_name'];
	echo '<td>' . $row['comment'];
}
echo '</table>';
?>
<input type="button" value="Добавить" onclick="goods_form(-1)">
<input type="button" value="Закрыть" onclick="id_close('goods_table')"></div>
