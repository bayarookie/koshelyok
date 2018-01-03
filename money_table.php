<?php
$t = '<p>Операции
<input type="button" value="Добавить" onclick="get_form(\'edit_form\',-1,\'money\')">
<input type="button" value="Закрыть" onclick="id_close(\'money_table\')"></p>';
echo '<article>' . $t;

//фильтры
$f_servs_id = isset($_POST['f_servs_id']) ? intval($_POST['f_servs_id']) : -1;
$f_grups_id = isset($_POST['f_grups_id']) ? intval($_POST['f_grups_id']) : -1;
$f_bgrup_id = isset($_POST['f_bgrup_id']) ? intval($_POST['f_bgrup_id']) : -1;
$f_walls_id = isset($_POST['f_walls_id']) ? intval($_POST['f_walls_id']) : -1;
$f_users_id = isset($_POST['f_users_id']) ? intval($_POST['f_users_id']) : -1;
$f = isset($_POST['f']) ? intval($_POST['f']) : 1;
if (isset($_POST['mo'])) {
	$f_dtto = date('Y-m-t', strtotime($_POST['mo'] . '-01'));
	$f_dtfr = date('Y-m-d', strtotime($_POST['mo'] . '-01'));
} elseif ($f == 3) {
	$f_dtto = date('Y-m-d');
	$result = byQu("SELECT MIN(op_date) FROM money");
	$f_dtfr = ($row = $result->fetch_row()) ? $row[0] : '2015-01-01';
} elseif ($f == 5) {
	$f_dtto = isset($_POST['p_date_to']) ? date('Y-m-d', strtotime($_POST['p_date_to'])) : date('Y-m-d');
	$f_dtfr = isset($_POST['p_date_from']) ? date('Y-m-d', strtotime($_POST['p_date_from'])) : date('Y-m-d');
} else {
	$f_dtto = isset($_POST['to']) ? date('Y-m-d', strtotime($_POST['to'])) : date('Y-m-d');
	$result = byQu("SELECT MAX(op_date) FROM money");
	$f_dtfr = ($row = $result->fetch_row()) ? $row[0] : date('Y-m-d');
	$f_dtfr = isset($_POST['from']) ? date('Y-m-d', strtotime($_POST['from'])) : (new DateTime($f_dtfr))->modify('first day of this month -1 month')->format('Y-m-d');
}
$filter = "";
if ($f_servs_id > -1) $filter .= " AND money.servs_id=" . $f_servs_id;
if ($f_grups_id > -1) $filter .= " AND money.grups_id=" . $f_grups_id;
if ($f_bgrup_id > -1) $filter .= " AND grups.bgrup_id=" . $f_bgrup_id;
if ($f_walls_id > -1) $filter .= " AND money.walls_id=" . $f_walls_id;
if ($f_users_id > -1) $filter .= " AND money.users_id=" . $f_users_id;

//сортировка
echo '<table class="money_table"><thead><tr><th class="edit" onclick="money_table(0,0)">';
$order = "";
$o = isset($_POST['o']) ? intval($_POST['o']) : 1;
$result = byQu("SELECT order_by FROM money_order WHERE id=" . $o);
if ($row = $result->fetch_row()) $order = "ORDER BY " . $row[0];

function byOr($s) {
	$result = byQu("SELECT id FROM money_order WHERE order_by LIKE '$s'");
	if ($row = $result->fetch_row()) return $row[0];
}

function byTh($s, $t) {
	global $order;
	echo '<th class="edit"';
	if ($s == 'op_summ') echo ' colspan="2"';
	$e = byOr('%' . $s) . ')">';
	if (strpos($order, $s) !== false) {
		if (strpos($order, 'DESC') === false) {
			$s = byOr('%' . $s . ' DESC%');
			$e = ($s != '') ? $s . ')">↑' : $e; 
		} else $e .= '↓';
	}
	echo ' onclick="money_table(0,'. $e . $t;
}

byTh('op_date', 'Дата');
byTh('op_summ', 'Сумма');
byTh('servs_name', 'Контора');
byTh('grups.name', 'Подгруппа');
byTh('money.comment', 'Описание');
byTh('walls.name', 'Кошелёк');
byTh('users.name', 'Юзер');

