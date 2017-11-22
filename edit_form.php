<?php
$tbl = isset($_POST['tbl']) ? $mysqli->real_escape_string($_POST['tbl']) : 'goods';
$e_id = isset($_POST['id']) ? intval($_POST['id']) : -1;
if ($tbl == 'goods') {
	$title = 'Конторы';
	$td = array('id', 'Наименование', 'Описание', 'Группа');
	$ids = array('e_id', 'e_name', 'e_comment', 'e_groups_id');
	$row = array(-1, '', '', -1);
} elseif ($tbl == 'groups') {
	$title = 'Группы';
	$td = array('id', 'Наименование', 'Описание');
	$ids = array('e_id', 'e_name', 'e_comment');
	$row = array(-1, '', '');
} elseif ($tbl == 'walls') {
	$title = 'Кошельки';
	$td = array('id', 'Наименование', 'Описание');
	$ids = array('e_id', 'e_name', 'e_comment');
	$row = array(-1, '', '');
} else {
	$title = 'Пользователи';
	$td = array('id', 'Имя', 'Пароль', 'Наименование');
	$ids = array('e_id', 'e_username', 'e_password', 'e_name');
	$row = array(-1, '', '', '');
}
if ($e_id > -1) {
	$result = byQu($mysqli, "SELECT * FROM $tbl WHERE id=$e_id");
	$row = $result->fetch_row();
}
echo '<figure><figcaption>' . $title . '</figcaption><input type="hidden" id="e_id" value="' . $e_id . '">';
echo '<table class="form">';
for ($i = 1; $i < count($row); $i++) {
	if (($i == 3) && ($tbl == 'goods')) {
		echo '<tr><td>Выберите группу:<td><select size="1" id="e_groups_id">';
		echo '<option';
		if (-1 == $row[3]) echo ' selected';
		echo ' value="-1">без группы</option>';
		$res2 = byQu($mysqli, "SELECT id, name FROM groups ORDER BY name");
		while ($ro2 = $res2->fetch_row()) {
			echo '<option';
			if ($ro2[0] == $row[3]) echo ' selected';
			echo ' value="' . $ro2[0] . '">' . $ro2[1] . '</option>';
		}
		echo '</select>';
	} else {
		echo '<tr><td>' . $td[$i] . '<td><input type="text" size="40" id="' . $ids[$i] . '" value="' . $row[$i] . '">';
	}
}
echo '<tr><td><td><input type="button" value="Отправить" onclick="edit_to_db(\'' . $tbl . '\')">
<input type="button" value="Закрыть" onclick="id_close(\'edit_form\')"></table></figure>';
?>
