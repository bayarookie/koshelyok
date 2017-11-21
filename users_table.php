<article>
<p>Пользователи
<input type="button" value="Добавить" onclick="get_form('user_form',-1)">
<input type="button" value="Закрыть" onclick="id_close('users_table')"></p>
<table><tr><th><th>Имя<th>Пароль<th>Наименование
<?php
$result = byQu($mysqli, "SELECT * FROM users");
while ($row = $result->fetch_assoc()) {
	echo ' <tr>';
	echo '<td class="edit" onclick="get_form(\'users_form\',' . $row['id'] . ')">Редактировать';
	echo '<td>' . $row['username'];
	echo '<td>' . $row['password'];
	echo '<td>' . $row['name'];
}
?>
</table>
<p>Пользователи
<input type="button" value="Добавить" onclick="get_form('user_form',-1)">
<input type="button" value="Закрыть" onclick="id_close('users_table')"></p>
