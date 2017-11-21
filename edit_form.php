<?php
$tbl = isset($_POST['tbl']) ? $mysqli->real_escape_string($_POST['tbl']) : 'goods';
$title = $tbl == 'goods' ? 'Конторы' : ($tbl == 'groups' ? 'Группы' : 'Кошельки');
$e_id = -1;
$name = '';
$komm = '';
$r_id = -1;
if (isset($_POST['id'])) {
	$e_id = intval($_POST['id']);
	$result = byQu($mysqli, "SELECT * FROM $tbl WHERE id=$e_id");
	if ($row = $result->fetch_assoc()) {
		$name = $row['name'];
		$komm = $row['comment'];
		$r_id = $row['groups_id'] ?? -1;
	}
}
echo '<figure><figcaption>' . $title . '</figcaption><input type="hidden" id="e_id" value="' . $e_id . '">';
echo '<table class="form"><tr><td>Введите имя:<td><input type="text" id="e_name" placeholder="Наименование" value="' . $name . '">';
if ($tbl == 'goods') {
	echo '<tr><td>Выберите группу:<td><select size="1" id="e_groups_id">';
	echo '<option';
	if (-1 == $r_id) echo ' selected';
	echo ' value="-1">без группы</option>';
	$result = byQu($mysqli, "SELECT id, name FROM groups ORDER BY name");
	while ($row = $result->fetch_assoc()) {
		echo '<option';
		if ($row['id'] == $r_id) echo ' selected';
		echo ' value="' . $row['id'] . '">' . $row['name'] . '</option>';
	}
	echo '</select>';
} else {
	echo '<input type="hidden" id="e_groups_id" value="-1">';
}
echo '<tr><td><td><textarea id="e_comment" cols="40" rows="3" maxlength="1000" placeholder="Описание">' . $komm . '</textarea></p>';
echo '<tr><td><td><input type="button" value="Отправить" onclick="edit_to_db(\'' . $tbl . '\')">';
?>
<input type="reset" value="Очистить">
<input type="button" value="Закрыть" onclick="id_close('edit_form')"></table></figure>
