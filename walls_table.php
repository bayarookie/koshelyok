<?php include 'db.php';?>
<article><p>Кошельки
<input type="button" value="Добавить" onclick="get_form('walls_form', -1)">
<input type="button" value="Закрыть" onclick="id_close('walls_table')"></p>
<table><tr><th><th>Наименование<th>Комментарий
<?php
$result = byQu($mysqli, "SELECT * FROM walls");
while ($row = $result->fetch_assoc()) {
	echo '<tr>';
	echo '<td class="edit" onclick="get_form(\'walls_form\', ' . $row['id'] . ')">Редактировать';
	echo '<td>' . $row['name'];
	echo '<td>' . $row['comment'];
}
?>
</table></article>
