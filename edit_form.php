<?php
$tbl = isset($_POST['tbl']) ? $mysqli->real_escape_string($_POST['tbl']) : 'goods';
$e_id = isset($_POST['id']) ? intval($_POST['id']) : -1;
$td = array('id', 'Наименование', 'Описание');
$ids = array('e_id', 'e_name', 'e_comment');
$row = array(-1, '', '');
if ($tbl == 'goods') {
	$title = 'Контора';
	$td = array('id', 'Наименование', 'Описание', 'Группа');
	$ids = array('e_id', 'e_name', 'e_comment', 'e_groups_id');
	$row = array(-1, '', '', -1);
} elseif ($tbl == 'groups') {
	$title = 'Группа';
} elseif ($tbl == 'walls') {
	$title = 'Кошелёк';
} elseif ($tbl == 'users') {
	$title = 'Пользователь';
	$td = array('id', 'Имя', 'Пароль', 'Наименование');
	$ids = array('e_id', 'e_username', 'e_password', 'e_name');
	$row = array(-1, '', '', '');
} else {
	$title = 'Транзакция';
	$td = array('id', 'Дата', 'Сумма', 'Контора', 'Кошелёк', 'Пользователь', 'Описание');
	$ids = array('e_id', 'e_op_date', 'e_op_summ', 'e_goods_id', 'e_walls_id', 'e_users_id', 'e_comment');
	$row = array(-1, date('Y-m-d'), 0, 0, 0, $user_id, '');
}
if ($e_id > -1) {
	$result = byQu($mysqli, "SELECT * FROM $tbl WHERE id=$e_id");
	$row = $result->fetch_row();
}
echo '<figure><figcaption>' . $title . '</figcaption><input type="hidden" id="e_id" value="' . $e_id . '">';
echo '<table class="form">';
for ($i = 1; $i < count($row); $i++) {
	if (substr($ids[$i],-3) == '_id') {
		$t = substr($ids[$i], 2, -3);
		echo '<tr><td>' . $td[$i] . '<td><select id="' . $ids[$i] . '" size="1">';
		echo '<option' . ((-1 == $row[$i]) ? ' selected' : '') . ' value="-1"></option>';
		$res2 = byQu($mysqli, "SELECT id, name FROM $t ORDER BY name");
		while ($ro2 = $res2->fetch_row())
			echo '<option' . (($ro2[0] == $row[$i]) ? ' selected' : '') . ' value="' . $ro2[0] . '">' . $ro2[1] . '</option>';
		echo '</select>';
	} elseif ($ids[$i] == 'e_op_date') {
		echo '<tr><td>' . $td[$i] . '<td><input type="date" id="' . $ids[$i] . '" value="' . $row[$i] . '">';
	} elseif ($ids[$i] == 'e_op_summ') {
		echo '<tr><td>' . $td[$i] . '<td><input type="number" id="' . $ids[$i] . '" value="' . $row[$i] . '" step="0.01">';
	} else {
		echo '<tr><td>' . $td[$i] . '<td><input type="text" id="' . $ids[$i] . '" value="' . $row[$i] . '" size="40">';
	}
}
echo '<tr><td><td><input type="button" value="Сохранить" onclick="edit_to_db(\'' . $tbl . '\')">
<input type="button" value="Отменить" onclick="id_close(\'edit_form\')"></table></figure>';
?>
