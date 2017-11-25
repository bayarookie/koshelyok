<?php
$f_dtto = isset($_POST['to']) ? date('Y-m-d', strtotime($_POST['to'])) : date('Y-m-d');
$f_dtfr = isset($_POST['from']) ? date('Y-m-d', strtotime($_POST['from'])) : date('Y-m-d', strtotime(date('Y-m-') . '01 -6 month'));
echo '<article><p>Отчёт помесячно
с <input type="date" id="p_date_from" value="' . $f_dtfr . '">
по <input type="date" id="p_date_to" value="' . $f_dtto . '">
<input type="button" value="Отчёт" onclick="get_report(\'report\')"> 
<input type="button" value="Закрыть" onclick="id_close(\'report\')"></p>';
$mo = "";
$sm = 0;
$result = byQu($mysqli,
	"SELECT DATE_FORMAT(op_date,'%Y-%m') as mo, goods.groups_id, groups.name, SUM(op_summ) as summ
		FROM money
		LEFT JOIN goods ON money.goods_id=goods.id
		LEFT JOIN groups ON goods.groups_id=groups.id
		WHERE money.op_date>='$f_dtfr' AND money.op_date<='$f_dtto'
		GROUP BY mo, goods.groups_id ORDER BY mo, groups.name");
while ($row = $result->fetch_assoc()) {
	if ($mo != $row['mo']) {
		if ($mo != "") {
			if ($sm < 0) echo '<tr class="minus">'; else echo '<tr class="plus">';
			echo '<td>Итого<td class="num">' . $sm;
			echo '</table></figure>';
		}
		$mo = $row['mo'];
		$sm = floatval($row['summ']);
		echo '<figure class="report"><figcaption>' . $mo . '</figcaption><table><tr><th>Группа<th>Сумма';
	} else $sm = $sm + floatval($row['summ']);
	if (floatval($row['summ']) < 0) echo '<tr class="minus">'; else echo '<tr class="plus">';
	echo '<td>' . $row['name'] . '<td class="edit num" onclick="money_table(2, ' . $row['groups_id'] . ',\'' . $row['mo'] . '\')">' . $row['summ'];
}
if ($sm < 0) echo '<tr class="minus">'; else echo '<tr class="plus">';
echo '<td>Итого<td class="num">' . $sm;
?>
</table></figure></article>
