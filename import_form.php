<article><h1>Импорт выписки из банка</h1><div class="form">
<?php
$arr['i_walls_id'] = $wall_id;
$j = byCb('i_walls_id');
?>
<div><label>Выбрать файл:</label> <input type="file" id="bankstate" accept=".txt, .csv"></div>
<div><label> </label>
<input type="button" value="Отправить файл" onclick="import_form2()">
<input type="button" value="Закрыть" onclick="id_close('import_form')"></div>
</div>
<div id="import_form2"></div>
</article>
<script id="combojs">
<?php echo $j;?>
</script>
