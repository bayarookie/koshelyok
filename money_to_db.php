<?php
include 'db.php';
//редактирование или добавление записи в БД
if (isset($_POST['m_id'])) {
	$m_id = intval($_POST['m_id']);
	if (isset($_POST['m_op_date'])) $date = $mysqli->real_escape_string($_POST['m_op_date']); else $date = date('Y-m-d');
	if (isset($_POST['m_op_summ'])) $summ = floatval($_POST['m_op_summ']); else $summ = 0;
	if (isset($_POST['m_goods_id'])) $g_id = intval($_POST['m_goods_id']); else $g_id = -1;
	if (isset($_POST['m_comment'])) $komm = $mysqli->real_escape_string($_POST['m_comment']); else $komm = '';
	if (isset($_POST['m_walls_id'])) $w_id = intval($_POST['m_walls_id']); else $w_id = -1;
	if ($m_id > -1) {
		$q = "UPDATE money
				SET op_date=STR_TO_DATE('$date', '%Y-%m-%d'), op_summ=$summ, goods_id=$g_id, comment='$komm', walls_id=$w_id
				WHERE id=$m_id";
	} elseif ($m_id === -1) {
		$q = "INSERT INTO money (op_date, op_summ, goods_id, comment, walls_id)
				VALUES (STR_TO_DATE('$date', '%Y-%m-%d'), $summ, $g_id, '$komm', $w_id)";
	} else $q = '';
	byQu($mysqli, $q);
	include 'money_table.php';
}
?>
