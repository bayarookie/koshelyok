<nav>
	<li><a onclick="money_table(-1)">Операции</a>
	<li><a>Списки &#9662;</a>
		<div>
			<a onclick="get_form('edit_table',-1,'servs_v')">Конторы</a>
			<a onclick="get_form('edit_table',-1,'grups_v')">Подгруппы</a>
			<a onclick="get_form('edit_table',-1,'bgrup')">Группы</a>
			<a onclick="get_form('edit_table',-1,'walls')">Кошельки</a>
<?php if ($user_id = 1) {?>
			<a onclick="get_form('edit_table',-1,'users')">Пользователи</a>
			<a onclick="get_form('edit_table',-1,'money_order')">Сортировка</a>
<?php }?>
		</div>
	<li><a>Отчёты &#9662;</a>
		<div>
			<a onclick="get_report('report1')">Помесячно (таблица 1)</a>
			<a onclick="get_report('report2')">Помесячно (таблица 2)</a>
			<a onclick="get_report('report3')">В среднем за месяц</a>
			<a onclick="get_report('report4')">По подгруппам</a>
			<a onclick="get_report('report5')">По конторам</a>
			<a onclick="get_report('report6')">По пользователям</a>
			<a onclick="get_report('report7')">По группам</a>
			<a onclick="get_report('report8')">Помесячно по подгруппе</a>
		</div>
<?php if ($user_id = 1) {?>
	<li><a>Служебные &#9662;</a>
		<div>
			<a onclick="get_form('import_form')">Импорт</a>
			<a href="backup.php">Резервное копирование</a>
		</div>
<?php }?>
	<li><a>Оформление &#9662;</a>
		<div>
			<a onclick="ch_css(1)">Тёмный стиль</a>
			<a onclick="ch_css(2)">Светлый стиль</a>
			<a onclick="ch_css(3)">Третий стиль</a>
			<a onclick="ch_css(4)">Ещё один стиль</a>
		</div>
	<li><a onclick="logout()">Выйти</a>
</nav>
<main>
	<section id="money_table"><?php include 'money_table.php' ?></section>
</main>
