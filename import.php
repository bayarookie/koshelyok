<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Импорт</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<h1><a href="">Импорт</a></h1>
<form enctype="multipart/form-data" action="import_it.php" method="POST">
<?php
include 'db.php';
$w_id = 1;
echo '<p>Выберите кошелёк: <select size="1" name="m_walls_id" id="m_walls_id">';
$query = "SELECT id, name FROM walls";
$result = byQu($mysqli, $query);
while ($row = $result->fetch_assoc()) {
	echo '<option';
	if ($row['id'] == $w_id) {echo ' selected';}
	echo ' value="' . $row['id'] . '">' . $row['name'] . '</option>';
}
echo '</select></p>';
?>
	<input type="hidden" name="MAX_FILE_SIZE" value="130000">
	Отправить этот файл: <input name="userfile" type="file">
	<input type="submit" value="Send File">
</form>
</body></html>
