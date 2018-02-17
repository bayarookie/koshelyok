<?php
$f_bgrup_id = isset($_GET['f_bgrup_id']) ? intval($_GET['f_bgrup_id']) : -1;
$filter = ($f_bgrup_id > -1) ? " WHERE bgrup_id=" . $f_bgrup_id : '';
echo '{options: [["-1", ""],';
$result = byQu("SELECT id, name, comment FROM grups$filter ORDER BY name");
while ($row = $result->fetch_assoc()) {
	echo '["' . $row['id'] . '", "' . $row['name'] . (($row['comment']) ? ' - ' . $row['comment'] : '') . '"],';
}
echo ']}';
?>
