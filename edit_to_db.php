<?php
include 'db.php';
//редактирование или добавление категорий в БД
$tbl = isset($_POST['tbl']) ? $mysqli->real_escape_string($_POST['tbl']) : '';
$e_id = isset($_POST['e_id']) ? intval($_POST['e_id']) : -1;
$name = isset($_POST['e_name']) ? $mysqli->real_escape_string($_POST['e_name']) : '';
$komm = isset($_POST['e_comment']) ? $mysqli->real_escape_string($_POST['e_comment']) : '';
$r_id = isset($_POST['e_groups_id']) ? intval($_POST['e_groups_id']) : -1;
if (!empty($tbl)) {
	if ($tbl == 'goods') {
		if ($e_id > -1) {
			$q = "UPDATE goods
					SET name='$name', comment='$komm', groups_id=$r_id
					WHERE id=$e_id";
		} else {
			$q = "INSERT INTO goods (name, comment, groups_id)
					VALUES ('$name', '$komm', $r_id)";
		}
	} else {
		if ($e_id > -1) {
			$q = "UPDATE $tbl
					SET name='$name', comment='$komm'
					WHERE id=$e_id";
		} else {
			$q = "INSERT INTO $tbl (name, comment)
					VALUES ('$name', '$komm')";
		}
	}
	byQu($mysqli, $q);
	include 'edit_table.php';
}
?>
