<?php
$f_dtto = isset($_POST['to']) ? date('Y-m-d', strtotime($_POST['to'])) : date('Y-m-d', strtotime(date('Y-m-') . '01 -1 day'));
$f_dtfr = isset($_POST['from']) ? date('Y-m-d', strtotime($_POST['from'])) : date('Y-m-d', strtotime(date('Y-m-') . '01 -6 month'));
$d1 = strtotime($f_dtfr);
$d2 = strtotime($f_dtto . ' +1 day');
$d = 0;
while (($d1 = strtotime(' +1 month', $d1)) <= $d2) $d++;
echo '<article><p>Отчёт №3, в среднем
с <input type="date" id="p_date_from" placeholder="Дата" value="' . $f_dtfr . '">
по <input type="date" id="p_date_to" placeholder="Дата" value="' . $f_dtto . '">
<input type="button" value="Отчёт" onclick="get_report(\'report3\')"> 
<input type="button" value="Закрыть" onclick="id_close(\'report\')"></p>';
echo '<figure><figcaption>месяцев = ' . $d . '</figcaption><table><tr><th>Группа<th>Сумма';
$sm = 0;
$res0 = byQu("SELECT grups_id, grups.name, SUM(op_summ) AS summ
	FROM money
	LEFT JOIN grups ON grups_id=grups.id
	WHERE op_date>='$f_dtfr' AND op_date<='$f_dtto'
	GROUP BY grups_id
	ORDER by summ DESC");
while ($row0 = $res0->fetch_assoc()) {
	$res1 = byQu("SELECT SUM(op_summ) as summ
		FROM money
		WHERE op_date>='$f_dtfr' AND op_date<='$f_dtto' AND grups_id=" . $row0['grups_id']);
	if ($row1 = $res1->fetch_assoc()) {
		$s = floatval($row1['summ'])/$d;
		$sm = $sm + $s;
	}
	echo '<tr class="' . (($s < 0) ? 'minus' : 'plus') . '">';
	echo '<td>' . $row0['name'] . '<td class="num">' . number_format($s, 2, '.', '');
}
echo '<tr><td>Итого<td class="' . (($sm < 0) ? 'minus' : 'plus') . ' num">' . number_format($sm, 2, '.', '');
?>
</table></figure></article>
