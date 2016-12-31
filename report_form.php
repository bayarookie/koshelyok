<?php
$rpt = isset($_POST['id']) ? intval($_POST['id']) : 1;
echo '<figure><figcaption>Отчёт ';
echo ($rpt == 2) ? 'средний' : 'помесячно';
echo '</figcaption>';
$f_dtto = date('Y-m-d');
$f_dtfr = date('Y-m-d', strtotime($f_dtto . ' -6 month'));
echo '<p>с: <input type="date" id="p_date_from" placeholder="Дата" value="' . $f_dtfr . '" autofocus>';
echo 'по: <input type="date" id="p_date_to" placeholder="Дата" value="' . $f_dtto . '"></p>';
echo '<p><input type="button" value="Отчёт" onclick="get_report(' . $rpt . ')">';
?>
<input type="reset" value="Очистить">
<input type="button" value="Закрыть" onclick="id_close('report_form')"></p></figure>
