<?php
include 'db.php';
echo '<article><h1><a href="">Отчёт</a></h1>
<table><tr><th>Группа<th>Сумма';
$query = "SELECT DATE_FORMAT(op_date,'%Y-%m') as mo, groups.id, groups.name, SUM(op_summ) as summ"
		." FROM money"
		." LEFT JOIN goods ON money.goods_id=goods.id"
		." LEFT JOIN groups ON goods.groups_id=groups.id"
		." GROUP BY mo, groups.id";
$result = byQu($mysqli, $query);
$mo = "";
$sm = 0;
while ($row = $result->fetch_assoc()) {
	if ($mo != $row['mo']) {
		if ($mo != "") echo '<tr class="minus"><td>' . $mo . '<td align="right">' . $sm;
		$mo = $row['mo'];
		$sm = floatval($row['summ']);
		echo '<tr class="plus"><td colspan=2>' . $mo;
	} else $sm = $sm + floatval($row['summ']);
	echo '<tr><td>' . $row['name'] . '<td align="right">
<input type="button" value="' . $row['summ'] . '" onclick="money_table(2,\'' . $row['mo'] . '\',' . $row['id'] . ')">';
}
echo '<tr class="minus"><td>' . $mo . '<td align="right">' . $sm;
echo '</table>';
?>
<input type="button" value="Закрыть" onclick="id_close('report')"></article>
