<?php
include 'db.php';
echo '<article><h1><a href="">Отчёт</a></h1>';
echo '<style>
.report {
	padding: 10px;
	display: inline-block;
	min-width: 175px;
	margin: 0 10px 10px 0;
}
figcaption {
	text-align: center;
}
</style>';
$query = "SELECT DATE_FORMAT(op_date,'%Y-%m') as mo, groups.id, groups.name, SUM(op_summ) as summ"
		." FROM money"
		." LEFT JOIN goods ON money.goods_id=goods.id"
		." LEFT JOIN groups ON goods.groups_id=groups.id"
		." GROUP BY mo, groups.id ORDER BY mo, groups.name";
$result = byQu($mysqli, $query);
$mo = "";
$sm = 0;
while ($row = $result->fetch_assoc()) {
	if ($mo != $row['mo']) {
		if ($mo != "") {
			if ($sm < 0) {echo '<tr class="minus">';} else {echo '<tr class="plus">';}
			echo '<td>Итого<td align="right">' . $sm;
			echo '</table></figure>';
		}
		$mo = $row['mo'];
		$sm = floatval($row['summ']);
		echo '<figure class="report"><figcaption>' . $mo . '</figcaption><table><tr><th>Группа<th>Сумма';
	} else $sm = $sm + floatval($row['summ']);
	if (floatval($row['summ']) < 0) {echo '<tr class="minus">';} else {echo '<tr class="plus">';}
	echo '<td>' . $row['name'] . '<td align="right">
<input type="button" value="' . $row['summ'] . '" onclick="money_table(2,\'' . $row['mo'] . '\',' . $row['id'] . ')">';
}
if ($sm < 0) {echo '<tr class="minus">';} else {echo '<tr class="plus">';}
echo '<td>Итого<td align="right">' . $sm;
echo '</table></figure>';
?>
<input type="button" value="Закрыть" onclick="id_close('report')"></article>
