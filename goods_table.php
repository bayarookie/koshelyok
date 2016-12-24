<?php include 'db.php';?>
<article>
<p>Категории
<input type="button" value="Добавить" onclick="get_form('goods_form', -1)">
<input type="button" value="Закрыть" onclick="id_close('goods_table')"></p>
<table><tr><th><th>Наименование<th>Группа<th>Комментарий
<?php
$result = byQu($mysqli,
	"SELECT goods.*, groups.name as groups_name
		FROM goods LEFT JOIN groups ON goods.groups_id=groups.id
		ORDER BY groups.name, goods.name");
while ($row = $result->fetch_assoc()) {
	echo ' <tr>';
	echo '<td class="edit" onclick="get_form(\'goods_form\', ' . $row['id'] . ')">Редактировать';
	echo '<td>' . $row['name'];
	echo '<td>' . $row['groups_name'];
	echo '<td>' . $row['comment'];
}
?>
</table>
<p>Категории
<input type="button" value="Добавить" onclick="get_form('goods_form', -1)">
<input type="button" value="Закрыть" onclick="id_close('goods_table')"></p>
</article>