//таблица, остаток на начало
$result = byQu("SELECT SUM(op_summ) AS summ, walls.name FROM money
	LEFT JOIN grups ON money.grups_id=grups.id
	LEFT JOIN walls ON money.walls_id=walls.id
	WHERE op_date<'$f_dtfr'$filter
	GROUP BY walls_id");
while ($row = $result->fetch_assoc())
	echo '<tr><td>На начало<td>' . $f_dtfr . '<td>' . $row['summ'] . '<td><td><td><td><td>' . $row['name'] . '<td>';
echo '</thead><tbody>';

//движение денег
$res2 = byQu("SELECT money.id, op_date,
	IF(op_summ>=0,op_summ,NULL) AS summ1, IF(op_summ<0,op_summ,NULL) AS summ2,
	IF(servs.comment='',servs.name,servs.comment) AS servs_name,
	grups.name AS grups_name, money.comment, walls.name AS walls_name, users.name AS users_name
	FROM money
	LEFT JOIN servs ON money.servs_id=servs.id
	LEFT JOIN grups ON money.grups_id=grups.id
	LEFT JOIN bgrup ON grups.bgrup_id=bgrup.id
	LEFT JOIN walls ON money.walls_id=walls.id
	LEFT JOIN users ON money.users_id=users.id
	WHERE op_date>='$f_dtfr' AND op_date<='$f_dtto'$filter
	$order");
while ($ro2 = $res2->fetch_assoc()) {
	echo (($ro2['summ1'] == '') ? '<tr class="minus">' : '<tr class="plus">');
	echo '<td class="edit" onclick="get_form(\'edit_form\',' . $ro2['id'] . ',\'money\')">Редактировать';
	echo '<td>' . $ro2['op_date'];
	echo '<td>' . $ro2['summ1'];
	echo '<td>' . $ro2['summ2'];
	echo '<td>' . mb_substr($ro2['servs_name'], 0, 18);
	echo '<td>' . $ro2['grups_name'];
	echo '<td>' . mb_substr($ro2['comment'], 0, 18);
	echo '<td>' . $ro2['walls_name'];
	echo '<td>' . $ro2['users_name'];
}

echo '</tbody><tfoot>';
//сумма движения денег
$result = byQu("SELECT SUM(if(op_summ>0,op_summ,0)) AS summ1, SUM(if(op_summ<0,op_summ,0)) AS summ2, walls.name
	FROM money
	LEFT JOIN grups ON money.grups_id=grups.id
	LEFT JOIN walls ON money.walls_id=walls.id
	WHERE op_date>='$f_dtfr' and op_date<='$f_dtto'$filter
	GROUP BY walls_id");
while ($row = $result->fetch_assoc())
	echo '<tr><td>Сумма<td><td class="plus">' . $row['summ1'] . '<td class="minus">' . $row['summ2'] . '<td><td><td><td>' . $row['name'] . '<td>';

//итого движение денег
$result = byQu("SELECT SUM(op_summ) AS summ
	FROM money
	LEFT JOIN grups ON money.grups_id=grups.id
	WHERE op_date>='$f_dtfr' and op_date<='$f_dtto'$filter");
if ($row = $result->fetch_assoc()) {
	echo ((floatval($row['summ']) < 0) ? '<tr class="minus">' : '<tr class="plus">');
	echo '<td>Итого<td><td><td>' . $row['summ'] . '<td><td><td><td><td>';
}

//остаток на день
if ($f_dtto < date('Y-m-d')) {
$result = byQu("SELECT SUM(op_summ) AS summ, walls.name, MAX(op_date) AS dt
	FROM money
	LEFT JOIN grups ON money.grups_id=grups.id
	LEFT JOIN walls ON money.walls_id=walls.id
	WHERE op_date<='$f_dtto'$filter
	GROUP BY walls_id");
while ($row = $result->fetch_assoc()) {
	echo ((floatval($row['summ']) < 0) ? '<tr class="minus">' : '<tr class="plus">');
	echo '<td>Остаток<td>' . $row['dt'] . '<td><td>' . $row['summ'] . '<td><td><td><td>' . $row['name'] . '<td>';
}}

//остаток
$result = byQu("SELECT SUM(op_summ) AS summ, walls.name, MAX(op_date) AS dt
	FROM money
	LEFT JOIN grups ON money.grups_id=grups.id
	LEFT JOIN walls ON money.walls_id=walls.id
	WHERE true$filter
	GROUP BY walls_id");
while ($row = $result->fetch_assoc()) {
	echo ((floatval($row['summ']) < 0) ? '<tr class="minus">' : '<tr class="plus">');
	echo '<td>Остаток<td>' . $row['dt'] . '<td><td>' . $row['summ'] . '<td><td><td><td>' . $row['name'] . '<td>';
}
echo '</tfoot>';
echo '</table></article>';

//фильтры
echo '<article><table class="form">';
echo '<tr><td>Фильтр: с <td><input type="date" id="from" value="' . $f_dtfr . '">';
echo ' по <input type="date" id="to" value="' . $f_dtto . '">';
bySe('контора:', 'f_servs_id', 'servs', $f_servs_id, 'Все');
bySe('подгруппа:', 'f_grups_id', 'grups', $f_grups_id, 'Все');
bySe('группа:', 'f_bgrup_id', 'bgrup', $f_bgrup_id, 'Все');
bySe('кошелёк:', 'f_walls_id', 'walls', $f_walls_id, 'Все');
bySe('пользователь:', 'f_users_id', 'users', $f_users_id, 'Все');
bySe('Cортировка:', 'o', 'money_order', $o, 'Без сортировки');

echo '<tr><td><td><input type="button" value="Обновить" onclick="money_table(1)"></table>';
if ($res2->num_rows > 25) echo $t;
echo '</article>';
?>
