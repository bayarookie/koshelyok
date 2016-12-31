<?php
include 'db.php';
//редактирование или добавление записи в БД
if (isset($_POST['m_id'])) {
	$m_id = intval($_POST['m_id']);
	$date = isset($_POST['m_op_date']) ? $mysqli->real_escape_string($_POST['m_op_date']) : date('Y-m-d');
	$summ = isset($_POST['m_op_summ']) ? floatval($_POST['m_op_summ']) : 0;
	$g_id = isset($_POST['m_goods_id']) ? intval($_POST['m_goods_id']) : -1;
	$komm = isset($_POST['m_comment']) ? $mysqli->real_escape_string($_POST['m_comment']) : '';
	$w_id = isset($_POST['m_walls_id']) ? intval($_POST['m_walls_id']) : -1;
	if ($m_id > -1) {
		$q = "UPDATE money
				SET op_date=STR_TO_DATE('$date', '%Y-%m-%d'), op_summ=$summ, goods_id=$g_id, comment='$komm', walls_id=$w_id
				WHERE id=$m_id";
	} else {
		$q = "INSERT INTO money (op_date, op_summ, goods_id, comment, walls_id)
				VALUES (STR_TO_DATE('$date', '%Y-%m-%d'), $summ, $g_id, '$komm', $w_id)";
	}
	byQu($mysqli, $q);
	include 'money_table.php';
}
?>
