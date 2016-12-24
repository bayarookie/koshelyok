<?php
include 'db.php';
if (isset($_POST['id'])) $m_id = intval($_POST['id']); else $m_id = -1;
$date = date('Y-m-d');
$summ = 0;
$g_id = -1;
$komm = '';
$w_id = -1;
if ($m_id > -1) {
	$result = byQu($mysqli, "SELECT * FROM money WHERE id=$m_id");
	if ($row = $result->fetch_assoc()) {
		$date = $row['op_date'];
		$summ = $row['op_summ'];
		$g_id = $row['goods_id'];
		$komm = $row['comment'];
		$w_id = $row['walls_id'];
	}
}

echo '<figure>';
echo '<p>Выберите дату: <input type="date" id="m_op_date" placeholder="Дата" value="' . $date . '"></p>';
echo '<p>Введите сумму: <input type="number" step="0.01" id="m_op_summ" placeholder="Сумма" value="' . $summ . '">';
echo '<input type="hidden" id="m_id" value="' . $m_id . '">';

echo '<p>Выберите категорию: <select size="1" id="m_goods_id">';
$result = byQu($mysqli,
	"SELECT goods.id, goods.name, groups.name as groups_name FROM goods
		LEFT JOIN groups ON goods.groups_id=groups.id
		ORDER BY groups.name, goods.name");
while ($row = $result->fetch_assoc()) {
	echo '<option';
	if ($row['id'] == $g_id) echo ' selected';
	echo ' value="' . $row['id'] . '">';
	if ($row['groups_name'] != '') echo $row['groups_name'] . ' - ';
	echo $row['name'] . '</option>';
}
echo '</select></p>';

echo '<p>Выберите кошелёк: <select size="1" id="m_walls_id">';
$result = byQu($mysqli, "SELECT id, name FROM walls");
while ($row = $result->fetch_assoc()) {
	echo '<option';
	if ($row['id'] == $w_id) {echo ' selected';}
	echo ' value="' . $row['id'] . '">' . $row['name'] . '</option>';
}
echo '</select></p>';

echo '<p>Комментарий<br>';
echo '<textarea id="m_comment" cols="40" rows="3" maxlength="1000" placeholder="Комментарий">' . $komm . '</textarea></p>';
?>
<p><input type="button" value="Сохранить" onclick="money_to_db()">
<input type="reset" value="Очистить">
<input type="button" value="Закрыть" onclick="id_close('money_form')"></p></figure>
