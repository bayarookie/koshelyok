<?php
$dtto = strtotime($_POST['p_date_to'] ?? 'now');
$dtfr = strtotime($_POST['p_date_from'] ?? 'first day of this month -3 month');
$f_dtto = date('Y-m-d', $dtto);
$f_dtfr = date('Y-m-d', $dtfr);
$u = intval($_POST['r_users_id'] ?? 1);
$arr['r_users_id'] = $u;
$g = intval($_POST['r_bgrup_id'] ?? 2);
$arr['r_bgrup_id'] = $g;
echo '<article><div class="form"><div><label>Отчёт №10, помесячно с</label>
<input type="date" value="' . $f_dtfr . '" name="p_date_from" placeholder="Дата">
по <input type="date" value="' . $f_dtto . '" name="p_date_to" placeholder="Дата"></div>';
$j = byCb('r_users_id');
$j = byCb('r_bgrup_id');
echo '<div><label> </label> <input type="button" value="Отчёт" onclick="get_report(\'report10\')">
<input type="button" value="Закрыть" onclick="id_close(\'report\')"></div></div>
<table><tr><th>Месяц';
$res0 = byQu("SELECT bgrup_id, bgrup.name, users_id, SUM(op_summ) as summ
	FROM money
	LEFT JOIN servs ON money.servs_id=servs.id
	LEFT JOIN grups ON servs.grups_id=grups.id
	LEFT JOIN bgrup ON grups.bgrup_id=bgrup.id
	LEFT JOIN walls ON money.walls_id=walls.id
	WHERE op_date>='$f_dtfr' AND op_date<='$f_dtto' AND users_id=$u AND bgrup_id=$g");
while ($row0 = $res0->fetch_assoc()) echo '<th>' . $row0['name'];
$gr = "";
$sm = 0;
for($d = $dtfr; $d <= $dtto; $d = $d + 86400){
	$dt = date('Y-m-d', $d);
	echo '<tr><td>' . $dt;
	$res0->data_seek(0);
	while ($row0 = $res0->fetch_assoc()) {
		$res2 = byQu("SELECT SUM(op_summ) as summ
			FROM money
			LEFT JOIN servs ON money.servs_id=servs.id
			LEFT JOIN grups ON servs.grups_id=grups.id
			LEFT JOIN walls ON money.walls_id=walls.id
			WHERE op_date='$dt' AND users_id=$u AND bgrup_id=$g");
		if ($row2 = $res2->fetch_assoc())
			if ($row2['summ'] == '')
				echo '<td class="num">0.00';
			else
				echo '<td class="edit num" onclick="money_table(10,' . $row0['bgrup_id'] . ',\'&users_id=' . $row0['users_id'] . '&p_date_from=' . $dt . '&p_date_to=' . $dt . '\')">' . $row2['summ'];
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
<script id='js'>
var ctx = document.getElementById("Chart1");
var myChart = new Chart(ctx, {
	type: 'line',
	data: {
		datasets: [
<?php
$mo = '';
for($d = $dtfr; $d <= $dtto; $d = $d + 86400){
	$mo .= '"' . date('Y-m-d', $d) . '",';
}
$res0->data_seek(0);
while ($row0 = $res0->fetch_assoc()) {
	$co = byCo();
	echo '{
	label: "' . $row0['name'] . '",
	backgroundColor: "' . $co . '",
	borderColor: "' . $co . '",
	fill: false,
	data: [';
	for($d = $dtfr; $d <= $dtto; $d = $d + 86400){
		$dt = date('Y-m-d', $d);
		$res2 = byQu("SELECT SUM(op_summ) as summ
			FROM money
			LEFT JOIN servs ON money.servs_id=servs.id
			LEFT JOIN grups ON servs.grups_id=grups.id
			LEFT JOIN walls ON money.walls_id=walls.id
			WHERE op_date='$dt' AND users_id=$u AND bgrup_id=$g");
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

<?php echo $j;?>
</script>
</article>
