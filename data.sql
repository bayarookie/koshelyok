-- Koshelyok SQL Dump
-- version 0.0.2
-- http://bayarookie.wallst.ru
--
-- Хост: localhost
-- Время создания: January 1 2018, 00:51
-- Версия сервера: 5.7.20-0ubuntu0.16.04.1
-- Версия PHP: 7.0.22-0ubuntu0.16.04.1

--
-- База данных: `koshelyok`
--

-- --------------------------------------------------------

--
-- Структура таблицы `grups`
--

CREATE TABLE `grups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `grups`
--

INSERT INTO `grups` (`id`, `name`, `comment`) VALUES
(0, 'Коммуналка', 'все коммунальные платежи'),
(1, 'Еда', 'жратва'),
(2, 'Доходы', ''),
(3, 'Дом', 'движение внутри дома'),
(4, 'Ширпотреб', 'всё остальное');

-- --------------------------------------------------------

--
-- Структура таблицы `money`
--

CREATE TABLE `money` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `op_date` date NOT NULL,
  `op_summ` decimal(12,2) NOT NULL,
  `servs_id` int(11) NOT NULL,
  `grups_id` int(4) NOT NULL,
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

INSERT INTO `money` (`id`, `op_date`, `op_summ`, `servs_id`, `grups_id`, `walls_id`, `users_id`, `comment`) VALUES
(0, '2015-11-25', '100.00', 0, 3, 0, 1, 'Стартовый капитал');

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
(5, 'По конторам', 'servs_name'),
(6, 'По конторам обратно', 'servs_name DESC'),
(7, 'По группам', 'groups.name'),
(8, 'По группам обратно', 'groups.name DESC'),
(9, 'По комментариям', 'money.comment'),
(10, 'По комментариям обратно', 'money.comment DESC'),
(11, 'По кошелькам', 'walls.name'),
(12, 'По кошелькам обратно', 'walls.name DESC'),
(13, 'По пользователям', 'users.name'),
(14, 'По пользователям обратно', 'users.name DESC'),
(15, 'По группам, конторам', 'groups.name, servs_name'),
(16, 'По кошелькам, дате', 'walls.name, op_date');

-- --------------------------------------------------------

--
-- Структура таблицы `servs`
--

CREATE TABLE `servs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `comment` text NOT NULL,
  `grups_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `servs`
--

INSERT INTO `servs` (`id`, `name`, `comment`, `grups_id`) VALUES
(0, 'Дом', '', 3);

-- --------------------------------------------------------

--
-- Структура для представления `servs_v`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `servs_v` AS select `servs`.`id` AS `id`,`servs`.`name` AS `name`,`servs`.`comment` AS `comment`,`grups`.`name` AS `grups_name`,count(`money`.`id`) AS `cnt` from ((`servs` left join `grups` on((`servs`.`grups_id` = `grups`.`id`))) left join `money` on((`servs`.`id` = `money`.`servs_id`))) group by `servs`.`id` order by `grups`.`name`,`servs`.`name`;

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
