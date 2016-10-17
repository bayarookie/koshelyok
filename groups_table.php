<?php
echo "<div><h3>Группы</h3>";
echo '<table><tr><th><th>Наименование<th>Комментарий';
$query = 'SELECT * FROM groups ORDER BY name';
$result = byQu($mysqli, $query);
while ($row = $result->fetch_assoc()) {
	echo '<tr>';
	echo '<td><input type="button" value="Редактировать" onclick="groups_form(' . $row['id'] . ')" />';
	echo '<td>' . $row['name'];
	echo '<td>' . $row['comment'];
}
echo "</table>";
echo '<input type="button" value="Добавить" onclick="groups_form(-1)">';
echo '<input type="button" value="Закрыть" onclick="groups_table_close()"></div>';
?>
