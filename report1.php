<?php
include 'db.php';
$f_dtto = isset($_POST['to']) ? date('Y-m-d', strtotime($_POST['to'])) : date('Y-m-d');
$f_dtfr = isset($_POST['from']) ? date('Y-m-d', strtotime($_POST['from'])) : date('Y-m-d', strtotime(date('Y-m-') . '01 -6 month'));
echo '<article><p>Отчёт помесячно
с <input type="date" id="p_date_from" placeholder="Дата" value="' . $f_dtfr . '" autofocus>
по <input type="date" id="p_date_to" placeholder="Дата" value="' . $f_dtto . '">
<input type="button" value="Отчёт" onclick="get_report(\'report1\')">
<input type="button" value="Закрыть" onclick="id_close(\'report1\')"></p>';
echo '<table><tr><th>Группа';
$res0 = byQu($mysqli,
	"SELECT DATE_FORMAT(op_date,'%Y-%m') as mo, SUM(op_summ) as summ
		FROM money
		WHERE money.op_date>='$f_dtfr' AND money.op_date<='$f_dtto'
		GROUP BY mo");
while ($row0 = $res0->fetch_assoc()) {
	echo '<th>' . $row0['mo'];
}
echo '<th>Сумма';
$gr = "";
$sm = 0;
$res1 = byQu($mysqli,
	"SELECT goods.groups_id, groups.name, SUM(op_summ) as summ
		FROM money
		LEFT JOIN goods ON money.goods_id=goods.id
		LEFT JOIN groups ON goods.groups_id=groups.id
		WHERE money.op_date>='$f_dtfr' AND money.op_date<='$f_dtto'
		GROUP BY goods.groups_id  ORDER BY groups.name");
while ($row1 = $res1->fetch_assoc()) {
	echo '<tr><td>' . $row1['name'];
	$res0->data_seek(0);
	while ($row0 = $res0->fetch_assoc()) {
		$res2 = byQu($mysqli,
			"SELECT SUM(op_summ) as summ
				FROM money
				LEFT JOIN goods ON money.goods_id=goods.id
				WHERE money.op_date>='$f_dtfr' AND money.op_date<='$f_dtto'
				AND groups_id=" . $row1['groups_id'] . " AND DATE_FORMAT(op_date,'%Y-%m')='" . $row0['mo'] . "'");
		if ($row2 = $res2->fetch_assoc()) {
			echo '<td class="edit num"';
			echo ' onclick="money_table(2, ' . $row1['groups_id'] . ',\'' . $row0['mo'] . '\')">';
			echo $row2['summ'] ?: '0.00';
		}
	}
	if (floatval($row1['summ']) < 0) echo '<td class="minus num">'; else echo '<td class="plus num">';
	echo $row1['summ'];
	$sm = $sm + floatval($row1['summ']);
}
echo '<tr><td>Итого';
$res0->data_seek(0);
while ($row0 = $res0->fetch_assoc()) {
	if (floatval($row0['summ']) < 0) echo '<td class="minus num">'; else echo '<td class="plus num">';
	echo $row0['summ'];
}
if ($sm < 0) echo '<td class="minus num">'; else echo '<td class="plus num">';
echo number_format($sm, 2, '.', '');
?>
</table></article>
