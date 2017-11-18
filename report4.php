<?php
$f_dtto = isset($_POST['to']) ? date('Y-m-d', strtotime($_POST['to'])) : date('Y-m-d');
$result = byQu($mysqli, "SELECT MIN(op_date) FROM money");
if ($row = $result->fetch_row()) $dt = $row[0]; else $dt = '2015-01-01';
$f_dtfr = isset($_POST['from']) ? date('Y-m-d', strtotime($_POST['from'])) : $dt;
echo '<article><p>Отчёт по группам
с <input type="date" id="p_date_from" placeholder="Дата" value="' . $f_dtfr . '" autofocus>
по <input type="date" id="p_date_to" placeholder="Дата" value="' . $f_dtto . '">
<input type="button" value="Отчёт" onclick="get_report(\'report4\')"> 
<input type="button" value="Закрыть" onclick="id_close(\'report\')"></p>';
echo '<figure class="report"><figcaption>с ' . $f_dtfr . ' по ' . $f_dtto . '</figcaption>
<table><tr><th>Группа<th>Сумма';
$sm = 0;
$result = byQu($mysqli,
	"SELECT groups.name, SUM(op_summ) as summ
		FROM money
		LEFT JOIN goods ON money.goods_id=goods.id
		LEFT JOIN groups ON goods.groups_id=groups.id
		WHERE money.op_date>='$f_dtfr' AND money.op_date<='$f_dtto'
		GROUP BY groups.id
		ORDER BY groups.name");
while ($row = $result->fetch_assoc()) {
	$sm = $sm + floatval($row['summ']);
	if (floatval($row['summ']) < 0) echo '<tr class="minus">'; else echo '<tr class="plus">';
	echo '<td>' . $row['name'] . '<td class="num">' . number_format($row['summ'], 2, '.', '');
}
if ($sm < 0) echo '<tr class="minus">'; else echo '<tr class="plus">';
echo '<td>Итого<td class="num">' . number_format($sm, 2, '.', '');
?>
</table></figure></article>
