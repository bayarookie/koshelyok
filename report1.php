<?php
$f_dtto = isset($_POST['to']) ? date('Y-m-d', strtotime($_POST['to'])) : date('Y-m-d');
$f_dtfr = isset($_POST['from']) ? date('Y-m-d', strtotime($_POST['from'])) : date('Y-m-d', strtotime(date('Y-m-') . '01 -6 month'));
echo '<article><p>Отчёт №1, помесячно подгруппы
с <input type="date" id="p_date_from" placeholder="Дата" value="' . $f_dtfr . '">
по <input type="date" id="p_date_to" placeholder="Дата" value="' . $f_dtto . '">
<input type="button" value="Отчёт" onclick="get_report(\'report1\')">
<input type="button" value="Закрыть" onclick="id_close(\'report\')"></p>';
echo '<table><tr><th>Подгруппа';
$res0 = byQu("SELECT DATE_FORMAT(op_date,'%Y-%m') as mo, SUM(op_summ) as summ
	FROM money
	WHERE op_date>='$f_dtfr' AND op_date<='$f_dtto'
	GROUP BY mo");
while ($row0 = $res0->fetch_assoc()) echo '<th>' . $row0['mo'];
echo '<th>Сумма';
$sm = 0;
$res1 = byQu("SELECT grups_id, grups.name, SUM(op_summ) as summ
	FROM money
	LEFT JOIN grups ON grups_id=grups.id
	WHERE op_date>='$f_dtfr' AND op_date<='$f_dtto'
	GROUP BY grups_id
	ORDER BY summ DESC");
while ($row1 = $res1->fetch_assoc()) {
	echo '<tr><td>' . $row1['name'];
	$res0->data_seek(0);
	while ($row0 = $res0->fetch_assoc()) {
		$res2 = byQu("SELECT SUM(op_summ) as summ
			FROM money
			WHERE op_date>='$f_dtfr' AND op_date<='$f_dtto'
			AND grups_id=" . $row1['grups_id'] . " AND DATE_FORMAT(op_date,'%Y-%m')='" . $row0['mo'] . "'");
		if ($row2 = $res2->fetch_assoc())
			if ($row2['summ'] == '')
				echo '<td class="num">0.00';
			else
				echo '<td class="edit num" onclick="money_table(4,' . $row1['grups_id'] . ',\'&mo=' . $row0['mo'] . '\')">' . $row2['summ'];
	}
	echo '<td class="' . ((floatval($row1['summ']) < 0) ? 'minus' : 'plus') . ' num">' . $row1['summ'];
	$sm = $sm + floatval($row1['summ']);
}
echo '<tr><td>Итого';
$res0->data_seek(0);
while ($row0 = $res0->fetch_assoc())
	echo '<td class="' . ((floatval($row0['summ']) < 0) ? 'minus' : 'plus') . ' num">' . $row0['summ'];
echo '<td class="' . (($sm < 0) ? 'minus' : 'plus') . ' num">' . number_format($sm, 2, '.', '');
?>
</table>
<canvas id="Chart1" width="500" height="300"></canvas>
<script id='chartjs'>
var ctx = document.getElementById("Chart1");
var myChart = new Chart(ctx, {
	type: 'line',
	data: {
		datasets: [
<?php
$mo = '';
$res0->data_seek(0);
while ($row0 = $res0->fetch_assoc()) $mo .= '"' . $row0['mo'] . '",';
$res1->data_seek(0);
while ($row1 = $res1->fetch_assoc()) {
	$co = byCo();
	echo '{
	label: "' . $row1['name'] . '",
	backgroundColor: "' . $co . '",
	borderColor: "' . $co . '",
	fill: false,
	data: [';
	$res0->data_seek(0);
	while ($row0 = $res0->fetch_assoc()) {
		$res2 = byQu("SELECT SUM(op_summ) as summ
			FROM money
			WHERE op_date>='$f_dtfr' AND op_date<='$f_dtto'
			AND grups_id=" . $row1['grups_id'] . " AND DATE_FORMAT(op_date,'%Y-%m')='" . $row0['mo'] . "'");
		if ($row2 = $res2->fetch_assoc()) echo ($row2['summ'] ? abs($row2['summ']) : '0.00') . ',';
	}
	echo ']
	},';
}
echo '],labels: [' . $mo . ']';
?>
	},
	options: {
		responsive: true,
		title:{
			display:true,
			text:'График'
		},
		tooltips: {
			mode: 'index',
			intersect: false,
		},
		hover: {
			mode: 'nearest',
			intersect: true
		},
		scales: {
			xAxes: [{
				display: true,
				scaleLabel: {
					display: true,
					labelString: 'Месяц'
				}
			}],
			yAxes: [{
				display: true,
				scaleLabel: {
					display: true,
					labelString: 'Сумма'
				}
			}]
		}
	}
});
</script>
</article>
