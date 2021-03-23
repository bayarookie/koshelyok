<?php
$tbl = $mysqli->real_escape_string($_POST['tbl'] ?? '');
$e_id = intval($_POST['e_id'] ?? -1);
if (empty($tbl)) die('table?');
if (substr($tbl, -2) == '_v') {
	$table = substr($tbl, 0, -2);
} else {
	$table = $tbl;
}
if ($e_id > -1) {
	$q = "UPDATE $table SET ";
} else {
	$q = "INSERT INTO $table (";
}
$p = '';
$v = '';
$result = byQu("SELECT * FROM $table LIMIT 1");
$row = $result->fetch_row();
$finfo = $result->fetch_fields();
if ($debug) {
echo '<pre>';
foreach ($finfo as $val) {
	printf("Name:      %s\n",   $val->name);
	printf("Table:     %s\n",   $val->table);
	printf("Max. Len:  %d\n",   $val->max_length);
	printf("Length:    %d\n",   $val->length);
	printf("charsetnr: %d\n",   $val->charsetnr);
	printf("Flags:     %d\n",   $val->flags);
	printf("Type:      %d\n\n", $val->type);
}
echo '</pre>';
}
for ($i = 1; $i < count($finfo); $i++) {
	$f = $finfo[$i]->name;
	if ($finfo[$i]->type == 10) {
		$s = "STR_TO_DATE('" . $mysqli->real_escape_string($_POST['e_' . $f] ?? date('Y-m-d')) . "', '%Y-%m-%d')";
	} elseif (in_array($finfo[$i]->type, [1,2,3,8,9])) { //int
		$s = intval($_POST['e_' . $f] ?? -1);
		if ($s == 0) {
			$a = $mysqli->real_escape_string($_POST['e_' . $f] ?? '');
			if ($a !== '') {
				if ($f == 'servs_id') {
					byQu("INSERT INTO servs (name, grups_id, comment) VALUES ('$a', -1, '')");
					$s = $mysqli->insert_id;
				}
			}
		}
	} elseif ($finfo[$i]->type == 246) {
		$s = floatval($_POST['e_' . $f] ?? 0);
	} else {
		$s = "'" . $mysqli->real_escape_string($_POST['e_' . $f] ?? '') . "'";
	}
	if ($e_id > -1) {
		if ($p != '') $p .= ", ";
		$p .= "$f=$s";
	} else {
		if ($p != '') $p .= ", ";
		if ($v != '') $v .= ", ";
		$p .= $f;
		$v .= $s;
	}
}
if ($e_id > -1) {
	$q .= $p . " WHERE id=$e_id";
} else {
	$q .= $p . ") VALUES ($v)";
}
byQu($q);
if ($tbl == 'money') include 'money_table.php';
else include 'edit_table.php';
?>
