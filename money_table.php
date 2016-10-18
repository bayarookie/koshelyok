<?php
include 'db.php';
echo '<div><h3>Операции</h3>';
echo '<table><tr><th><th>Дата<th>Сумма<th><th>Категория<th>Группа<th>Комментарий<th>Кошелёк';

//фильтр
$filter = "";
if ($f_goods_id > -1) {
	$filter .= " AND goods_id=" . $f_goods_id;
}
if ($f_groups_id > -1) {
	$filter .= " AND groups_id=" . $f_groups_id;
}
if ($f_walls_id > -1) {
	$filter .= " AND walls_id=" . $f_walls_id;
}

//остаток на начало
$query = "SELECT SUM(op_summ) as summ, walls.name FROM money"
		." LEFT JOIN goods ON money.goods_id=goods.id"
		." LEFT JOIN groups ON goods.groups_id=groups.id"
		." LEFT JOIN walls ON money.walls_id=walls.id"
		." WHERE money.op_date<'$f_dtfr'" . $filter
		." GROUP BY walls_id";
$result = byQu($mysqli, $query);
while ($row = $result->fetch_assoc()) {
	echo '<tr><td>На начало<td>' . $f_dtfr . '<td align="right">' . $row['summ'] . '<td><td><td><td><td>' . $row['name'];
}

//движение денег
$query = "SELECT money.*,"
		." IF(money.op_summ>=0,money.op_summ,NULL) AS summ1, IF(money.op_summ<0,money.op_summ,NULL) AS summ2,"
		." goods.name as goods_name, groups.name as groups_name, walls.name as walls_name"
		." FROM money"
		." LEFT JOIN goods ON money.goods_id=goods.id"
		." LEFT JOIN groups ON goods.groups_id=groups.id"
		." LEFT JOIN walls ON money.walls_id=walls.id"
		." WHERE money.op_date>='$f_dtfr' AND money.op_date<='$f_dtto'" . $filter
		." ORDER BY money.op_date";
$result = byQu($mysqli, $query);
while ($row = $result->fetch_assoc()) {
	$summ = floatval($row['op_summ']);
	if ($summ < 0) {echo '<tr class="minus">';} else {echo '<tr class="plus">';}
	echo '<td><input type="button" value="Редактировать" onclick="money_form(' . $row['id'] . ')">';
	echo '<td>' . $row['op_date'];
	echo '<td align="right">' . $row['summ1'];
	echo '<td align="right">' . $row['summ2'];
	echo '<td>' . $row['goods_name'];
	echo '<td>' . $row['groups_name'];
	echo '<td>' . $row['comment'];
	echo '<td>' . $row['walls_name'];
}

//сумма движения денег
$query = "SELECT SUM(if(op_summ>0,op_summ,0)) as summ1, SUM(if(op_summ<0,op_summ,0)) as summ2, walls.name"
		." FROM money"
		." LEFT JOIN goods ON money.goods_id=goods.id"
		." LEFT JOIN groups ON goods.groups_id=groups.id"
		." LEFT JOIN walls ON money.walls_id=walls.id"
		." WHERE money.op_date>='$f_dtfr' and money.op_date<='$f_dtto'" . $filter
		." GROUP BY walls_id";
$result = byQu($mysqli, $query);
while ($row = $result->fetch_assoc()) {
	echo '<tr><td>Сумма<td><td align="right">' . $row['summ1'] . '<td align="right">' . $row['summ2'] . '<td><td><td><td>'. $row['name'];
}

//итого движение денег
$query = "SELECT SUM(op_summ) as summ"
		." FROM money"
		." LEFT JOIN goods ON money.goods_id=goods.id"
		." LEFT JOIN groups ON goods.groups_id=groups.id"
		." LEFT JOIN walls ON money.walls_id=walls.id"
		." WHERE money.op_date>='$f_dtfr' and money.op_date<='$f_dtto'" . $filter;
$result = byQu($mysqli, $query);
if ($row = $result->fetch_assoc()) {
	$summ = floatval($row['summ']);
	if ($summ < 0) {echo '<tr class="minus">';} else {echo '<tr class="plus">';}
	echo '<td>Итого<td><td><td align="right">' . $row['summ'] . '<td><td><td><td>';
}

//остаток
$query = "SELECT SUM(op_summ) as summ, walls.name, MAX(op_date) as dt"
		." FROM money"
		." LEFT JOIN goods ON money.goods_id=goods.id"
		." LEFT JOIN groups ON goods.groups_id=groups.id"
		." LEFT JOIN walls ON money.walls_id=walls.id"
		." WHERE true" . $filter
		." GROUP BY walls_id";
$result = byQu($mysqli, $query);
while ($row = $result->fetch_assoc()) {
	echo '<tr><td>Остаток<td>' . $row['dt'] . '<td><td align="right">' . $row['summ'] . '<td><td><td><td>'. $row['name'];
}
echo '</table>';

//фильтр по дате
echo '<div><p>Фильтр: с <input type="date" name="date_from" id="date_from" placeholder="гггг-мм-дд" value="' . $f_dtfr . '">';
echo 'по <input type="date" name="date_to" id="date_to" placeholder="гггг-мм-дд" value="' . $f_dtto . '"></p>';

//фильтр по категории
echo '<p>категория: <select size="1" name="f_goods_id" id="f_goods_id">';
echo '<option';
if (-1 == $f_goods_id) echo ' selected';
echo ' value="-1">Все</option>';
$query = "SELECT goods.id, goods.name, groups.name as groups_name FROM goods"
		." LEFT JOIN groups ON goods.groups_id=groups.id"
		." ORDER BY groups.name, goods.name";
$result = byQu($mysqli, $query);
while ($row = $result->fetch_assoc()) {
	echo '<option';
	if ($row['id'] == $f_goods_id) echo ' selected';
	echo ' value="' . $row['id'] . '">';
	if ($row['groups_name'] != '') echo $row['groups_name'] . ' - ';
	echo $row['name'] . '</option>';
}
echo '</select></p>';

//фильтр по группе
echo '<p>группа: <select size="1" name="f_groups_id" id="f_groups_id">';
echo '<option';
if (-1 == $f_groups_id) echo ' selected';
echo ' value="-1">Все</option>';
$query = "SELECT id, name FROM groups ORDER BY name";
$result = byQu($mysqli, $query);
while ($row = $result->fetch_assoc()) {
	echo '<option';
	if ($row['id'] == $f_groups_id) echo ' selected';
	echo ' value="' . $row['id'] . '">' . $row['name'] . '</option>';
}
echo '</select></p>';

//фильтр по кошельку
echo '<p>кошелёк: <select size="1" name="f_walls_id" id="f_walls_id">';
echo '<option';
if (-1 == $f_walls_id) echo ' selected';
echo ' value="-1">Все</option>';
$query = "SELECT id, name FROM walls";
$result = byQu($mysqli, $query);
while ($row = $result->fetch_assoc()) {
	echo '<option';
	if ($row['id'] == $f_walls_id) echo ' selected';
	echo ' value="' . $row['id'] . '">' . $row['name'] . '</option>';
}
echo '</select></p>';
?>
<p><input type="button" value="Обновить" onclick="money_table(1)"></p></div>
<input type="button" value="Добавить" onclick="money_form(-1)">
<input type="button" value="Закрыть" onclick="id_close('money_table')">