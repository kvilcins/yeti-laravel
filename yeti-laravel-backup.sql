-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Хост: mysql
-- Время создания: Июн 17 2025 г., 14:26
-- Версия сервера: 8.0.41
-- Версия PHP: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `yeti-laravel`
--

-- --------------------------------------------------------

--
-- Структура таблицы `bids`
--

CREATE TABLE `bids` (
  `id` bigint UNSIGNED NOT NULL,
  `lot_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `bid_amount` decimal(10,2) NOT NULL,
  `bid_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `bids`
--

INSERT INTO `bids` (`id`, `lot_id`, `user_id`, `bid_amount`, `bid_time`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 12001.00, '2025-01-20 12:07:19', '2025-06-17 13:26:36', '2025-06-17 13:26:36'),
(2, 1, 4, 12002.00, '2025-01-20 12:08:44', '2025-06-17 13:26:36', '2025-06-17 13:26:36'),
(3, 26, 4, 1200.00, '2025-06-16 11:15:01', '2025-06-16 11:15:01', '2025-06-16 11:15:01');

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `class`, `created_at`, `updated_at`) VALUES
(1, 'Доски и лыжи', 'boards', 'boards', '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(2, 'Крепления', 'attachment', 'attachment', '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(3, 'Ботинки', 'boots', 'boots', '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(4, 'Одежда', 'clothing', 'clothing', '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(5, 'Инструменты', 'tools', 'tools', '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(6, 'Разное', 'other', 'other', '2025-06-17 14:25:38', '2025-06-17 14:25:38');

-- --------------------------------------------------------

--
-- Структура таблицы `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `items`
--

CREATE TABLE `items` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` int UNSIGNED NOT NULL,
  `min_bid` int UNSIGNED NOT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timer` timestamp NULL DEFAULT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `items`
--

INSERT INTO `items` (`id`, `title`, `slug`, `description`, `price`, `min_bid`, `img`, `timer`, `category_id`, `created_at`, `updated_at`) VALUES
(1, '2014 Rossignol District Snowboard', '2014-rossignol-district-snowboard', 'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчком и четкими дугами.\n                          Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия\n                          в сочетании с классическим прогибом кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется,\n                          просто посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла равнодушным.', 10999, 12000, '../img/lot-1.jpg', NULL, 1, '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(2, 'DC Ply Mens 2016/2017 Snowboard', 'dc-ply-mens-2016-2017-snowboard', 'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчком и четкими дугами.\n                          Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия\n                          в сочетании с классическим прогибом кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется,\n                          просто посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла равнодушным.', 159999, 12000, '../img/lot-2.jpg', NULL, 1, '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(3, 'Крепления Union Contact Pro 2015 года размер L/XL', 'union-contact-pro-2015', 'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчком и четкими дугами.\n                          Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия\n                          в сочетании с классическим прогибом кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется,\n                          просто посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла равнодушным.', 8000, 12000, '../img/lot-3.jpg', NULL, 2, '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(4, 'Ботинки для сноуборда DC Mutiny Charocal', 'dc-mutiny-charocal', 'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчком и четкими дугами.\n                          Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия\n                          в сочетании с классическим прогибом кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется,\n                          просто посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла равнодушным.', 10999, 12000, '../img/lot-4.jpg', NULL, 3, '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(5, 'Куртка для сноуборда DC Mutiny Charocal', 'dc-mutiny-charocal-jacket', 'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчком и четкими дугами.\n                          Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия\n                          в сочетании с классическим прогибом кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется,\n                          просто посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла равнодушным.', 7500, 12000, '../img/lot-5.jpg', NULL, 4, '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(6, 'Маска Oakley Canopy', 'oakley-canopy', 'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчком и четкими дугами.\n                          Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия\n                          в сочетании с классическим прогибом кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется,\n                          просто посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла равнодушным.', 5400, 12000, '../img/lot-6.jpg', NULL, 6, '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(7, 'Сноуборд PRIME Surf', 'prime-surf', 'Особенности:\nМужской сноуборд\nЖесткость: средняя\nНазначение: All-mountain, подготовленные трассы\nУровень райдера: новичок, продвинутый\nГеометрия Twin-Tip: симметричная геометрия и жесткость делает доску максимально сбалансированной и универсальной и дает максимальную мобильность для фристайла\nПрогиб Camber: классический прогиб доски обладает высокой стабильностью на скоростях и отлично держит кант при закладывании дуг, а так же имеет взрывной щелчок\nКонструкция сноуборда: CAP\nСердечник Light Woodcore: облегченный сердечник из древесины тополя обладает прочностью и дает единую гибкость доски по всей длине\nСтекловолокно Triaxial Fiberglass: укладывается в трёх направлениях, обеспеяивая высокую жесткость, отзывчивость и стабильность\nБоковые стенки Polyurethane ABS Sidewall: высокопрочные бесшовные боковые стенки из полиуретана отлично демпфируют и равномерно распределяют ударную нагрузку\nЭкструдированный скользяк Extruded 4400: прочный и простой в обслуживании\nЗакладные Tank Armour Inserts (16 шт.): прочные закладные из нержавеющей стали марки 304\nВерхний слой ABS TOPSHEET with UV-Protection: высококачественный прочный верхний слой с защитой от царапин и ультрафиолетовых лучей \nСтальной кант\nСистема креплений 2x4', 17780, 100, 'img/678d26e85dde6.jpg', NULL, 1, '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(8, 'Куртка утепленная мужская Termit', 'termit-jacket', 'Конструктивные особенности\nПокрой	\nПрямой\nДлина	\nСредняя\nКапюшон	\nНе отстегивается\nЗастежка	\nМолния​\nКоличество карманов	\n2\nСнегозащитная юбка	\nНе отстегивается\nФункциональные особенности\nВодоотталкивающая пропитка	\nДа\nЗащита от ветра	\nДа\nУтеплитель	\nСинтетический\nОбщие характеристики\nВид спорта	\nСноубординг\nПол	\nМужчины\nГарантия подлинности товара	\nДа\nСостав\nМатериал верха	\n100% полиэстер\nМатериал утеплителя	\n100% полиэстер\nМатериал подкладки	\n100% полиэстер\nДополнительные характеристики\nВес утеплителя на м2	\n100\nКод производителя	\n124847\nСтрана производства	\nКитай\nСезон	\nЗима\nУход за товаром\nРекомендации по уходу	\nЩадящая стирка 30 °C. Не отбеливать. Сушка в машине запрещена. Глажение запрещено. Химчистка запрещена.\nДополнительная информация	\nСтирать специальным средством. Не замачивать.', 1999, 100, 'img/678d27bc0229e.jpg', NULL, 4, '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(9, 'Куртка утепленная женская Termit', 'termit-jacket-women', 'ВОДОНЕПРОНИЦАЕМАЯ МЕМБРАНА\nМембрана Dry\'vex защищает от промокания и отводит от тела излишки тепла и влаги. Показатели водонепроницаемости и паропроницаемости: 5000 мм / 5000 г/м2/24 ч.', 3799, 150, 'img/678d28009e8a9.jpg', NULL, 4, '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(10, 'Крепления сноубордические Union Flite Pro', 'union-flite-pro', 'Union Flite Pro — чемпионы по легкости среди сноубордических креплений. Эта модель для фристайла идеально подойдет начинающим и прогрессирующим сноубордистам. Универсальные диски совместимы с системой закладных 4x4, 4x2, Channel, 3D.\n\nНАДЕЖНАЯ ФИКСАЦИЯ\nВерхний стреп Forma обеспечивает хорошую передачу усилия и стабильность при управлении доской. Носочный стреп TS 4.0 надежно фиксирует ботинок. Алюминиевые бакли отличаются мягким ходом.\nПРОЧНОСТЬ\nПяточная дуга изготовлена из прочного экструдированного алюминия Extruded 3D Aluminum не деформируется под воздействием нагрузок. Она обеспечивает оптимальную поддержку пятки и минимизирует сопротивление.\nТОЧНАЯ ПЕРЕДАЧА ЭНЕРГИИ\nЛегкая и жесткая база из суперпрочного материала Duraflex гарантирует высокую производительность в широком диапазоне минусовых температур. Уменьшенная площадь контакта с доской обеспечивает еще большую степень отзывчивости.\nАМОРТИЗАЦИЯ\nБаза частично выполнена из пены ЭВА и превосходно компенсирует ударные нагрузки при приземлениях.', 17599, 200, 'img/678d2843dc7d4.jpg', NULL, 2, '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(11, 'Сноубордические ботинки Terror Fastec', 'terror-fastec', 'Сноубордические ботинки Terror с фиксатором шнуровки на язычке. Прочный непромокаемый материал выдержит трение о крепления и обеспечит тепло и сухость ног во время катания.\n\nКОМФОРТ\nТермоформуемый внутренник с анатомическими вкладышами и поддержкой голеностопа. 3D-язычок для дополнительного комфорта.\nБЫСТРАЯ ФИКСАЦИЯ\nПредусмотрена система быстрой фиксации ботинка. Обувание не займет много времени!\nАМОРТИЗАЦИЯ\nСтелька из пеноматериала ЭВА и облегченная резиновая подошва для амортизации.\nУСТОЙЧИВОСТЬ К ИЗНОСУ\nВнешний ботинок выполнен из прочного материала. Благодаря этому модель прослужит не один сезон.', 18719, 200, 'img/678d28d18c57d.jpg', NULL, 3, '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(12, 'Маска Uvex Pyrit FM', 'uvex-pyrit-fm', 'Базовая маска от Uvex. Безрамочная конструкция и зеркальная линза обеспечивают стильный внешний вид и функциональность модели. Световой фильтр S2 (база зеленая, внешнее покрытие зеркальное синее).\n\nЗАЩИТА ОТ УЛЬТРАФИОЛЕТА\nВстроенные фильтры от UVA-, UVB- и UVC-излучения обеспечивают надежную защиту ваших глаз.\nЗАЩИТА ОТ ЗАПОТЕВАНИЯ\nПокрытие Supravision предотвращает образование конденсата на линзе.\nСОВМЕСТИМОСТЬ С ОЧКАМИ\nМаску можно надевать поверх очков, корректирующих зрение.\nКОМФОРТ\nУплотнитель из велюра, вентилируемая оправа и стреп с силиконовым покрытием гарантируют комфортную и надежную фиксацию маски.', 7499, 100, 'img/678d293d7b705.jpg', NULL, 6, '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(13, 'Маска горнолыжная Uvex Splash', 'maska-gornolyzhnaya-uvex-splash', 'Легкая маска для катания в пасмурную погоду от Uvex.\n\nЗАЩИТА ОТ УЛЬТРАФИОЛЕТА\nЛинза со встроенными фильтрами со 100% защитой от всех видов ультрафиолетового излучения.\nЗАЩИТА ОТ ЗАПОТЕВАНИЯ\nСпециальное покрытие не позволяет маске запотеть.', 3999, 1000, 'img/679605dab4c82.jpg', NULL, 6, '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(14, 'Сноубордические ботинки Nitro Team TLS', 'snoubordicheskie-botinki-nitro-team-tls', 'Жесткие ботинки Nitro для продвинутых и профессиональных райдеров. Подойдут для универсального катания и бэккантри. Съемный усилитель язычка позволяет подстраивать жесткость под стиль катания.', 100, 20, 'img/683f3c75171d1.jpg', NULL, 3, '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(23, 'Балаклава Airhole Balaclava Full Hinge', 'balaklava-airhole-balaclava-full-hinge-2', 'Удобная балаклава Airhole с фирменным отверстием для дыхания предназначена для занятий зимними видами спорта. Модель надежно защищает лицо от снега и встречного ветра.', 4699, 500, 'img/683f4eaee4745.jpg', NULL, 4, '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(25, 'Шлем Uvex Wanted', 'shlem-uvex-wanted', 'All-mountain шлем с глубокой посадкой Uvex wanted. Прочная внешняя конструкция Hardshell и амортизирующий внутренний слой EPS гарантируют максимальную защиту. Подкладка с дополнительным утеплителем в области шеи для комфорта во время катания. Регулируемая вентиляция поддерживает оптимальный микроклимат внутри шлема.', 12999, 500, 'img/683f52208745a.jpg', NULL, 6, '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(26, 'Сноуборд Termit Savage', 'snoubord-termit-savage', 'Сноуборд Savage от Termit — идеальный выбор для любителей фрирайда. Надежная универсальная доска подходит для катания на высоких склонах, отлично справляется со скоростным спуском по трассе и всплывает в легком пухляке.', 27999, 1000, 'img/684ffc9078641.jpg', NULL, 1, '2025-06-17 14:25:38', '2025-06-17 14:25:38');

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_09_04_102242_create_categories_table', 1),
(6, '2024_09_04_102353_create_items_table', 1),
(7, '2024_09_10_143237_add_contact_and_avatar_to_users_table', 1),
(8, '2024_09_23_122209_create_pages_table', 1),
(9, '2025_01_20_105833_create_bids_table', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `pages`
--

CREATE TABLE `pages` (
  `id` bigint UNSIGNED NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default_value',
  `route` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default_value',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `pages`
--

INSERT INTO `pages` (`id`, `slug`, `name`, `title`, `content`, `type`, `route`, `created_at`, `updated_at`) VALUES
(1, 'main', 'Главная', 'Главная', NULL, 'default_value', 'default_value', '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(2, 'add', 'Добавление лота', 'Добавление лота', NULL, 'default_value', 'lot.create', '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(3, 'lot', 'Лот', 'Лот', '1', 'default_value', 'default_value', '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(4, 'viewed-lots', 'Просмотренные лоты', 'Просмотренные лоты', NULL, 'default_value', 'viewed.lots', '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(5, 'register', 'Регистрация', 'Регистрация', NULL, 'default_value', 'register', '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(6, 'login', 'Авторизация', 'Авторизация', NULL, 'default_value', 'login', '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(7, 'search', 'Поиск', 'Поиск', NULL, 'default_value', 'search', '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(8, 'search-suggestions', 'Поисковые подсказки', 'Поисковые подсказки', NULL, 'default_value', 'search.suggestions', '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(9, 'category', 'Категория', 'Категория', '1', 'default_value', 'default_value', '2025-06-17 14:25:38', '2025-06-17 14:25:38'),
(10, 'profile', 'Профиль', 'Личный кабинет', NULL, 'default_value', 'default_value', '2025-06-17 14:25:38', '2025-06-17 14:25:38');

-- --------------------------------------------------------

--
-- Структура таблицы `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `contact_details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `contact_details`, `avatar`) VALUES
(1, 'Игнат', 'ignat.v@gmail.com', NULL, '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka', NULL, '2025-06-17 13:26:36', '2025-06-17 13:26:36', NULL, NULL),
(2, 'Леночка', 'kitty_93@li.ru', NULL, '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa', NULL, '2025-06-17 13:26:36', '2025-06-17 13:26:36', NULL, NULL),
(3, 'Руслан', 'warrior07@mail.ru', NULL, '$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW', NULL, '2025-06-17 13:26:36', '2025-06-17 13:26:36', NULL, NULL),
(4, 'Екатерина', 'kvilcins@mail.ru', NULL, '$2y$12$e9FT7Ob0RER29vs9NZDOBuL2q2t09f6QfX9J3FrSibbQdcl07.PG2', NULL, '2025-06-17 13:26:36', '2025-06-17 13:36:14', NULL, 'avatars/caTpSTVXJpoq75D3kXZJESMCjS3tmBujh9I6prF4.webp'),
(6, 'kvilcins', 'kvilcins@list.ru', NULL, '$2y$12$jfmum6z7v2PVl12PQcu9x.qXDWhfA59kFEvvftH.mA2YqjEK60LxG', NULL, '2025-06-17 14:16:46', '2025-06-17 14:24:49', '12345', 'avatars/2czOzEDaHgtAO47O3Q3c2j78Cv9diiEilYta6UdZ.webp');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `bids`
--
ALTER TABLE `bids`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bids_lot_id_foreign` (`lot_id`),
  ADD KEY `bids_user_id_foreign` (`user_id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Индексы таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Индексы таблицы `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `items_slug_unique` (`slug`),
  ADD KEY `items_category_id_foreign` (`category_id`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pages_slug_unique` (`slug`);

--
-- Индексы таблицы `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Индексы таблицы `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `bids`
--
ALTER TABLE `bids`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `bids`
--
ALTER TABLE `bids`
  ADD CONSTRAINT `bids_lot_id_foreign` FOREIGN KEY (`lot_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bids_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
