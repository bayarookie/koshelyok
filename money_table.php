<?php include 'db.php';?>
<article><p>Операции
<input type="button" value="Добавить" onclick="get_form('money_form', -1)">
<input type="button" value="Закрыть" onclick="id_close('money_table')"></p>
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

//сортировка
echo '<table><tr><th class="edit" onclick="money_table(4, 0)">';
$order = "";
$o = isset($_POST['o']) ? intval($_POST['o']) : 1;

//по дате
echo '<th class="edit" onclick="money_table(4, ';
if ($o == 1) {
	$order = "ORDER BY money.op_date";
	echo '2)">↑';
} elseif ($o == 2) {
	$order = "ORDER BY money.op_date DESC";
	echo '1)">↓';
} else {
	echo '1)">';
}
echo 'Дата';

//по сумме
echo '<th class="edit" colspan="2" onclick="money_table(4, ';
if ($o == 3) {
	$order = "ORDER BY money.op_summ";
	echo '4)">↑';
} elseif ($o == 4) {
	$order = "ORDER BY money.op_summ DESC";
	echo '3)">↓';
} else {
	echo '4)">';
}
echo 'Сумма';

//по категориям
echo '<th class="edit" onclick="money_table(4, ';
if ($o == 5) {
	$order = "ORDER BY goods.name";
	echo '6)">↑';
} elseif ($o == 6) {
	$order = "ORDER BY goods.name DESC";
	echo '5)">↓';
} else {
	echo '5)">';
}
echo 'Категория';

//по группам
echo '<th class="edit" onclick="money_table(4, ';
if ($o == 7) {
	$order = "ORDER BY groups.name";
	echo '8)">↑';
} elseif ($o == 8) {
	$order = "ORDER BY groups.name DESC";
	echo '7)">↓';
} else {
	echo '7)">';
}
echo 'Группа';

//по комментам
echo '<th class="edit" onclick="money_table(4, ';
if ($o == 9) {
	$order = "ORDER BY money.comment";
	echo '10)">↑';
} elseif ($o == 10) {
	$order = "ORDER BY money.comment DESC";
	echo '9)">↓';
} else {
	echo '9)">';
}
echo 'Комментарий';

//по кошелькам
echo '<th class="edit" onclick="money_table(4, ';
if ($o == 11) {
	$order = "ORDER BY walls.name";
	echo '12)">↑';
} elseif ($o == 12) {
	$order = "ORDER BY walls.name DESC";
	echo '11)">↓';
} else {
	echo '11)">';
}
echo 'Кошелёк';

//по группам, категориям
if ($o == 13) {
	$order = "ORDER BY groups.name, goods.name";
} elseif ($o == 14) {
	$order = "ORDER BY groups.name DESC, goods.name DESC";
}

//по кошелькам, дате
if ($o == 15) {
	$order = "ORDER BY walls.name, money.op_date";
} elseif ($o == 16) {
	$order = "ORDER BY walls.name DESC, money.op_date DESC";
}

//таблица, остаток на начало
$result = byQu($mysqli,
	"SELECT SUM(op_summ) as summ, walls.name FROM money
		LEFT JOIN goods ON money.goods_id=goods.id
		LEFT JOIN groups ON goods.groups_id=groups.id
		LEFT JOIN walls ON money.walls_id=walls.id
		WHERE money.op_date<'$f_dtfr'$filter
		GROUP BY walls_id");
while ($row = $result->fetch_assoc()) {
	echo '<tr><td>На начало<td>' . $f_dtfr . '<td class="num">' . $row['summ'] . '<td><td><td><td><td>' . $row['name'];
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
		$order");
while ($row = $result->fetch_assoc()) {
	$summ = floatval($row['op_summ']);
	if ($summ < 0) echo '<tr class="minus">'; else echo '<tr class="plus">';
	echo '<td class="edit" onclick="get_form(\'money_form\', ' . $row['id'] . ')">Редактировать';
	echo '<td>' . $row['op_date'];
	echo '<td class="num">' . $row['summ1'];
	echo '<td class="num">' . $row['summ2'];
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
	echo '<tr><td>Сумма<td><td class="num">' . $row['summ1'] . '<td class="num">' . $row['summ2'] . '<td><td><td><td>'. $row['name'];
}

//итого движение денег
$result = byQu($mysqli,
	"SELECT SUM(op_summ) as summ
		FROM money
		LEFT JOIN goods ON money.goods_id=goods.id
		LEFT JOIN groups ON goods.groups_id=groups.id
		WHERE money.op_date>='$f_dtfr' and money.op_date<='$f_dtto'$filter");
if ($row = $result->fetch_assoc()) {
	$summ = floatval($row['summ']);
	if ($summ < 0) echo '<tr class="minus">'; else echo '<tr class="plus">';
	echo '<td>Итого<td><td><td class="num">' . $row['summ'] . '<td><td><td><td>';
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
	echo '<tr><td>Остаток<td>' . $row['dt'] . '<td><td class="num">' . $row['summ'] . '<td><td><td><td>'. $row['name'];
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
	echo '<tr><td>Остаток<td>' . $row['dt'] . '<td><td class="num">' . $row['summ'] . '<td><td><td><td>'. $row['name'];
}
echo '</table>';

//фильтры
echo '<div>';

//фильтр по дате
echo '<p>Фильтр: с <input type="date" name="date_from" id="date_from" placeholder="гггг-мм-дд" value="' . $f_dtfr . '">';
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
echo '</select></p>';

//сортировка
echo '<p>Cортировка: <select size="1" name="ordr" id="ordr">';
echo '<option'; if (0 == $o) echo ' selected'; echo ' value="0">Без сортировки</option>';
echo '<option'; if (1 == $o) echo ' selected'; echo ' value="1">По дате</option>';
echo '<option'; if (2 == $o) echo ' selected'; echo ' value="2">По дате обратно</option>';
echo '<option'; if (3 == $o) echo ' selected'; echo ' value="3">По сумме</option>';
echo '<option'; if (4 == $o) echo ' selected'; echo ' value="4">По сумме обратно</option>';
echo '<option'; if (5 == $o) echo ' selected'; echo ' value="5">По категориям</option>';
echo '<option'; if (6 == $o) echo ' selected'; echo ' value="6">По категориям обратно</option>';
echo '<option'; if (7 == $o) echo ' selected'; echo ' value="7">По группам</option>';
echo '<option'; if (8 == $o) echo ' selected'; echo ' value="8">По группам обратно</option>';
echo '<option'; if (9 == $o) echo ' selected'; echo ' value="9">По комментариям</option>';
echo '<option'; if (10 == $o) echo ' selected'; echo ' value="10">По комментариям обратно</option>';
echo '<option'; if (11 == $o) echo ' selected'; echo ' value="11">По кошелькам</option>';
echo '<option'; if (12 == $o) echo ' selected'; echo ' value="12">По кошелькам обратно</option>';
echo '<option'; if (13 == $o) echo ' selected'; echo ' value="13">По группам, категориям</option>';
echo '<option'; if (14 == $o) echo ' selected'; echo ' value="14">По группам, категориям обратно</option>';
echo '<option'; if (15 == $o) echo ' selected'; echo ' value="15">По кошелькам, дате</option>';
echo '<option'; if (16 == $o) echo ' selected'; echo ' value="16">По кошелькам, дате обратно</option>';
echo '</select></p>';

?>
<p><input type="button" value="Обновить" onclick="money_table(1)"></p></div>
<p>Операции
<input type="button" value="Добавить" onclick="get_form('money_form', -1)">
<input type="button" value="Закрыть" onclick="id_close('money_table')"></p>
</article>
