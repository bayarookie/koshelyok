<article><h1>Импорт выписки из банка</h1><table class="form">
<?php bySe('кошелёк:', 'w_id', 'walls', 1, '');?>
<tr><td>Выбрать файл:<td><input type="file" id="bankstate" accept=".txt, .csv">
<tr><td><td><input type="button" value="Отправить файл" onclick="import_form2()">
<input type="button" value="Закрыть" onclick="id_close('import_form')">
</table>
<div id="import_form2"></div>
</article>
