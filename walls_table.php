<?php
include 'db.php';
echo "<article><h3>Кошельки</h3>";
echo '<table><tr><th><th>Наименование<th>Комментарий';
$query = "SELECT * FROM walls";
$result = byQu($mysqli, $query);
while ($row = $result->fetch_assoc()) {
	echo '<tr>';
	echo '<td><input type="button" value="Редактировать" onclick="get_form(\'walls_form\', ' . $row['id'] . ')">';
	echo '<td>' . $row['name'];
	echo '<td>' . $row['comment'];
}
echo "</table>";
?>
<input type="button" value="Добавить" onclick="get_form('walls_form', -1)">
<input type="button" value="Закрыть" onclick="id_close('walls_table')"></article>
