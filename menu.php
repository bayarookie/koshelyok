<nav>
	<li><a href="javascript:void(0)" onclick="money_table(-1)">Операции</a>
	<li>
		<a href="javascript:void(0)">Списки</a>
		<div>
			<a href="javascript:void(0)" onclick="get_form('edit_table',-1,'goods')">Конторы</a>
			<a href="javascript:void(0)" onclick="get_form('edit_table',-1,'groups')">Группы</a>
			<a href="javascript:void(0)" onclick="get_form('edit_table',-1,'walls')">Кошельки</a>
			<a href="javascript:void(0)" onclick="get_form('edit_table',-1,'users')">Пользователи</a>
		</div>
	<li>
		<a href="javascript:void(0)">Отчёты</a>
		<div>
			<a href="javascript:void(0)" onclick="get_report('report')">Помесячно (месяца отдельно)</a>
			<a href="javascript:void(0)" onclick="get_report('report1')">Помесячно (общая таблица 1)</a>
			<a href="javascript:void(0)" onclick="get_report('report2')">Помесячно (общая таблица 2)</a>
			<a href="javascript:void(0)" onclick="get_report('report3')">В среднем за месяц</a>
			<a href="javascript:void(0)" onclick="get_report('report4')">По группам</a>
			<a href="javascript:void(0)" onclick="get_report('report5')">По конторам</a>
			<a href="javascript:void(0)" onclick="get_report('report6')">По пользователям</a>
		</div>
	<li>
		<a href="javascript:void(0)">Служебные</a>
		<div>
			<a href="javascript:void(0)" onclick="get_form('import_form')">Импорт</a>
			<a href="backup.php">Резервное копирование</a>
		</div>
	<li>
		<a href="javascript:void(0)">Оформление</a>
		<div>
			<a href="javascript:void(0)" onclick="ch_css(1)">Тёмный стиль</a>
			<a href="javascript:void(0)" onclick="ch_css(2)">Светлый стиль</a>
			<a href="javascript:void(0)" onclick="ch_css(3)">Третий стиль</a>
			<a href="javascript:void(0)" onclick="ch_css(4)">Ещё один стиль</a>
		</div>
	<li>
		<a href="javascript:void(0)" onclick="logout()">Выйти</a>
</nav>
<main>
	<section id="money_table"><?php include 'money_table.php' ?></section>
</main>
