-- Koshelyok SQL Dump
-- version 0.0.1
-- http://bayarookie.wallst.ru
--
-- Хост: localhost
-- Время создания: October 12 2016, 01:07
-- Версия сервера: 50715
-- Версия PHP: 7.0.8-0ubuntu0.16.04.3

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
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `goods`
--

INSERT INTO `goods` (`id`, `name`, `comment`, `groups_id`) VALUES
(0, 'Дом', 'Движение денег внутри квартиры', -1);

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `groups`
--

INSERT INTO `groups` (`id`, `name`, `comment`) VALUES
(0, 'Коммуналка', 'Все коммунальные платежи'),
(1, 'Еда', 'вся еда');

-- --------------------------------------------------------

--
-- Структура таблицы `money`
--

CREATE TABLE `money` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `op_date` date NOT NULL,
  `op_summ` decimal(12,2) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `walls_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `walls_id` (`walls_id`)
) ENGINE=InnoDB AUTO_INCREMENT=326 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `money`
--

INSERT INTO `money` (`id`, `op_date`, `op_summ`, `goods_id`, `walls_id`, `comment`) VALUES
(0, '2015-11-25', '100.00', 0, 0, 'Стартовый капитал');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL DEFAULT '',
  `password` varchar(64) NOT NULL DEFAULT '',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'test', '1234');

-- --------------------------------------------------------

--
-- Структура таблицы `walls`
--

CREATE TABLE `walls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `comment` text NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `walls`
--

INSERT INTO `walls` (`id`, `name`, `comment`) VALUES
(0, 'наличные', 'в кармане, в шкафу'),
(1, 'Сбербанк', 'карта сбербанка');
