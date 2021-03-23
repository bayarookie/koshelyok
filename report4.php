<?php
$f_dtto = date('Y-m-d', strtotime($_POST['p_date_to'] ?? 'now'));
$f_dtfr = date('Y-m-d', strtotime($_POST['p_date_from'] ?? byDt('MIN')));
echo '<article><p>Отчёт №4, по подгруппам
с <input type="date" value="' . $f_dtfr . '" name="p_date_from" placeholder="Дата">
по <input type="date" value="' . $f_dtto . '" name="p_date_to" placeholder="Дата">
<input type="button" value="Отчёт" onclick="get_report(\'report4\')"> 
<input type="button" value="Закрыть" onclick="id_close(\'report\')"></p>';
echo '<figure><figcaption>с ' . $f_dtfr . ' по ' . $f_dtto . '</figcaption>
<table><tr><th>Подгруппа<th>Группа<th>Сумма';
$sm = 0;
$co = '';
$sd = '';
$gr = '';
$result = byQu("SELECT grups_id, grups.name, bgrup.name as bgrup_name, SUM(op_summ) as summ
	FROM money
	LEFT JOIN servs ON money.servs_id=servs.id
	LEFT JOIN grups ON servs.grups_id=grups.id
	LEFT JOIN bgrup ON grups.bgrup_id=bgrup.id
	WHERE op_date>='$f_dtfr' AND op_date<='$f_dtto'
	GROUP BY grups_id
	ORDER BY summ DESC");
while ($row = $result->fetch_assoc()) {
	$c = byCo();
	$co .= '"' . $c . '",';
	$sd .= abs($row['summ'] ?: '0.00') . ',';
	$gr .= '"' . $row['name'] . '",';
	$sm = $sm + floatval($row['summ']);
	echo '<tr style="background-color:' . $c . ';"><td>' . $row['name'];
	echo '<td>' . $row['bgrup_name'];
	echo '<td class="edit num" onclick="money_table(6,' . $row['grups_id'] . ')">';
	echo number_format($row['summ'], 2, '.', '');
}
echo '<tr><td>Итого<td>' . '<td class="num ' . (($sm < 0) ? 'minus' : 'plus') . '">' . number_format($sm, 2, '.', '');
?>
</table></figure>
<canvas id="Chart4" width="500" height="300"></canvas>
<script id='js'>
var ctx = document.getElementById("Chart4");
var myChart = new Chart(ctx, {
	type: 'pie',
	data: {
		datasets: [{
<?php
echo '			backgroundColor: [' . $co . '],
			data: [' . $sd . ']
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
