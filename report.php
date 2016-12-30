<?php include 'db.php';?>
<article><p>Отчёт помесячно
<input type="button" value="Закрыть" onclick="id_close('report')"></p>
<?php
if (isset($_POST['to'])) $f_dtto = date('Y-m-d', strtotime($_POST['to'])); else $f_dtto = date('Y-m-d');
if (isset($_POST['from'])) $f_dtfr = date('Y-m-d', strtotime($_POST['from'])); else $f_dtfr = date('Y-m-d', strtotime($f_dtto . ' -6 month'));
$mo = "";
$sm = 0;
$result = byQu($mysqli,
	"SELECT DATE_FORMAT(op_date,'%Y-%m') as mo, groups.id, groups.name, SUM(op_summ) as summ
		FROM money
		LEFT JOIN goods ON money.goods_id=goods.id
		LEFT JOIN groups ON goods.groups_id=groups.id
		WHERE money.op_date>='$f_dtfr' AND money.op_date<='$f_dtto'
		GROUP BY mo, groups.id ORDER BY mo, groups.name");
while ($row = $result->fetch_assoc()) {
	if ($mo != $row['mo']) {
		if ($mo != "") {
			if ($sm < 0) echo '<tr class="minus">'; else echo '<tr class="plus">';
			echo '<td>Итого<td align="right">' . $sm;
			echo '</table></figure>';
		}
		$mo = $row['mo'];
		$sm = floatval($row['summ']);
		echo '<figure class="report"><figcaption>' . $mo . '</figcaption><table><tr><th>Группа<th>Сумма';
	} else $sm = $sm + floatval($row['summ']);
	if (floatval($row['summ']) < 0) echo '<tr class="minus">'; else echo '<tr class="plus">';
	echo '<td>' . $row['name'] . '<td class="edit" align="right" onclick="money_table(2,' . $row['id'] . ',\'' . $row['mo'] . '\')">' . $row['summ'];
}
if ($sm < 0) echo '<tr class="minus">'; else echo '<tr class="plus">';
echo '<td>Итого<td align="right">' . $sm;
?>
</table></figure></article>
