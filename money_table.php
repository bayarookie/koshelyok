<?php
$t = '<p>Операции
<input type="button" value="Добавить" onclick="get_form(\'edit_form\',-1,\'money\')">
<input type="button" value="Закрыть" onclick="id_close(\'money_table\')"></p>';
echo '<article>' . $t;

//фильтры
$f_goods_id = -1;
$f_groups_id = -1;
$f_walls_id = -1;
$f_users_id = -1;
$f = isset($_POST['f']) ? intval($_POST['f']) : 1;
if ($f == 1) {
	$f_dtto = isset($_POST['to']) ? date('Y-m-d', strtotime($_POST['to'])) : date('Y-m-d');
	$result = byQu($mysqli, "SELECT MAX(op_date) FROM money");
	if ($row = $result->fetch_row()) $f_dtfr = $row[0]; else $f_dtfr = date('Y-m-d');
	$f_dtfr = isset($_POST['from']) ? date('Y-m-d', strtotime($_POST['from'])) : (new DateTime($f_dtfr))->modify('first day of this month -1 month')->format('Y-m-d');
	$f_goods_id = isset($_POST['f_goods_id']) ? intval($_POST['f_goods_id']) : -1;
	$f_groups_id = isset($_POST['f_groups_id']) ? intval($_POST['f_groups_id']) : -1;
	$f_walls_id = isset($_POST['f_walls_id']) ? intval($_POST['f_walls_id']) : -1;
	$f_users_id = isset($_POST['f_users_id']) ? intval($_POST['f_users_id']) : -1;
} elseif ($f == 2) {
	if (isset($_POST['mo'])) {
		$f_dtto = date('Y-m-t', strtotime($_POST['mo'] . '-01'));
		$f_dtfr = date('Y-m-d', strtotime($_POST['mo'] . '-01'));
	}
	$f_groups_id = isset($_POST['f_groups_id']) ? intval($_POST['f_groups_id']) : -1;
} elseif ($f == 3) {
	$f_dtto = date('Y-m-d');
	$result = byQu($mysqli, "SELECT MIN(op_date) FROM money");
	if ($row = $result->fetch_row()) $f_dtfr = $row[0]; else $f_dtfr = '2015-01-01';
	$f_goods_id = isset($_POST['f_goods_id']) ? intval($_POST['f_goods_id']) : -1;
}
$filter = "";
if ($f_goods_id > -1) $filter .= " AND goods_id=" . $f_goods_id;
if ($f_groups_id > -1) $filter .= " AND groups_id=" . $f_groups_id;
if ($f_walls_id > -1) $filter .= " AND walls_id=" . $f_walls_id;
if ($f_users_id > -1) $filter .= " AND users_id=" . $f_users_id;

//сортировка
echo '<table class="money_table"><tr><th class="edit" onclick="money_table(4,0)">';
$order = "";
$o = isset($_POST['o']) ? intval($_POST['o']) : 1;

//по дате
echo '<th class="edit" onclick="money_table(4,';
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
echo '<th class="edit" colspan="2" onclick="money_table(4,';
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

//по конторам
echo '<th class="edit" onclick="money_table(4,';
if ($o == 5) {
	$order = "ORDER BY goods.name";
	echo '6)">↑';
} elseif ($o == 6) {
	$order = "ORDER BY goods.name DESC";
	echo '5)">↓';
} else {
	echo '5)">';
}
echo 'Контора';

//по группам
echo '<th class="edit" onclick="money_table(4,';
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
echo '<th class="edit" onclick="money_table(4,';
if ($o == 9) {
	$order = "ORDER BY money.comment";
	echo '10)">↑';
} elseif ($o == 10) {
	$order = "ORDER BY money.comment DESC";
	echo '9)">↓';
} else {
	echo '9)">';
}
echo 'Описание';

//по кошелькам
echo '<th class="edit" onclick="money_table(4,';
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

//по пользователям
echo '<th class="edit" onclick="money_table(4,';
if ($o == 13) {
	$order = "ORDER BY users.name";
	echo '14)">↑';
} elseif ($o == 14) {
	$order = "ORDER BY users.name DESC";
	echo '13)">↓';
} else {
	echo '13)">';
}
echo 'Юзер';

//по группам, конторам
if ($o == 15) {
	$order = "ORDER BY groups.name, goods.name";
} elseif ($o == 16) {
	$order = "ORDER BY groups.name DESC, goods.name DESC";
}

//по кошелькам, дате
if ($o == 17) {
	$order = "ORDER BY walls.name, money.op_date";
} elseif ($o == 18) {
	$order = "ORDER BY walls.name DESC, money.op_date DESC";
}

