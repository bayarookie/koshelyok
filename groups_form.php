<?php
include 'db.php';
$r_id = -1;
$name = '';
$komm = '';
if (isset($_POST['id'])) {
	$r_id = intval($_POST['id']);
	$result = byQu($mysqli, "SELECT * FROM groups WHERE id=$r_id");
	if ($row = $result->fetch_assoc()) {
		$name = $row['name'];
		$komm = $row['comment'];
	}
}

echo '<figure><input type="hidden" id="r_id" value="' . $r_id . '">';
echo '<p>Введите имя: <input type="text" id="r_name" placeholder="Наименование" value="' . $name . '"></p>';
echo '<p>Комментарий<br>';
echo '<textarea id="r_comment" cols="40" rows="3" maxlength="1000" placeholder="Комментарий">' . $komm . '</textarea></p>';
?>
<p><input type="button" value="Отправить" onclick="groups_to_db()">
<input type="reset" value="Очистить">
<input type="button" value="Закрыть" onclick="id_close('groups_form')"></p></figure>
