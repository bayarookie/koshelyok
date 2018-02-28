<?php
$t = '<p>Операции
<input type="button" value="Добавить" onclick="get_form(\'edit_form\',-1,\'money\')">
<input type="button" value="Закрыть" onclick="id_close(\'money_table\')"></p>';
echo '<article>' . $t;
//фильтры
$f = intval($_POST['f'] ?? 1);
if (isset($_POST['mo'])) {
	$f_dtto = date('Y-m-t', strtotime($_POST['mo'] . '-01'));
	$f_dtfr = date('Y-m-d', strtotime($_POST['mo'] . '-01'));
} elseif ($f == 3) {
	$f_dtto = date('Y-m-d');
	$f_dtfr = byDt('MIN');
} elseif ($f == 5) { //from report
	$f_dtto = date('Y-m-d', strtotime($_POST['p_date_to'] ?? 'now'));
	$f_dtfr = date('Y-m-d', strtotime($_POST['p_date_from'] ?? '-1 month'));
} else {
	$f_dtto = date('Y-m-d', strtotime($_POST['to'] ?? 'now'));
	$f_dtfr = date('Y-m-d', strtotime($_POST['from'] ?? byDt('MAX') . ' first day of this month -1 month'));
}
$arr['f_servs_id'] = intval($_POST['f_servs_id'] ?? -1);
$arr['f_grups_id'] = intval($_POST['f_grups_id'] ?? -1);
$arr['f_bgrup_id'] = intval($_POST['f_bgrup_id'] ?? -1);
$arr['f_walls_id'] = intval($_POST['f_walls_id'] ?? -1);
$arr['f_users_id'] = intval($_POST['f_users_id'] ?? -1);
$filter = '';
if ($arr['f_servs_id'] > -1) $filter .= ' AND money.servs_id=' . $arr['f_servs_id'];
if ($arr['f_grups_id'] > -1) $filter .= ' AND money.grups_id=' . $arr['f_grups_id'];
if ($arr['f_bgrup_id'] > -1) $filter .= ' AND grups.bgrup_id=' . $arr['f_bgrup_id'];
if ($arr['f_walls_id'] > -1) $filter .= ' AND money.walls_id=' . $arr['f_walls_id'];
if ($arr['f_users_id'] > -1) $filter .= ' AND money.users_id=' . $arr['f_users_id'];

//сортировка
echo '<table class="money_table"><thead><tr><th>№<th class="edit" onclick="money_table(0,0)">';
$arr['o_money_order_id'] = intval($_POST['o_money_order_id'] ?? 1);
$result = byQu("SELECT order_by FROM money_order WHERE id=" . $arr['o_money_order_id']);
$order = ($row = $result->fetch_row()) ? "ORDER BY " . $row[0] : '';

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
	echo '<tr><td><td>На начало<td>' . $f_dtfr . '<td>' . $row['summ'] . '<td><td><td><td><td>' . $row['name'] . '<td>';
echo '</thead><tbody>';

//движение денег
$result = byQu("SELECT money.id, op_date,
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
$rep = ($result->num_rows > 25);
$cnt = 0;
while ($ro2 = $result->fetch_assoc()) {
	echo (($ro2['summ1'] == '') ? '<tr class="minus">' : '<tr class="plus">');
	$cnt++;
	echo '<td>' . $cnt;
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
	echo '<tr><td><td>Сумма<td><td>' . $row['summ1'] . '<td>' . $row['summ2'] . '<td><td><td><td>' . $row['name'] . '<td>';

//итого движение денег
$result = byQu("SELECT SUM(op_summ) AS summ
	FROM money
	LEFT JOIN grups ON money.grups_id=grups.id
	WHERE op_date>='$f_dtfr' and op_date<='$f_dtto'$filter");
if ($row = $result->fetch_assoc())
	echo '<tr><td><td>Итого<td><td><td class="' . ((floatval($row['summ']) < 0) ? 'minus' : 'plus') . '">' . $row['summ'] . '<td><td><td><td><td>';

//остаток на день
if ($f_dtto < date('Y-m-d')) {
$result = byQu("SELECT SUM(op_summ) AS summ, walls.name, MAX(op_date) AS dt
	FROM money
	LEFT JOIN grups ON money.grups_id=grups.id
	LEFT JOIN walls ON money.walls_id=walls.id
	WHERE op_date<='$f_dtto'$filter
	GROUP BY walls_id");
while ($row = $result->fetch_assoc())
	echo '<tr><td><td>Остаток<td>' . $row['dt'] . '<td><td class="' . ((floatval($row['summ']) < 0) ? 'minus' : 'plus') . '">' . $row['summ'] . '<td><td><td><td>' . $row['name'] . '<td>';
}

//остаток
$result = byQu("SELECT SUM(op_summ) AS summ, walls.name, MAX(op_date) AS dt
	FROM money
	LEFT JOIN grups ON money.grups_id=grups.id
	LEFT JOIN walls ON money.walls_id=walls.id
	WHERE true$filter
	GROUP BY walls_id");
while ($row = $result->fetch_assoc())
	echo '<tr><td><td>Остаток<td>' . $row['dt'] . '<td><td>' . $row['summ'] . '<td><td><td><td>' . $row['name'] . '<td>';
echo '</tfoot>';
echo '</table></article>';

//фильтры
echo '<article>';
if ($rep) echo $t;
echo '<div class="form">';
echo '<div><label>Фильтр: с </label>
<input type="date" value="' . $f_dtfr . '" name="from"> по <input type="date" value="' . $f_dtto . '" name="to"></div>';
$j = '';
$j .= byCb('f_servs_id');
$j .= byCb('f_grups_id');
$j .= byCb('f_bgrup_id');
$j .= byCb('f_walls_id');
$j .= byCb('f_users_id');
//bySe('Cортировка:', 'o', 'money_order', $o, 'Без сортировки');
$j .= byCb('o_money_order_id');

echo '<div><label> </label>
<input type="button" value="Обновить" onclick="money_table(1)"></div></div></article>
<script id="js">';
echo $j;
echo '</script>';
?>
