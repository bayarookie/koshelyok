<main><section><article class="login_form"><form>
	<div class='error_msg'><?php echo $errm ?></div>
	<label>Имя пользователя :</label><br>
	<input type="text" id="username" placeholder="имя" value="<?php if ($reme == 'true') echo $user ?>"><br><br>
	<label>Пароль :</label><br>
	<input type="password" id="password" placeholder="пароль" value="<?php if ($reme == 'true') echo $pass ?>"><br><br>
	<input type="checkbox" id="remember"<?php if ($reme == 'true') echo ' checked' ?>> Запомнить меня<br><br>
	<input type="button" value="Войти" onclick="login()">
</form></article></section></main>
