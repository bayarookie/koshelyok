<?php include 'db.php';?>
<article><p>Группы
<input type="button" value="Добавить" onclick="get_form('groups_form', -1)">
<input type="button" value="Закрыть" onclick="id_close('groups_table')"></p>
<table><tr><th><th>Наименование<th>Комментарий
<?php
$result = byQu($mysqli, "SELECT * FROM groups ORDER BY name");
while ($row = $result->fetch_assoc()) {
	echo '<tr>';
	echo '<td class="edit" onclick="get_form(\'groups_form\', ' . $row['id'] . ')">Редактировать';
	echo '<td>' . $row['name'];
	echo '<td>' . $row['comment'];
}
?>
</table></article>
