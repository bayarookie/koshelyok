<?php include 'db.php';?>
<article><p>Операции
<input type="button" value="Добавить" onclick="get_form('money_form', -1)">
<input type="button" value="Закрыть" onclick="id_close('money_table')"></p>
<table><tr><th><th>Дата<th>Сумма<th><th>Категория<th>Группа<th>Комментарий<th>Кошелёк
<?php

//фильтр по дате за последний месяц или как, по категории, по группе
$f = isset($_POST['f']) ? intval($_POST['f']) : 1;
if ($f == 1) {
	$f_dtto = isset($_POST['to']) ? date('Y-m-d', strtotime($_POST['to'])) : date('Y-m-d');
	$f_dtfr = isset($_POST['from']) ? date('Y-m-d', strtotime($_POST['from'])) : date('Y-m-d', strtotime($f_dtto . ' -1 month'));
	$f_goods_id = isset($_POST['f_goods_id']) ? intval($_POST['f_goods_id']) : -1;
	$f_groups_id = isset($_POST['f_groups_id']) ? intval($_POST['f_groups_id']) : -1;
	$f_walls_id = isset($_POST['f_walls_id']) ? intval($_POST['f_walls_id']) : -1;
} elseif ($f == 2) {
	if (isset($_POST['mo'])) {
		$f_dtto = date('Y-m-d', strtotime($_POST['mo'] . '-01 +1 month -1 day'));
		$f_dtfr = date('Y-m-d', strtotime($_POST['mo'] . '-01'));
	}
	$f_goods_id = -1;
	$f_groups_id = isset($_POST['f_groups_id']) ? intval($_POST['f_groups_id']) : -1;
	$f_walls_id = -1;
} elseif ($f == 3) {
	$f_dtto = date('Y-m-d');
	$f_dtfr = date('Y-m-d', strtotime($f_dtto . ' -1 year'));
	$f_goods_id = isset($_POST['f_goods_id']) ? intval($_POST['f_goods_id']) : -1;
	$f_groups_id = -1;
	$f_walls_id = -1;
} else {
	$f_goods_id = -1;
	$f_groups_id = -1;
	$f_walls_id = -1;
}
$filter = "";
if ($f_goods_id > -1) $filter .= " AND goods_id=" . $f_goods_id;
if ($f_groups_id > -1) $filter .= " AND groups_id=" . $f_groups_id;
if ($f_walls_id > -1) $filter .= " AND walls_id=" . $f_walls_id;

