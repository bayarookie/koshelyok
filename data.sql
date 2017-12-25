-- Koshelyok SQL Dump
-- version 0.0.2
-- http://bayarookie.wallst.ru
--
-- Хост: localhost
-- Время создания: December 26 2017, 01:30
-- Версия сервера: 5.7.20-0ubuntu0.16.04.1
-- Версия PHP: 7.0.22-0ubuntu0.16.04.1

--
-- База данных: `koshelyok`
--

-- --------------------------------------------------------

--
-- Структура таблицы `goods`
--

CREATE TABLE `goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `comment` text NOT NULL,
  `groups_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `goods`
--

INSERT INTO `goods` (`id`, `name`, `comment`, `groups_id`) VALUES
(0, 'Дом', '', 3);

-- --------------------------------------------------------

--
-- Структура для представления `goods_v`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `goods_v` AS select `goods`.`id` AS `id`,`goods`.`name` AS `name`,`goods`.`comment` AS `comment`,`groups`.`name` AS `groups_name`,count(`money`.`id`) AS `cnt` from ((`goods` left join `groups` on((`goods`.`groups_id` = `groups`.`id`))) left join `money` on((`goods`.`id` = `money`.`goods_id`))) group by `goods`.`id` order by `groups`.`name`,`goods`.`name`;

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `groups`
--

INSERT INTO `groups` (`id`, `name`, `comment`) VALUES
(0, 'Коммуналка', 'все коммунальные платежи'),
(1, 'Еда', 'жратва'),
(2, 'Доходы', ''),
(3, 'Дом', 'движение внутри дома'),
(4, 'Ширпотреб', 'всё остальное'),
(5, 'Лекарства', ''),
(6, 'Внутри', 'Перемещения со счёта на счёт'),
(7, 'Техника', ''),
(8, 'Одежда', '');

-- --------------------------------------------------------

--
-- Структура таблицы `money`
--

CREATE TABLE `money` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `op_date` date NOT NULL,
  `op_summ` decimal(12,2) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `walls_id` int(4) NOT NULL,
  `users_id` int(4) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `walls_id` (`walls_id`),
  KEY `op_date` (`op_date`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `money`
--

INSERT INTO `money` (`id`, `op_date`, `op_summ`, `goods_id`, `walls_id`, `users_id`, `comment`) VALUES
(0, '2015-11-25', '100.00', 0, 0, 1, 'Стартовый капитал');

-- --------------------------------------------------------

--
-- Структура таблицы `money_order`
--

CREATE TABLE `money_order` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `order_by` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `money_order`
--

INSERT INTO `money_order` (`id`, `name`, `order_by`) VALUES
(1, 'По дате', 'op_date'),
(2, 'По дате обратно', 'op_date DESC'),
(3, 'По сумме', 'op_summ'),
(4, 'По сумме обратно', 'op_summ DESC'),
(5, 'По конторам', 'goods_name'),
(6, 'По конторам обратно', 'goods_name DESC'),
(7, 'По группам', 'groups.name'),
(8, 'По группам обратно', 'groups.name DESC'),
(9, 'По комментариям', 'money.comment'),
(10, 'По комментариям обратно', 'money.comment DESC'),
(11, 'По кошелькам', 'walls.name'),
(12, 'По кошелькам обратно', 'walls.name DESC'),
(13, 'По пользователям', 'users.name'),
(14, 'По пользователям обратно', 'users.name DESC'),
(15, 'По группам, конторам', 'groups.name, goods_name'),
(16, 'По кошелькам, дате', 'walls.name, op_date');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL DEFAULT '',
  `password` varchar(64) NOT NULL DEFAULT '',
  `name` text NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`) VALUES
(1, 'root', '1', 'Баяр'),
(2, 'ya', '1234', 'РДЖ');

-- --------------------------------------------------------

--
-- Структура таблицы `walls`
--

CREATE TABLE `walls` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `comment` text NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `walls`
--

INSERT INTO `walls` (`id`, `name`, `comment`) VALUES
(0, 'наличные', 'в кошельке, в шкафу, в карманах'),
(1, 'карта 3008', 'карта сбербанка');
