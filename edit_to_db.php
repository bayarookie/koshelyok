<?php
//редактирование или добавление категорий в БД
$tbl = isset($_POST['tbl']) ? $mysqli->real_escape_string($_POST['tbl']) : '';
$e_id = isset($_POST['e_id']) ? intval($_POST['e_id']) : -1;
$name = isset($_POST['e_name']) ? $mysqli->real_escape_string($_POST['e_name']) : '';
$komm = isset($_POST['e_comment']) ? $mysqli->real_escape_string($_POST['e_comment']) : '';
if (empty($tbl)) die('table?');
if ($tbl == 'money') {
	$date = isset($_POST['e_op_date']) ? $mysqli->real_escape_string($_POST['e_op_date']) : date('Y-m-d');
	$summ = isset($_POST['e_op_summ']) ? floatval($_POST['e_op_summ']) : 0;
	$s_id = isset($_POST['e_servs_id']) ? intval($_POST['e_servs_id']) : -1;
	$g_id = isset($_POST['e_grups_id']) ? intval($_POST['e_grups_id']) : -1;
	$w_id = isset($_POST['e_walls_id']) ? intval($_POST['e_walls_id']) : -1;
	$u_id = isset($_POST['e_users_id']) ? intval($_POST['e_users_id']) : -1;
	if ($e_id > -1) {
		$q = "UPDATE money
				SET op_date=STR_TO_DATE('$date', '%Y-%m-%d'), op_summ=$summ, servs_id=$s_id, grups_id=$g_id, walls_id=$w_id, users_id=$u_id, comment='$komm'
				WHERE id=$e_id";
	} else {
		$q = "INSERT INTO money (op_date, op_summ, servs_id, grups_id, walls_id, users_id, comment)
				VALUES (STR_TO_DATE('$date', '%Y-%m-%d'), $summ, $s_id, $g_id, $w_id, $u_id, '$komm')";
	}
} elseif ($tbl == 'money_order') {
	$ordr = isset($_POST['e_order_by']) ? $mysqli->real_escape_string($_POST['e_order_by']) : '';
	if ($e_id > -1) {
		$q = "UPDATE money_order
				SET name='$name', order_by='$ordr'
				WHERE id=$e_id";
	} else {
		$q = "INSERT INTO $tbl (name, order_by)
				VALUES ('$name', '$ordr')";
	}
} elseif ($tbl == 'users') {
	$user = isset($_POST['e_username']) ? $mysqli->real_escape_string($_POST['e_username']) : '';
	$pass = isset($_POST['e_password']) ? $mysqli->real_escape_string($_POST['e_password']) : '';
	if ($e_id > -1) {
		$q = "UPDATE users
				SET username='$user', password='$pass', name='$name'
				WHERE id=$e_id";
	} else {
		$q = "INSERT INTO users (username, password, name)
				VALUES ('$user', '$pass', '$name')";
	}
} elseif ($tbl == 'servs_v') {
	$r_id = isset($_POST['e_grups_id']) ? intval($_POST['e_grups_id']) : -1;
	if ($e_id > -1) {
		$q = "UPDATE servs
				SET name='$name', comment='$komm', grups_id=$r_id
				WHERE id=$e_id";
	} else {
		$q = "INSERT INTO servs (name, comment, grups_id)
				VALUES ('$name', '$komm', $r_id)";
	}
} elseif ($tbl == 'grups_v') {
	$r_id = isset($_POST['e_bgrup_id']) ? intval($_POST['e_bgrup_id']) : -1;
	if ($e_id > -1) {
		$q = "UPDATE grups
				SET name='$name', comment='$komm', bgrup_id=$r_id
				WHERE id=$e_id";
	} else {
		$q = "INSERT INTO grups (name, comment, bgrup_id)
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
byQu($q);
if ($tbl == 'money') include 'money_table.php';
else include 'edit_table.php';
?>
