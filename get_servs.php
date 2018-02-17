<?php
$f_grups_id = isset($_GET['f_grups_id']) ? intval($_GET['f_grups_id']) : -1;
$filter = ($f_grups_id > -1) ? " WHERE grups_id=" . $f_grups_id : '';
echo '{options: [["-1", ""],';
$result = byQu("SELECT id, name, comment FROM servs$filter ORDER BY name");
while ($row = $result->fetch_assoc()) {
	echo '["' . $row['id'] . '", "' . $row['name'] . (($row['comment']) ? ' - ' . $row['comment'] : '') . '"],';
}
echo ']}';
?>
