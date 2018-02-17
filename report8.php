<?php
$f_dtto = isset($_POST['p_date_to']) ? date('Y-m-d', strtotime($_POST['p_date_to'])) : date('Y-m-d');
$f_dtfr = isset($_POST['p_date_from']) ? date('Y-m-d', strtotime($_POST['p_date_from'])) : date('Y-m-d', strtotime(date('Y-m-') . '01 -6 month'));
$f_grups_id = isset($_POST['f_grups_id']) ? intval($_POST['f_grups_id']) : 0;
echo '<article><table class="form"><tr><td>Отчёт №8, помесячно
с<td><input type="date" name="p_date_from" placeholder="Дата" value="' . $f_dtfr . '">
по <input type="date" name="p_date_to" placeholder="Дата" value="' . $f_dtto . '">';
bySe('подгруппа:', 'f_grups_id', 'grups', $f_grups_id, '');
echo '<tr><td><td><input type="button" value="Отчёт" onclick="get_report(\'report8\')">
<input type="button" value="Закрыть" onclick="id_close(\'report\')"></table>
<table><tr><th>Месяц';
$res0 = byQu("SELECT grups_id, grups.name, SUM(op_summ) as summ
	FROM money
	LEFT JOIN grups ON grups_id=grups.id
	WHERE op_date>='$f_dtfr' AND op_date<='$f_dtto' AND grups_id=$f_grups_id");
while ($row0 = $res0->fetch_assoc()) echo '<th>' . $row0['name'];
$gr = "";
$sm = 0;
$res1 = byQu("SELECT DATE_FORMAT(op_date,'%Y-%m') as mo, SUM(op_summ) as summ
	FROM money
	WHERE op_date>='$f_dtfr' AND op_date<='$f_dtto'
	GROUP BY mo");
while ($row1 = $res1->fetch_assoc()) {
	echo '<tr><td>' . $row1['mo'];
	$res0->data_seek(0);
	while ($row0 = $res0->fetch_assoc()) {
		$res2 = byQu("SELECT SUM(op_summ) as summ
			FROM money
			WHERE op_date>='$f_dtfr' AND op_date<='$f_dtto' AND grups_id=$f_grups_id
			AND DATE_FORMAT(op_date,'%Y-%m')='" . $row1['mo'] . "'");
		if ($row2 = $res2->fetch_assoc())
			if ($row2['summ'] == '')
				echo '<td class="num">0.00';
			else
				echo '<td class="edit num" onclick="money_table(4,' . $row0['grups_id'] . ',\'&mo=' . $row1['mo'] . '\')">' . $row2['summ'];
	}
	$sm = $sm + floatval($row1['summ']);
}
echo '<tr><td>Итого';
$res0->data_seek(0);
while ($row0 = $res0->fetch_assoc())
	echo '<td class="' . ((floatval($row0['summ']) < 0) ? 'minus' : 'plus') . ' num">' . $row0['summ'];
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
$res1->data_seek(0);
while ($row1 = $res1->fetch_assoc()) $mo .= '"' . $row1['mo'] . '",';
$res0->data_seek(0);
while ($row0 = $res0->fetch_assoc()) {
	$co = byCo();
	echo '{
	label: "' . $row0['name'] . '",
	backgroundColor: "' . $co . '",
	borderColor: "' . $co . '",
	fill: false,
	data: [';
	$res1->data_seek(0);
	while ($row1 = $res1->fetch_assoc()) {
		$res2 = byQu("SELECT SUM(op_summ) as summ
			FROM money
			WHERE op_date>='$f_dtfr' AND op_date<='$f_dtto' AND grups_id=$f_grups_id
			AND DATE_FORMAT(op_date,'%Y-%m')='" . $row1['mo'] . "'");
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