//таблица, остаток на начало
$result = byQu($mysqli, "SELECT SUM(op_summ) AS summ, walls.name FROM money
	LEFT JOIN goods ON money.goods_id=goods.id
	LEFT JOIN groups ON goods.groups_id=groups.id
	LEFT JOIN walls ON money.walls_id=walls.id
	LEFT JOIN users ON money.users_id=users.id
	WHERE money.op_date<'$f_dtfr'$filter
	GROUP BY walls_id");
while ($row = $result->fetch_assoc()) {
	echo '<tr><td>На начало<td>' . $f_dtfr . '<td>' . $row['summ'] . '<td><td><td><td><td>' . $row['name'] . '<td>';
}

//движение денег
$res2 = byQu($mysqli, "SELECT money.id, money.op_date, money.comment,
	IF(money.op_summ>=0,money.op_summ,NULL) AS summ1, IF(money.op_summ<0,money.op_summ,NULL) AS summ2,
	IF(goods.comment='',goods.name,goods.comment) AS goods_name,
	groups.name AS groups_name, walls.name AS walls_name, users.name AS users_name
	FROM money
	LEFT JOIN goods ON money.goods_id=goods.id
	LEFT JOIN groups ON goods.groups_id=groups.id
	LEFT JOIN walls ON money.walls_id=walls.id
	LEFT JOIN users ON money.users_id=users.id
	WHERE money.op_date>='$f_dtfr' AND money.op_date<='$f_dtto'$filter
	$order");
while ($ro2 = $res2->fetch_assoc()) {
	echo (($ro2['summ1'] == '') ? '<tr class="minus">' : '<tr class="plus">');
	echo '<td class="edit" onclick="get_form(\'edit_form\',' . $ro2['id'] . ',\'money\')">Редактировать';
	echo '<td>' . $ro2['op_date'];
	echo '<td>' . $ro2['summ1'];
	echo '<td>' . $ro2['summ2'];
	echo '<td>' . $ro2['goods_name'];
	echo '<td>' . $ro2['groups_name'];
	echo '<td>' . mb_substr($ro2['comment'], 0, 18);
	echo '<td>' . $ro2['walls_name'];
	echo '<td>' . $ro2['users_name'];
}

//сумма движения денег
$result = byQu($mysqli, "SELECT SUM(if(op_summ>0,op_summ,0)) AS summ1, SUM(if(op_summ<0,op_summ,0)) AS summ2, walls.name
	FROM money
	LEFT JOIN goods ON money.goods_id=goods.id
	LEFT JOIN groups ON goods.groups_id=groups.id
	LEFT JOIN walls ON money.walls_id=walls.id
	LEFT JOIN users ON money.users_id=users.id
	WHERE money.op_date>='$f_dtfr' and money.op_date<='$f_dtto'$filter
	GROUP BY walls_id");
while ($row = $result->fetch_assoc()) {
	echo '<tr><td>Сумма<td><td class="plus">' . $row['summ1'] . '<td class="minus">' . $row['summ2'] . '<td><td><td><td>' . $row['name'] . '<td>';
}

//итого движение денег
$result = byQu($mysqli, "SELECT SUM(op_summ) AS summ
	FROM money
	LEFT JOIN goods ON money.goods_id=goods.id
	LEFT JOIN groups ON goods.groups_id=groups.id
	WHERE money.op_date>='$f_dtfr' and money.op_date<='$f_dtto'$filter");
if ($row = $result->fetch_assoc()) {
	echo ((floatval($row['summ']) < 0) ? '<tr class="minus">' : '<tr class="plus">');
	echo '<td>Итого<td><td><td>' . $row['summ'] . '<td><td><td><td><td>';
}

//остаток на день
if ($f_dtto < date('Y-m-d')) {
$result = byQu($mysqli, "SELECT SUM(op_summ) AS summ, walls.name, MAX(op_date) AS dt
	FROM money
	LEFT JOIN goods ON money.goods_id=goods.id
	LEFT JOIN groups ON goods.groups_id=groups.id
	LEFT JOIN walls ON money.walls_id=walls.id
	LEFT JOIN users ON money.users_id=users.id
	WHERE money.op_date<='$f_dtto'$filter
	GROUP BY walls_id");
while ($row = $result->fetch_assoc()) {
	echo ((floatval($row['summ']) < 0) ? '<tr class="minus">' : '<tr class="plus">');
	echo '<td>Остаток<td>' . $row['dt'] . '<td><td>' . $row['summ'] . '<td><td><td><td>' . $row['name'] . '<td>';
}}

//остаток
$result = byQu($mysqli, "SELECT SUM(op_summ) AS summ, walls.name, MAX(op_date) AS dt
	FROM money
	LEFT JOIN goods ON money.goods_id=goods.id
	LEFT JOIN groups ON goods.groups_id=groups.id
	LEFT JOIN walls ON money.walls_id=walls.id
	LEFT JOIN users ON money.users_id=users.id
	WHERE true$filter
	GROUP BY walls_id");
while ($row = $result->fetch_assoc()) {
	echo ((floatval($row['summ']) < 0) ? '<tr class="minus">' : '<tr class="plus">');
	echo '<td>Остаток<td>' . $row['dt'] . '<td><td>' . $row['summ'] . '<td><td><td><td>' . $row['name'] . '<td>';
}
echo '</table>';

//фильтры
echo '<table class="form">';

//фильтр по дате
echo '<tr><td>Фильтр: с <td><input type="date" id="date_from" value="' . $f_dtfr . '">';
echo ' по <input type="date" id="date_to" value="' . $f_dtto . '">';

//фильтр по конторе
echo '<tr><td>контора:<td><select size="1" id="f_goods_id">';
echo '<option' . ((-1 == $f_goods_id) ? ' selected' : '') . ' value="-1">Все</option>';
$result = byQu($mysqli, "SELECT goods.id, CONCAT(groups.name,' - ',IF(goods.comment='',goods.name,goods.comment)) AS name
	FROM goods
	LEFT JOIN groups ON goods.groups_id=groups.id
	ORDER BY name");
while ($row = $result->fetch_assoc())
	echo '<option' . (($row['id'] == $f_goods_id) ? ' selected' : '') . ' value="' . $row['id'] . '">' . $row['name'] . '</option>';
echo '</select>';

//фильтр по группе
echo '<tr><td>группа:<td><select size="1" id="f_groups_id">';
echo '<option' . ((-1 == $f_groups_id) ? ' selected' : '') . ' value="-1">Все</option>';
$result = byQu($mysqli, "SELECT id, name FROM groups ORDER BY name");
while ($row = $result->fetch_assoc())
	echo '<option' . (($row['id'] == $f_groups_id) ? ' selected' : '') . ' value="' . $row['id'] . '">' . $row['name'] . '</option>';
echo '</select>';

//фильтр по кошельку
echo '<tr><td>кошелёк:<td><select size="1" id="f_walls_id">';
echo '<option' . ((-1 == $f_walls_id) ? ' selected' : '') . ' value="-1">Все</option>';
$result = byQu($mysqli, "SELECT id, name FROM walls");
while ($row = $result->fetch_assoc())
	echo '<option' . (($row['id'] == $f_walls_id) ? ' selected' : '') . ' value="' . $row['id'] . '">' . $row['name'] . '</option>';
echo '</select>';

//фильтр по пользователю
echo '<tr><td>пользователь:<td><select size="1" id="f_users_id">';
echo '<option' . ((-1 == $f_users_id) ? ' selected' : '') . ' value="-1">Все</option>';
$result = byQu($mysqli, "SELECT id, name FROM users");
while ($row = $result->fetch_assoc())
	echo '<option' . (($row['id'] == $f_users_id) ? ' selected' : '') . ' value="' . $row['id'] . '">' . $row['name'] . '</option>';
echo '</select>';

//сортировка
echo '<tr><td>Cортировка:<td><select size="1" id="ordr">';
echo '<option'; if (0 == $o) echo ' selected'; echo ' value="0">Без сортировки</option>';
echo '<option'; if (1 == $o) echo ' selected'; echo ' value="1">По дате</option>';
echo '<option'; if (2 == $o) echo ' selected'; echo ' value="2">По дате обратно</option>';
echo '<option'; if (3 == $o) echo ' selected'; echo ' value="3">По сумме</option>';
echo '<option'; if (4 == $o) echo ' selected'; echo ' value="4">По сумме обратно</option>';
echo '<option'; if (5 == $o) echo ' selected'; echo ' value="5">По конторам</option>';
echo '<option'; if (6 == $o) echo ' selected'; echo ' value="6">По конторам обратно</option>';
echo '<option'; if (7 == $o) echo ' selected'; echo ' value="7">По группам</option>';
echo '<option'; if (8 == $o) echo ' selected'; echo ' value="8">По группам обратно</option>';
echo '<option'; if (9 == $o) echo ' selected'; echo ' value="9">По описаниям</option>';
echo '<option'; if (10 == $o) echo ' selected'; echo ' value="10">По описаниям обратно</option>';
echo '<option'; if (11 == $o) echo ' selected'; echo ' value="11">По кошелькам</option>';
echo '<option'; if (12 == $o) echo ' selected'; echo ' value="12">По кошелькам обратно</option>';
echo '<option'; if (13 == $o) echo ' selected'; echo ' value="13">По пользователям</option>';
echo '<option'; if (14 == $o) echo ' selected'; echo ' value="14">По пользователям обратно</option>';
echo '<option'; if (15 == $o) echo ' selected'; echo ' value="15">По группам, конторам</option>';
echo '<option'; if (16 == $o) echo ' selected'; echo ' value="16">По группам, конторам обратно</option>';
echo '<option'; if (17 == $o) echo ' selected'; echo ' value="17">По кошелькам, дате</option>';
echo '<option'; if (18 == $o) echo ' selected'; echo ' value="18">По кошелькам, дате обратно</option>';
echo '</select>';

echo '<tr><td><td><input type="button" value="Обновить" onclick="money_table(1)"></table>';
if ($res2->num_rows > 25) echo $t;
echo '</article>';
?>
