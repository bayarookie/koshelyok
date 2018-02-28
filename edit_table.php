<?php
$tbl = $mysqli->real_escape_string($_POST['tbl'] ?? '');
if ($tbl == '') die('table?');
$title = $tbl_nam[$tbl];
$t = '<p>' . $title . '
<input type="button" value="Добавить" onclick="get_form(\'edit_form\',-1,\'' . $tbl . '\')">
<input type="button" value="Закрыть" onclick="id_close(\'edit_table\')"></p>';
$result = byQu("SELECT * FROM $tbl");
$finfo = $result->fetch_fields();
$th = '<tr><th>№<th>';
for ($i = 1; $i < count($finfo); $i++)
	$th .= '<th>' . $fld_nam[$finfo[$i]->name];

echo '<article>' . $t . '<table>' . $th;
$imax = $result->field_count;
$cnt = 0;
while ($row = $result->fetch_row()) {
	$cnt++;
	echo '<tr><td>' . $cnt . '<td class="edit" onclick="get_form(\'edit_form\',' . $row[0] . ',\'' . $tbl . '\')">Редактировать';
	for ($i = 1; $i < $imax; $i++) {
		if ($i == 4) {
			echo '<td class="num' . ((intval($row[$i]) > 0) ? ' edit" onclick="money_table(3,' . $row[0] . ')' : '') . '">' . $row[$i];
		} elseif (($i == 2) && ($tbl == 'users')) {
			echo '<td>*******';
		} else
			echo '<td>' . $row[$i];
	}
}
echo '</table>';
if ($result->num_rows > 30) echo $t;
echo '</article>';
?>
