<?php
$f_dtto = date('Y-m-d', strtotime($_POST['p_date_to'] ?? 'now'));
$f_dtfr = date('Y-m-d', strtotime($_POST['p_date_from'] ?? 'first day of this month -1 year'));
echo '<article><p>Отчёт №2, помесячно подгруппы
с <input type="date" value="' . $f_dtfr . '" name="p_date_from" placeholder="Дата">
по <input type="date" value="' . $f_dtto . '" name="p_date_to" placeholder="Дата">
<input type="button" value="Отчёт" onclick="get_report(\'report2\')">
<input type="button" value="Закрыть" onclick="id_close(\'report\')"></p>';
echo '<table><tr><th>Месяц';
$res0 = byQu("SELECT bgrup_id, bgrup.name, SUM(op_summ) as summ
	FROM money
	LEFT JOIN servs ON money.servs_id=servs.id
	LEFT JOIN grups ON servs.grups_id=grups.id
	LEFT JOIN bgrup ON grups.bgrup_id=bgrup.id
	WHERE op_date>='$f_dtfr' AND op_date<='$f_dtto'
	GROUP BY bgrup_id
	ORDER BY summ DESC");
while ($row0 = $res0->fetch_assoc()) echo '<th>' . $row0['name'];
echo '<th>Сумма';
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
			LEFT JOIN servs ON money.servs_id=servs.id
			LEFT JOIN grups ON servs.grups_id=grups.id
			WHERE op_date>='$f_dtfr' AND op_date<='$f_dtto'
			AND bgrup_id=" . intval($row0['bgrup_id']) . " AND DATE_FORMAT(op_date,'%Y-%m')='" . $row1['mo'] . "'");
		if ($row2 = $res2->fetch_assoc())
			if ($row2['summ'] == '')
				echo '<td class="num">0.00';
			else
				echo '<td class="edit num" onclick="money_table(7,' . $row0['bgrup_id'] . ',\'&mo=' . $row1['mo'] . '\')">' . $row2['summ'];
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
<canvas id="Chart2" width="500" height="300"></canvas>
<script id='js'>
var ctx = document.getElementById("Chart2");
var myChart = new Chart(ctx, {
	type: 'bar',
	data: {
		datasets: [
<?php
$gr = '';
$res0->data_seek(0);
while ($row0 = $res0->fetch_assoc()) $gr .= '"' . $row0['name'] . '",';
$res1->data_seek(0);
while ($row1 = $res1->fetch_assoc()) {
	$co = byCo();
	echo '{
	label: "' . $row1['mo'] . '",
	backgroundColor: "' . $co . '",
	borderColor: "' . $co . '",
	fill: false,
	data: [';
	$res0->data_seek(0);
	while ($row0 = $res0->fetch_assoc()) {
		$res2 = byQu("SELECT SUM(op_summ) as summ
			FROM money
			LEFT JOIN servs ON money.servs_id=servs.id
			LEFT JOIN grups ON servs.grups_id=grups.id
			WHERE op_date>='$f_dtfr' AND op_date<='$f_dtto'
			AND bgrup_id=" . intval($row0['bgrup_id']) . " AND DATE_FORMAT(op_date,'%Y-%m')='" . $row1['mo'] . "'");
		if ($row2 = $res2->fetch_assoc()) echo ($row2['summ'] ? abs($row2['summ']) : '0.00') . ',';
	}
	echo ']
	},';
}
echo '],labels: [' . $gr . ']';
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
					labelString: 'Группа'
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
