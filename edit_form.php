<?php
$tbl = $mysqli->real_escape_string($_POST['tbl'] ?? '');
if ($tbl == '') die('table?');
$e_id = intval($_POST['id'] ?? -1);
if (substr($tbl, -2) == '_v') {
	$table = substr($tbl, 0, -2);
} else {
	$table = $tbl;
}
$title = $tbl_nam[$tbl];
$j = '';
echo '<figure><figcaption>' . $title . '</figcaption><div class="form">';
if ($e_id >= 0)
	$result = byQu("SELECT * FROM $table WHERE id=$e_id");
else
	$result = byQu("SELECT * FROM $table LIMIT 1");
$row = $result->fetch_row();
$finfo = $result->fetch_fields();
for ($i = 1; $i < count($finfo); $i++) {
	$f = $finfo[$i]->name;
	if (substr($f, -3) == '_id') {
		if ($e_id < 0) {
			$row[$i] = -1;
			if ($tbl == 'money') {
				if ($f == 'users_id') {$row[$i] = $user_id;}
				if ($f == 'walls_id') {$row[$i] = $wall_id;}
			}
		}
		$arr['e_' . $f] = $row[$i];
		$j .= byCb('e_' . $f);
	} else {
		if ($e_id < 0) {$row[$i] = '';}
		echo '<div><label>' . $fld_nam[$f] . '</label> ';
		if ($finfo[$i]->type == 10) {
			echo '<input type="date"';
			if ($e_id < 0) $row[$i] = date('Y-m-d');
		} elseif ($finfo[$i]->type == 246) {
			echo '<input type="number" step="0.01"';
		} elseif ($f == 'password') {
			echo '<input type="password"';
		} else {
			echo '<input type="text" autocomplete="off"';
		}
		echo ' value="' . $row[$i] . '" name="e_' . $f . '"></div>';
	}
}
echo '<div><label> </label>
<input type="button" value="Сохранить" onclick="edit_to_db(\'' . $tbl . '\')">
<input type="button" value="Отменить" onclick="id_close(\'edit_form\')"></div></div>
<input type="hidden" value="' . $e_id . '" name="e_id"></figure>
<script id="js">';
echo $j;
echo '</script>';
?>
