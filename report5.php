<?php
$f_dtto = isset($_POST['to']) ? date('Y-m-d', strtotime($_POST['to'])) : date('Y-m-d');
$result = byQu("SELECT MIN(op_date) FROM money");
if ($row = $result->fetch_row()) $dt = $row[0]; else $dt = '2015-01-01';
$f_dtfr = isset($_POST['from']) ? date('Y-m-d', strtotime($_POST['from'])) : $dt;
echo '<article><p>Отчёт №5, по конторам
с <input type="date" id="p_date_from" placeholder="Дата" value="' . $f_dtfr . '" autofocus>
по <input type="date" id="p_date_to" placeholder="Дата" value="' . $f_dtto . '">
<input type="button" value="Отчёт" onclick="get_report(\'report5\')"> 
<input type="button" value="Закрыть" onclick="id_close(\'report\')"></p>';
echo '<figure><figcaption>с ' . $f_dtfr . ' по ' . $f_dtto . '</figcaption>
<table><tr><th>Группа<th>Контора<th>Сумма';
$sm = 0; $sg = ''; $su = 0; 
$result = byQu("SELECT servs_id, grups.name, IF(servs.comment='',servs.name,servs.comment) AS gnam, SUM(op_summ) as summ
	FROM money
	LEFT JOIN servs ON money.servs_id=servs.id
	LEFT JOIN grups ON money.grups_id=grups.id
	WHERE op_date>='$f_dtfr' AND op_date<='$f_dtto'
	GROUP BY grups.id, servs_id
	ORDER BY grups.name, servs.name");
while ($row = $result->fetch_assoc()) {
	if ($sg == $row['name']) {
		$su = $su + floatval($row['summ']);
	} else {
		if ($sg <> '') echo '<tr><td><td>Итого<td class="num">' . number_format($su, 2, '.', '');
		$sg = $row['name'];
		$su = floatval($row['summ']);
	}
	$sm = $sm + floatval($row['summ']);
	echo '<tr class="' . ((floatval($row['summ']) < 0) ? 'minus' : 'plus') . '">';
	echo '<td>' . $row['name'] . '<td>' . $row['gnam'];
	echo '<td class="edit num" onclick="money_table(5,' . $row['servs_id'] . ')">';
	echo number_format($row['summ'], 2, '.', '');
}
if ($sg <> '') echo '<tr><td><td>Итого<td class="num">' . number_format($su, 2, '.', '');
echo '<tr class="' . (($sm < 0) ? 'minus' : 'plus') . '">';
echo '<td><td>Итого<td class="num">' . number_format($sm, 2, '.', '');
?>
</table></figure></article>
