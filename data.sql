-- Koshelyok SQL Dump
-- version 0.0.2
-- https://gitlab.com/bayarookie/koshelyok
--
-- Хост: localhost
-- Время создания: October 1 2018, 16:16
-- Версия сервера: 5.5.5-10.1.36-MariaDB
-- Версия PHP: 7.2.10

--
-- База данных: `koshelyok`
--

-- --------------------------------------------------------

--
-- Структура таблицы `bgrup`
--

CREATE TABLE `bgrup` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `comment` tinyblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `grups`
--

CREATE TABLE `grups` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `comment` tinyblob,
  `bgrup_id` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура для представления `grups_v`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `grups_v` AS select `grups`.`id` AS `id`,`grups`.`name` AS `name`,`grups`.`comment` AS `comment`,`bgrup`.`name` AS `bgrup_name` from (`grups` left join `bgrup` on((`grups`.`bgrup_id` = `bgrup`.`id`))) order by `bgrup`.`name`,`grups`.`name`;

-- --------------------------------------------------------

--
-- Структура таблицы `money`
--

CREATE TABLE `money` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `op_date` date NOT NULL,
  `op_summ` decimal(12,2) NOT NULL,
  `servs_id` mediumint(9) NOT NULL,
  `walls_id` tinyint(4) NOT NULL,
  `comment` tinyblob,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `walls_id` (`walls_id`),
  KEY `op_date` (`op_date`)
) ENGINE=InnoDB AUTO_INCREMENT=1217 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `money_order`
--

CREATE TABLE `money_order` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `order_by` varchar(64) NOT NULL,
  `comment` tinyblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `servs`
--

CREATE TABLE `servs` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `comment` tinyblob,
  `grups_id` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8;

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
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL DEFAULT '',
  `password` varchar(64) NOT NULL DEFAULT '',
  `name` varchar(64) NOT NULL,
  `comment` tinyblob,
  `walls_id` tinyint(4) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `walls`
--

CREATE TABLE `walls` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `comment` tinyblob,
  `users_id` tinyint(4) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
