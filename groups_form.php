<?php
include 'db.php';
$r_id = -1;
$name = '';
$komm = '';
if (isset($_POST['r_id'])) {
	$r_id = intval($_POST['r_id']);
	$query = 'SELECT * FROM groups WHERE id=' . $r_id;
	$result = byQu($mysqli, $query);
	if ($row = $result->fetch_assoc()) {
		$name = $row['name'];
		$komm = $row['comment'];
	}
}

echo '<div><input type="hidden" name="r_id" id="r_id" value="' . $r_id . '">';
echo '<p>Введите имя: <input type="text" name="r_name" id="r_name" placeholder="Наименование" value="' . $name . '"></p>';
echo '<p>Комментарий<br>';
echo '<textarea name="r_comment" id="r_comment" cols="40" rows="3" maxlength="1000" placeholder="Комментарий">' . $komm . '</textarea></p>';
?>
<p><input type="button" value="Отправить" onclick="groups_to_db()">
<input type="reset" value="Очистить">
<input type="button" value="Закрыть" onclick="id_close('groups_form')"></p></div>
