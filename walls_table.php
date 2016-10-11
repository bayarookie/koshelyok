<?php
echo "<div><h3>Кошельки</h3>";
echo '<table><tr><th><th>Наименование<th>Комментарий';
$query = "SELECT * FROM walls";
$result = byQu($mysqli, $query);
while ($row = $result->fetch_assoc()) {
	echo '<tr>';
	echo '<td><input type="button" value="Редактировать" onclick="walls_form(' . $row['id'] . ')">';
	echo '<td>' . $row['name'];
	echo '<td>' . $row['comment'];
}
echo "</table>";
echo '<input type="button" value="Добавить" onclick="walls_form(-1)">';
echo '<input type="button" value="Закрыть" onclick="walls_table_close()"></div>';
?>