//остаток на начало
$result = byQu($mysqli,
	"SELECT SUM(op_summ) as summ, walls.name FROM money
		LEFT JOIN goods ON money.goods_id=goods.id
		LEFT JOIN groups ON goods.groups_id=groups.id
		LEFT JOIN walls ON money.walls_id=walls.id
		WHERE money.op_date<'$f_dtfr'$filter
		GROUP BY walls_id");
while ($row = $result->fetch_assoc()) {
	echo '<tr><td>На начало<td>' . $f_dtfr . '<td align="right">' . $row['summ'] . '<td><td><td><td><td>' . $row['name'];
}

//движение денег
$result = byQu($mysqli,
	"SELECT money.*,
		IF(money.op_summ>=0,money.op_summ,NULL) AS summ1, IF(money.op_summ<0,money.op_summ,NULL) AS summ2,
		goods.name as goods_name, groups.name as groups_name, walls.name as walls_name
		FROM money
		LEFT JOIN goods ON money.goods_id=goods.id
		LEFT JOIN groups ON goods.groups_id=groups.id
		LEFT JOIN walls ON money.walls_id=walls.id
		WHERE money.op_date>='$f_dtfr' AND money.op_date<='$f_dtto'$filter
		ORDER BY money.op_date");
while ($row = $result->fetch_assoc()) {
	$summ = floatval($row['op_summ']);
	if ($summ < 0) echo '<tr class="minus">'; else echo '<tr class="plus">';
	echo '<td class="edit" onclick="get_form(\'money_form\', ' . $row['id'] . ')">Редактировать';
	echo '<td>' . $row['op_date'];
	echo '<td align="right">' . $row['summ1'];
	echo '<td align="right">' . $row['summ2'];
	echo '<td>' . $row['goods_name'];
	echo '<td>' . $row['groups_name'];
	echo '<td>' . $row['comment'];
	echo '<td>' . $row['walls_name'];
}

//сумма движения денег
$result = byQu($mysqli,
	"SELECT SUM(if(op_summ>0,op_summ,0)) as summ1, SUM(if(op_summ<0,op_summ,0)) as summ2, walls.name
		FROM money
		LEFT JOIN goods ON money.goods_id=goods.id
		LEFT JOIN groups ON goods.groups_id=groups.id
		LEFT JOIN walls ON money.walls_id=walls.id
		WHERE money.op_date>='$f_dtfr' and money.op_date<='$f_dtto'$filter
		GROUP BY walls_id");
while ($row = $result->fetch_assoc()) {
	echo '<tr><td>Сумма<td><td align="right">' . $row['summ1'] . '<td align="right">' . $row['summ2'] . '<td><td><td><td>'. $row['name'];
}

//итого движение денег
$result = byQu($mysqli,
	"SELECT SUM(op_summ) as summ
		FROM money
		LEFT JOIN goods ON money.goods_id=goods.id
		LEFT JOIN groups ON goods.groups_id=groups.id
		LEFT JOIN walls ON money.walls_id=walls.id
		WHERE money.op_date>='$f_dtfr' and money.op_date<='$f_dtto'$filter");
if ($row = $result->fetch_assoc()) {
	$summ = floatval($row['summ']);
	if ($summ < 0) echo '<tr class="minus">'; else echo '<tr class="plus">';
	echo '<td>Итого<td><td><td align="right">' . $row['summ'] . '<td><td><td><td>';
}

if ($f_dtto != date('Y-m-d')) {
//остаток на день
$result = byQu($mysqli,
	"SELECT SUM(op_summ) as summ, walls.name, MAX(op_date) as dt
		FROM money
		LEFT JOIN goods ON money.goods_id=goods.id
		LEFT JOIN groups ON goods.groups_id=groups.id
		LEFT JOIN walls ON money.walls_id=walls.id
		WHERE money.op_date<='$f_dtto'$filter
		GROUP BY walls_id");
while ($row = $result->fetch_assoc()) {
	echo '<tr><td>Остаток<td>' . $row['dt'] . '<td><td align="right">' . $row['summ'] . '<td><td><td><td>'. $row['name'];
}}

//остаток
$result = byQu($mysqli,
	"SELECT SUM(op_summ) as summ, walls.name, MAX(op_date) as dt
		FROM money
		LEFT JOIN goods ON money.goods_id=goods.id
		LEFT JOIN groups ON goods.groups_id=groups.id
		LEFT JOIN walls ON money.walls_id=walls.id
		WHERE true$filter
		GROUP BY walls_id");
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
$result = byQu($mysqli,
	"SELECT goods.id, goods.name, groups.name as groups_name FROM goods
		LEFT JOIN groups ON goods.groups_id=groups.id
		ORDER BY groups.name, goods.name");
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
$result = byQu($mysqli, "SELECT id, name FROM groups ORDER BY name");
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
$result = byQu($mysqli, "SELECT id, name FROM walls");
while ($row = $result->fetch_assoc()) {
	echo '<option';
	if ($row['id'] == $f_walls_id) echo ' selected';
	echo ' value="' . $row['id'] . '">' . $row['name'] . '</option>';
}
?>
</select></p>

<p><input type="button" value="Обновить" onclick="money_table(1)"></p></div>
<p>Операции
<input type="button" value="Добавить" onclick="get_form('money_form', -1)">
<input type="button" value="Закрыть" onclick="id_close('money_table')"></p>
</article>
