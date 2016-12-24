<?php
include 'db.php';
//редактирование или добавление категорий в БД
if (isset($_POST['w_id'])) {
	$w_id = intval($_POST['w_id']);
	if (isset($_POST['w_name'])) $name = $mysqli->real_escape_string($_POST['w_name']); else $name = '';
	if (isset($_POST['w_comment'])) $komm = $mysqli->real_escape_string($_POST['w_comment']); else $komm = '';
	if ($w_id > -1) {
		$q = "UPDATE walls
				SET name='$name', comment='$komm'
				WHERE id=$w_id";
	} else {
		$q = "INSERT INTO walls (name, comment)
				VALUES ('$name', '$komm')";
	}
	byQu($mysqli, $q);
	include 'walls_table.php';
}
?>
