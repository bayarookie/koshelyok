<?php
$f_dtto = isset($_POST['to']) ? date('Y-m-d', strtotime($_POST['to'])) : date('Y-m-d');
$result = byQu($mysqli, "SELECT MIN(op_date) FROM money");
$dt = ($row = $result->fetch_row()) ? $row[0] : '2015-01-01';
$f_dtfr = isset($_POST['from']) ? date('Y-m-d', strtotime($_POST['from'])) : $dt;
echo '<article><p>Отчёт №4, по группам
с <input type="date" id="p_date_from" placeholder="Дата" value="' . $f_dtfr . '">
по <input type="date" id="p_date_to" placeholder="Дата" value="' . $f_dtto . '">
<input type="button" value="Отчёт" onclick="get_report(\'report4\')"> 
<input type="button" value="Закрыть" onclick="id_close(\'report\')"></p>';
echo '<figure><figcaption>с ' . $f_dtfr . ' по ' . $f_dtto . '</figcaption>
<table><tr><th>Группа<th>Сумма';
$sm = 0;
$result = byQu($mysqli, "SELECT groups.id, groups.name, SUM(op_summ) as summ
	FROM money
	LEFT JOIN goods ON money.goods_id=goods.id
	LEFT JOIN groups ON goods.groups_id=groups.id
	WHERE money.op_date>='$f_dtfr' AND money.op_date<='$f_dtto'
	GROUP BY groups.id
	ORDER BY summ DESC");
while ($row = $result->fetch_assoc()) {
	$sm = $sm + floatval($row['summ']);
	$c = (floatval($row['summ']) < 0) ? 'minus' : 'plus';
	echo '<tr class="' . $c . '"><td>' . $row['name'];
	echo '<td class="edit num ' . $c . '" onclick="get_form(\'money_table\',0,\'money&f=3&f_groups_id=' . $row['id'] . '\')">';
	echo number_format($row['summ'], 2, '.', '');
}
echo '<tr><td>Итого' . '<td class="num ' . (($sm < 0) ? 'minus' : 'plus') . '">' . number_format($sm, 2, '.', '');
?>
</table></figure>
<canvas id="Chart4" width="500" height="300"></canvas>
<script id='chartjs'>
var ctx = document.getElementById("Chart4");
var myChart = new Chart(ctx, {
	type: 'pie',
	data: {
		datasets: [{
<?php
$co = '';
$gr = '';
$sm = '';
$result->data_seek(0);
while ($row = $result->fetch_assoc()) {
	$co .= '"' . byCo() . '",';
	$gr .= '"' . $row['name'] . '",';
	$sm .= abs($row['summ'] ?: '0.00') . ',';
}
echo '			backgroundColor: [' . $co . '],
			data: [' . $sm . ']
		}],
		labels: [' . $gr . ']';
?>
	},
	options: {
		responsive: true,
		legend: {
			position: 'right',
		},
	}
});
</script>
</article>
