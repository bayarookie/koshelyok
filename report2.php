<?php include 'db.php';?>
<article><p>Отчёт средний
<input type="button" value="Закрыть" onclick="id_close('report2')"></p>
<?php
if (isset($_POST['to'])) $f_dtto = date('Y-m-d', strtotime($_POST['to'])); else $f_dtto = date('Y-m-d');
if (isset($_POST['from'])) $f_dtfr = date('Y-m-d', strtotime($_POST['from'])); else $f_dtfr = date('Y-m-d', strtotime($f_dtto . ' -6 month'));
echo '<figure class="report"><figcaption>с ' . $f_dtfr . ' по ' . $f_dtto . '</figcaption><table><tr><th>Группа<th>Сумма';
$sm = 0;
$result = byQu($mysqli,
	"SELECT id, name, AVG(summ) AS summ
		FROM (SELECT DATE_FORMAT(op_date,'%Y-%m') as mo, groups.id, groups.name, SUM(op_summ) as summ
		FROM money
		LEFT JOIN goods ON money.goods_id=goods.id
		LEFT JOIN groups ON goods.groups_id=groups.id
		WHERE money.op_date>='$f_dtfr' AND money.op_date<='$f_dtto'
		GROUP BY mo, groups.id ORDER BY mo, groups.name) AS a
		GROUP BY id");
while ($row = $result->fetch_assoc()) {
	$sm = $sm + floatval($row['summ']);
	if (floatval($row['summ']) < 0) echo '<tr class="minus">'; else echo '<tr class="plus">';
	echo '<td>' . $row['name'] . '<td align="right">' . $row['summ'];
}
if ($sm < 0) echo '<tr class="minus">'; else echo '<tr class="plus">';
echo '<td>Итого<td align="right">' . $sm;
?>
</table></figure></article>
