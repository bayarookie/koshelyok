<?php
$f_dtto = isset($_POST['p_date_to']) ? date('Y-m-d', strtotime($_POST['p_date_to'])) : date('Y-m-d');
$result = byQu("SELECT MIN(op_date) FROM money");
if ($row = $result->fetch_row()) $dt = $row[0]; else $dt = '2015-01-01';
$f_dtfr = isset($_POST['p_date_from']) ? date('Y-m-d', strtotime($_POST['p_date_from'])) : $dt;
echo '<article><p>Отчёт №6, по пользователям
с <input type="date" id="p_date_from" placeholder="Дата" value="' . $f_dtfr . '">
по <input type="date" id="p_date_to" placeholder="Дата" value="' . $f_dtto . '">
<input type="button" value="Отчёт" onclick="get_report(\'report6\')">
<input type="button" value="Закрыть" onclick="id_close(\'report\')"></p>';
echo '<table><tr><th>Группа';
$res0 = byQu("SELECT users_id, users.name, SUM(op_summ) as summ
	FROM money
	LEFT JOIN users ON users_id=users.id
	WHERE op_date>='$f_dtfr' AND op_date<='$f_dtto'
	GROUP BY users_id
	ORDER BY users.name");
while ($row0 = $res0->fetch_assoc())
	echo '<th>' . $row0['name'];
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
			AND grups_id=" . $row1['grups_id'] . " AND users_id='" . $row0['users_id'] . "'");
		if ($row2 = $res2->fetch_assoc()) {
			echo '<td class="edit num" onclick="money_table(6,' . $row1['grups_id'] . ',\'&f_users_id=' . $row0['users_id'] . '\')">';
			echo $row2['summ'] ?: '0.00';
		}
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
<canvas id="Chart6" width="500" height="300"></canvas>
<script id='chartjs'>
var ctx = document.getElementById("Chart6");
var myChart = new Chart(ctx, {
	type: 'bar',
	data: {
		datasets: [
<?php
$us = '';
$res1->data_seek(0);
while ($row1 = $res1->fetch_assoc()) $us .= '"' . $row1['name'] . '",';
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
		$res2 = byQu("SELECT ABS(SUM(op_summ)) as summ
			FROM money
			WHERE op_date>='$f_dtfr' AND op_date<='$f_dtto'
			AND grups_id=" . $row1['grups_id'] . " AND users_id='" . $row0['users_id'] . "'");
		if ($row2 = $res2->fetch_assoc()) echo ($row2['summ'] ?: '0.00') . ',';
	}
	echo ']
	},';
}
echo '],labels: [' . $us . ']';
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
