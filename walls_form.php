<?php
include 'db.php';
$w_id = -1;
$name = '';
$komm = '';
if (isset($_POST['id'])) {
	$w_id = intval($_POST['id']);
	$query = "SELECT * FROM walls WHERE id=" . $w_id;
	$result = byQu($mysqli, $query);
	if ($row = $result->fetch_assoc()) {
		$name = $row['name'];
		$komm = $row['comment'];
	}
}

echo '<figure><input type="hidden" id="w_id" value="' . $w_id . '">';
echo '<p>Введите имя: <input type="text" id="w_name" placeholder="Наименование" value="' . $name . '"></p>';
echo '<p>Комментарий<br>';
echo '<textarea id="w_comment" cols="40" rows="3" maxlength="1000" placeholder="Комментарий">' . $komm . '</textarea></p>';
?>
<p><input type="button" value="Отправить" onclick="walls_to_db()">
<input type="reset" value="Очистить">
<input type="button" value="Закрыть" onclick="id_close('walls_form')"></p></figure>
