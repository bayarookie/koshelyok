<?php
include 'db.php';
//редактирование или добавление категорий в БД
if (isset($_POST['r_id'])) {
	$r_id = intval($_POST['r_id']);
	if (isset($_POST['r_name'])) {$name = $mysqli->real_escape_string($_POST['r_name']);} else {$name = '';}
	if (isset($_POST['r_comment'])) {$komm = $mysqli->real_escape_string($_POST['r_comment']);} else {$komm = '';}
	if ($r_id > -1) {
		$query = "UPDATE groups"
				." SET name='$name', comment='$komm'"
				." WHERE id=$r_id";
	} else {
		$query = "INSERT INTO groups (name, comment)"
				." VALUES ('$name', '$komm')";
	}
	$result = byQu($mysqli, $query);
	include 'groups_table.php';
}
?>
