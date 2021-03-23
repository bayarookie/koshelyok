<?php
$f_dtto = date('Y-m-d', strtotime($_POST['p_date_to'] ?? 'now'));
$f_dtfr = date('Y-m-d', strtotime($_POST['p_date_from'] ?? byDt('MIN')));
echo '<article><p>Отчёт №9, по пользователям, группам
с <input type="date" value="' . $f_dtfr . '" name="p_date_from" placeholder="Дата">
по <input type="date" value="' . $f_dtto . '" name="p_date_to" placeholder="Дата">
<input type="button" value="Отчёт" onclick="get_report(\'report9\')">
<input type="button" value="Закрыть" onclick="id_close(\'report\')"></p>';
echo '<table><tr><th>Группа';
$res0 = byQu("SELECT users_id, users.name, SUM(op_summ) as summ
	FROM money
	LEFT JOIN walls ON money.walls_id=walls.id
	LEFT JOIN users ON walls.users_id=users.id
	WHERE op_date>='$f_dtfr' AND op_date<='$f_dtto'
	GROUP BY users_id
	ORDER BY users.name");
while ($row0 = $res0->fetch_assoc())
	echo '<th>' . $row0['name'];
echo '<th>Сумма';
$sm = 0;
$res1 = byQu("SELECT bgrup_id, bgrup.name, SUM(op_summ) as summ
	FROM money
	LEFT JOIN servs ON money.servs_id=servs.id
	LEFT JOIN grups ON servs.grups_id=grups.id
	LEFT JOIN bgrup ON grups.bgrup_id=bgrup.id
	WHERE op_date>='$f_dtfr' AND op_date<='$f_dtto'
	GROUP BY bgrup_id
	ORDER BY summ DESC");
while ($row1 = $res1->fetch_assoc()) {
	echo '<tr><td>' . $row1['name'];
	$res0->data_seek(0);
	$g = ($row1['bgrup_id'] ?? '-1');
	while ($row0 = $res0->fetch_assoc()) {
		$res2 = byQu("SELECT SUM(op_summ) as summ
			FROM money
			LEFT JOIN servs ON money.servs_id=servs.id
			LEFT JOIN grups ON servs.grups_id=grups.id
			LEFT JOIN walls ON money.walls_id=walls.id
			WHERE op_date>='$f_dtfr' AND op_date<='$f_dtto'
			AND bgrup_id=" . $g . " AND users_id=" . $row0['users_id']);
		if ($row2 = $res2->fetch_assoc()) {
			echo '<td class="edit num" onclick="money_table(7,' . $g . ',\'&f_users_id=' . $row0['users_id'] . '\')">';
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
<script id='js'>
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
			LEFT JOIN servs ON money.servs_id=servs.id
			LEFT JOIN grups ON servs.grups_id=grups.id
			LEFT JOIN walls ON money.walls_id=walls.id
			WHERE op_date>='$f_dtfr' AND op_date<='$f_dtto'
			AND bgrup_id=" . ($row1['bgrup_id'] ?? '-1') . " AND users_id=" . $row0['users_id']);
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
