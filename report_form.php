<?php
if (isset($_POST['id'])) $rpt = intval($_POST['id']); else $rpt = 1;
echo '<figure><figcaption>Отчёт ';
if ($rpt == 2) echo 'средний'; else echo 'помесячно';
echo '</figcaption>';
$f_dtto = date('Y-m-d');
$f_dtfr = date('Y-m-d', strtotime($f_dtto . ' -6 month'));
echo '<p>с: <input type="date" id="p_date_from" placeholder="Дата" value="' . $f_dtfr . '" autofocus>';
echo 'по: <input type="date" id="p_date_to" placeholder="Дата" value="' . $f_dtto . '"></p>';
echo '<p><input type="button" value="Отчёт" onclick="get_report(' . $rpt . ')">';
?>
<input type="reset" value="Очистить">
<input type="button" value="Закрыть" onclick="id_close('report_form')"></p></figure>
