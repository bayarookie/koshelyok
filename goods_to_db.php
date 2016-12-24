<?php
include 'db.php';
//редактирование или добавление категорий в БД
if (isset($_POST['g_id'])) {
	$g_id = intval($_POST['g_id']);
	if (isset($_POST['g_name'])) $name = $mysqli->real_escape_string($_POST['g_name']); else $name = '';
	if (isset($_POST['g_comment'])) $komm = $mysqli->real_escape_string($_POST['g_comment']); else $komm = '';
	if (isset($_POST['g_groups_id'])) $r_id = intval($_POST['g_groups_id']); else $r_id = -1;
	if ($g_id > -1) {
		$q = "UPDATE goods
				SET name='$name', comment='$komm', groups_id=$r_id
				WHERE id=$g_id";
	} else {
		$q = "INSERT INTO goods (name, comment, groups_id)
				VALUES ('$name', '$komm', $r_id)";
	}
	byQu($mysqli, $q);
	include 'goods_table.php';
}
?>
