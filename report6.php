<?php
$f_dtto = isset($_POST['to']) ? date('Y-m-d', strtotime($_POST['to'])) : date('Y-m-d');
$result = byQu($mysqli, "SELECT MIN(op_date) FROM money");
if ($row = $result->fetch_row()) $dt = $row[0]; else $dt = '2015-01-01';
$f_dtfr = isset($_POST['from']) ? date('Y-m-d', strtotime($_POST['from'])) : $dt;
echo '<article><p>Отчёт по пользователям
с <input type="date" id="p_date_from" placeholder="Дата" value="' . $f_dtfr . '">
по <input type="date" id="p_date_to" placeholder="Дата" value="' . $f_dtto . '">
<input type="button" value="Отчёт" onclick="get_report(\'report6\')">
<input type="button" value="Закрыть" onclick="id_close(\'report\')"></p>';
echo '<table><tr><th>Группа';
$res0 = byQu($mysqli, "SELECT money.users_id, users.name, SUM(op_summ) as summ
	FROM money
	LEFT JOIN users ON money.users_id=users.id
	WHERE money.op_date>='$f_dtfr' AND money.op_date<='$f_dtto'
	GROUP BY money.users_id
	ORDER BY users.name");
while ($row0 = $res0->fetch_assoc()) {
	echo '<th>' . $row0['name'];
}
echo '<th>Сумма';
$gr = "";
$sm = 0;
$res1 = byQu($mysqli, "SELECT goods.groups_id, groups.name, SUM(op_summ) as summ
	FROM money
	LEFT JOIN goods ON money.goods_id=goods.id
	LEFT JOIN groups ON goods.groups_id=groups.id
	WHERE money.op_date>='$f_dtfr' AND money.op_date<='$f_dtto'
	GROUP BY goods.groups_id
	ORDER BY groups.name");
while ($row1 = $res1->fetch_assoc()) {
	echo '<tr><td>' . $row1['name'];
	$res0->data_seek(0);
	while ($row0 = $res0->fetch_assoc()) {
		$res2 = byQu($mysqli, "SELECT SUM(op_summ) as summ
			FROM money
			LEFT JOIN goods ON money.goods_id=goods.id
			WHERE money.op_date>='$f_dtfr' AND money.op_date<='$f_dtto'
			AND groups_id=" . $row1['groups_id'] . " AND users_id='" . $row0['users_id'] . "'");
		if ($row2 = $res2->fetch_assoc()) {
			echo '<td class="edit num"';
			echo ' onclick="money_table(6,' . $row1['groups_id'] . ',' . $row0['users_id'] . ')">';
			echo $row2['summ'] ?: '0.00';
		}
	}
	echo (floatval($row1['summ']) < 0) ? '<td class="minus num">' : '<td class="plus num">';
	echo $row1['summ'];
	$sm = $sm + floatval($row1['summ']);
}
echo '<tr><td>Итого';
$res0->data_seek(0);
while ($row0 = $res0->fetch_assoc()) {
	echo (floatval($row0['summ']) < 0) ? '<td class="minus num">' : '<td class="plus num">';
	echo $row0['summ'];
}
echo ($sm < 0) ? '<td class="minus num">' : '<td class="plus num">';
echo number_format($sm, 2, '.', '');
?>
</table></article>
