<?php
include 'db.php';
$g_id = -1;
$name = '';
$komm = '';
$r_id = -1;
if (isset($_POST['g_id'])) {
	$g_id = intval($_POST['g_id']);
	$query = 'SELECT * FROM goods WHERE id=' . $g_id;
	$result = byQu($mysqli, $query);
	if ($row = $result->fetch_assoc()) {
		$name = $row['name'];
		$komm = $row['comment'];
		$r_id = $row['groups_id'];
	}
}

echo '<div><input type="hidden" name="g_id" id="g_id" value="' . $g_id . '">';
echo '<p>Введите имя: <input type="text" name="g_name" id="g_name" placeholder="Наименование" value="' . $name . '"></p>';
echo '<p>Выберите группу: <select size="1" name="g_groups_id" id="g_groups_id">';
echo '<option';
if (-1 == $r_id) echo ' selected';
echo ' value="-1">без группы</option>';
$query = "SELECT id, name FROM groups ORDER BY name";
$result = byQu($mysqli, $query);
while ($row = $result->fetch_assoc()) {
	echo '<option';
	if ($row['id'] == $r_id) echo ' selected';
	echo ' value="' . $row['id'] . '">' . $row['name'] . '</option>';
}
echo '</select></p>';
echo '<p>Комментарий<br>';
echo '<textarea name="g_comment" id="g_comment" cols="40" rows="3" maxlength="1000" placeholder="Комментарий">' . $komm . '</textarea></p>';
?>
<p><input type="button" value="Отправить" onclick="goods_to_db()">
<input type="reset" value="Очистить">
<input type="button" value="Редактировать группы" onclick="groups_table()">
<input type="button" value="Закрыть" onclick="id_close('goods_form')"></p></div>
