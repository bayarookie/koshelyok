<?php
if (file_exists('config.php')) {
	include 'config.php';
} else {
	define("DB_ADRES", "localhost");
	define("DB_DATAB", "koshelyok");
	define("DB_LOGIN", "koshelyok");
	define("DB_PASSW", "1113");
	define("DB_CHARS", "utf-8");
}
echo '<section id="install">
<script id="js">
document.getElementById(\'next\').onclick = function() {
	var data = "frm=instal2";
	data += get_inputs(document.body, "input, select");
	ajaxsend(data, document.body);
}
</script>
<style>
#install figure {
	max-width: 600px;
	margin: auto;
}
</style>
<figure><figcaption>Установка</figcaption>
<div class="form">
<div><label>MySQL админ</label> <input type="text" value="debian-sys-maint" name="adm_name"></div>
<div><label>MySQL пароль</label> <input type="password" value="" name="adm_pass"></div><br>
<div><label>адрес сервера</label> <input type="text" value="'.DB_ADRES.'" name="DB_ADRES"></div>
<div><label>база данных</label> <input type="text" value="'.DB_DATAB.'" name="DB_DATAB"></div>
<div><label>юзер для бд</label> <input type="text" value="'.DB_LOGIN.'" name="DB_LOGIN"></div>
<div><label>пароль</label> <input type="text" value="'.DB_PASSW.'" name="DB_PASSW"></div><br>
<div><label>логин</label> <input type="text" value="ya" name="usr_name"></div>
<div><label>пароль</label> <input type="text" value="1234" name="usr_pass"></div>
<div><label>имя юзера</label> <input type="text" value="юзер" name="usr_disp"></div>
<div><label> </label>
<input type="button" value="Продолжить" id="next">
<input type="button" value="Отменить" onclick="id_close(\'install\')">
</div></div></figure></section>';
