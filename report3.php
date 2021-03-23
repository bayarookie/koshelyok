<?php
$f_dtto = date('Y-m-d', strtotime($_POST['p_date_to'] ?? 'first day of this month -1 day'));
$f_dtfr = date('Y-m-d', strtotime($_POST['p_date_from'] ?? 'first day of this month -1 year'));
$d1 = strtotime($f_dtfr);
$d2 = strtotime($f_dtto . ' +1 day');
$d = 0;
while (($d1 = strtotime(' +1 month', $d1)) <= $d2) $d++;
echo '<article><p>Отчёт №3, в среднем
с <input type="date" value="' . $f_dtfr . '" name="p_date_from" placeholder="Дата">
по <input type="date" value="' . $f_dtto . '" name="p_date_to" placeholder="Дата">
<input type="button" value="Отчёт" onclick="get_report(\'report3\')"> 
<input type="button" value="Закрыть" onclick="id_close(\'report\')"></p>';
echo '<figure><figcaption>месяцев = ' . $d . '</figcaption><table><tr><th>Группа<th>Сумма';
$sm = 0;
$co = '';
$sd = '';
$gr = '';
$res0 = byQu("SELECT grups_id, grups.name, SUM(op_summ) AS summ
	FROM money
	LEFT JOIN servs ON money.servs_id=servs.id
	LEFT JOIN grups ON servs.grups_id=grups.id
	WHERE op_date>='$f_dtfr' AND op_date<='$f_dtto'
	GROUP BY grups_id
	ORDER by summ DESC");
while ($row0 = $res0->fetch_assoc()) {
	$c = byCo();
	$co .= '"' . $c . '",';
	$sd .= abs($row0['summ'] ?: '0.00') . ',';
	$gr .= '"' . $row0['name'] . '",';
	$res1 = byQu("SELECT SUM(op_summ) as summ
		FROM money
		LEFT JOIN servs ON money.servs_id=servs.id
		WHERE op_date>='$f_dtfr' AND op_date<='$f_dtto' AND grups_id=" . $row0['grups_id']);
	if ($row1 = $res1->fetch_assoc()) {
		$s = floatval($row1['summ'])/$d;
		$sm = $sm + $s;
	}
	echo '<tr style="background-color:' . $c . ';">';
	echo '<td>' . $row0['name'] . '<td class="num">' . number_format($s, 2, '.', '');
}
echo '<tr><td>Итого<td class="' . (($sm < 0) ? 'minus' : 'plus') . ' num">' . number_format($sm, 2, '.', '');
?>
</table></figure><canvas id="Chart4" width="500" height="300"></canvas>
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

