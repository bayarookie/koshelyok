<?php
include 'db.php';
echo '<div><h1><a href="">Импорт</a></h1>';
$w_id = 1;
echo '<p>Выберите кошелёк: <select size="1" id="w_id">';
$query = "SELECT id, name FROM walls";
$result = byQu($mysqli, $query);
while ($row = $result->fetch_assoc()) {
	echo '<option';
	if ($row['id'] == $w_id) {echo ' selected';}
	echo ' value="' . $row['id'] . '">' . $row['name'] . '</option>';
}
echo '</select></p>';
?>
<p>Выбрать файл: <input type="file" id="i_file">
<input type="button" value="Отправить этот файл" onclick="import_to_db()"></p>
<input type="button" value="Закрыть" onclick="id_close('import_form')">
</div>
