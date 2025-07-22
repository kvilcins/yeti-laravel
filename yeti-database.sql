-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 22, 2025 at 09:25 PM
-- Server version: 8.0.42-33
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ch46607_yeti`
--

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

CREATE TABLE IF NOT EXISTS `bids` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `lot_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `bid_amount` decimal(10,2) NOT NULL,
  `bid_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bids_lot_id_foreign` (`lot_id`),
  KEY `bids_user_id_foreign` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bids`
--

INSERT INTO `bids` (`id`, `lot_id`, `user_id`, `bid_amount`, `bid_time`, `created_at`, `updated_at`) VALUES
(16, 8, 6, 200.00, '2025-06-24 17:22:02', '2025-06-24 17:22:02', '2025-06-24 17:22:02'),
(17, 22, 6, 360.00, '2025-06-24 17:23:14', '2025-06-24 17:23:14', '2025-06-24 17:23:14'),
(18, 17, 6, 200.00, '2025-06-24 17:23:42', '2025-06-24 17:23:42', '2025-06-24 17:23:42'),
(19, 8, 7, 250.00, '2025-06-24 17:24:02', '2025-06-24 17:24:02', '2025-06-24 17:24:02'),
(20, 17, 7, 230.00, '2025-06-24 17:24:22', '2025-06-24 17:24:22', '2025-06-24 17:24:22'),
(21, 12, 7, 95.00, '2025-06-24 17:24:31', '2025-06-24 17:24:31', '2025-06-24 17:24:31'),
(22, 4, 10, 169.00, '2025-06-25 12:13:34', '2025-06-25 12:13:34', '2025-06-25 12:13:34'),
(23, 15, 10, 69.00, '2025-06-25 12:14:50', '2025-06-25 12:14:50', '2025-06-25 12:14:50'),
(24, 13, 10, 269.00, '2025-06-25 12:37:03', '2025-06-25 12:37:03', '2025-06-25 12:37:03'),
(30, 14, 12, 104.00, '2025-07-13 15:37:54', '2025-07-13 15:37:54', '2025-07-13 15:37:54');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `class` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `class`, `created_at`, `updated_at`) VALUES
(1, 'Boards and skis', 'boards', 'boards', '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(2, 'Bindings', 'attachment', 'attachment', '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(3, 'Shoes', 'boots', 'boots', '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(4, 'Clothes', 'clothing', 'clothing', '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(5, 'Tools', 'tools', 'tools', '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(6, 'Other', 'other', 'other', '2025-06-24 17:19:03', '2025-06-24 17:19:03');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` int UNSIGNED NOT NULL,
  `min_bid` int UNSIGNED NOT NULL,
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timer` timestamp NULL DEFAULT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `status` enum('active','inactive','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `winner_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `items_slug_unique` (`slug`),
  KEY `items_category_id_foreign` (`category_id`),
  KEY `items_user_id_foreign` (`user_id`),
  KEY `items_winner_id_foreign` (`winner_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `title`, `slug`, `description`, `price`, `min_bid`, `img`, `timer`, `category_id`, `user_id`, `status`, `winner_id`, `created_at`, `updated_at`) VALUES
(1, '2014 Rossignol District Snowboard', '2014-rossignol-district-snowboard', 'A lightweight agile snowboard ready to dominate any park with explosive pops and razor-sharp turns.', 120, 120, 'lots/ig17f48s2C1mr82dLwOQ5PkEhuhLBPoHO8RTmNJC.jpg', '2025-09-12 00:00:00', 1, 1, 'active', NULL, '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(2, 'DC Ply Mens 2016/2017 Snowboard', 'dc-ply-mens-2016-2017-snowboard', 'A lightweight agile snowboard ready to dominate any park with explosive pops and razor-sharp turns.\r\nBi-Ax fiberglass laid in two directions gives this board excellent flex and responsiveness, while symmetrical geometry\r\ncombined with classic camber profile allows you to confidently hold high speeds. And if you\'re completely exhausted by the end of the riding day,\r\njust look at your board and smile - the sick graphics by Sean Cliver never leave anyone indifferent.', 160, 160, 'lots/j9b28hiNmcIoWlbh2WOwlxQBCiy3xhMtMX7EvYE3.jpg', '2025-09-11 00:00:00', 1, 1, 'active', NULL, '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(3, 'Union Contact Pro 2015 bindings size L/XL', 'union-contact-pro-2015-bindings-size-l-xl', 'A lightweight maneuverable snowboard ready to bring the heat in any park, melting snow with powerful ollies and crisp carves.\r\nBi-Ax fiberglass laid in two directions gives this board excellent flex and responsiveness, while the symmetrical geometry\r\ncombined with classic camber profile lets you confidently hold high speeds. And if you\'re completely wiped out by the end of the riding day,\r\njust look at your board and smile - the awesome graphics by Sean Cliver never fail to impress.', 80, 80, 'lots/GYR0DXL78pA1yz5UBNQXNyHfcKGNplm0Er9qEccF.jpg', '2025-10-02 00:00:00', 2, 1, 'active', NULL, '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(4, 'DC Mutiny Charcoal snowboard boots', 'dc-mutiny-charocal', 'A lightweight maneuverable snowboard ready to bring the heat in any park, melting snow with powerful ollies and crisp carves.\r\nBi-Ax fiberglass laid in two directions gives this board excellent flex and responsiveness, while the symmetrical geometry\r\ncombined with classic camber profile lets you confidently hold high speeds. And if you\'re completely wiped out by the end of the riding day,\r\njust look at your board and smile - the awesome graphics by Sean Cliver never fail to impress.', 109, 109, 'lots/lpq390CWX4pNYPgqPqkKUgkOdsrLEYXb9ZzttzuF.jpg', '2025-06-30 00:00:00', 3, 1, 'active', NULL, '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(5, 'DC Mutiny Charcoal snowboard jacket', 'dc-mutiny-charocal-jacket', 'A lightweight maneuverable snowboard ready to bring the heat in any park, melting snow with powerful ollies and crisp carves.\r\nBi-Ax fiberglass laid in two directions gives this board excellent flex and responsiveness, while the symmetrical geometry\r\ncombined with classic camber profile lets you confidently hold high speeds. And if you\'re completely wiped out by the end of the riding day,\r\njust look at your board and smile - the awesome graphics by Sean Cliver never fail to impress.', 75, 75, 'lots/3ttibYfgonWctE5OzwXgJoHAfKpDGyXdG5m8RKiy.jpg', '2025-11-13 00:00:00', 4, 1, 'active', NULL, '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(6, 'Oakley Canopy goggles', 'oakley-canopy', 'A lightweight maneuverable snowboard ready to bring the heat in any park, melting snow with powerful ollies and crisp carves.\r\nBi-Ax fiberglass laid in two directions gives this board excellent flex and responsiveness, while the symmetrical geometry\r\ncombined with classic camber profile lets you confidently hold high speeds. And if you\'re completely wiped out by the end of the riding day,\r\njust look at your board and smile - the awesome graphics by Sean Cliver never fail to impress.', 54, 54, 'lots/DL0vukPSwxc06Bx5yqCs6ukc8XgCypjbTjzJzUKz.jpg', '2025-10-01 00:00:00', 6, 1, 'active', NULL, '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(7, 'Snowboard PRIME Surf', 'prime-surf', 'Features:\r\nMen\'s snowboard\r\nFlex: medium\r\nPurpose: All-mountain, groomed runs\r\nRider level: beginner, advanced\r\nTwin-Tip geometry: symmetrical geometry and flex makes the board maximally balanced and versatile and provides maximum mobility for freestyle\r\nCamber profile: classic board camber provides high stability at speeds and excellent edge hold when carving, as well as explosive pop\r\nSnowboard construction: CAP\r\nLight Woodcore: lightweight poplar wood core provides strength and gives uniform flex throughout the board length\r\nTriaxial Fiberglass: laid in three directions, providing high stiffness, responsiveness and stability\r\nPolyurethane ABS Sidewalls: high-strength seamless polyurethane sidewalls provide excellent dampening and evenly distribute impact loads\r\nExtruded 4400 base: durable and easy to maintain\r\nTank Armour Inserts (16 pcs): durable stainless steel 304 grade inserts\r\nABS TOPSHEET with UV-Protection: high-quality durable top layer with protection from scratches and UV rays\r\nSteel edges\r\n2x4 binding system', 177, 177, 'lots/DypH3F0qj892V1OferdhIIx85QJVmtsLW8jOOu0e.jpg', '2025-10-17 00:00:00', 1, 1, 'active', NULL, '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(8, 'Termit men\'s insulated jacket', 'termit-jacket', 'Design Features\r\nCut\r\nRegular\r\nLength\r\nMedium\r\nHood\r\nNon-detachable\r\nClosure\r\nZipper\r\nNumber of pockets\r\n2\r\nSnow skirt\r\nNon-detachable\r\n\r\nFunctional Features\r\nWater-repellent treatment\r\nYes\r\nWind protection\r\nYes\r\nInsulation\r\nSynthetic\r\n\r\nGeneral Characteristics\r\nSport\r\nSnowboarding\r\nGender\r\nMen\r\nProduct authenticity guarantee\r\nYes\r\n\r\nComposition\r\nUpper material\r\n100% polyester\r\nInsulation material\r\n100% polyester\r\nLining material\r\n100% polyester\r\n\r\nAdditional Characteristics\r\nInsulation weight per m²\r\n100\r\nManufacturer code\r\n124847\r\nCountry of manufacture\r\nChina\r\nSeason\r\nWinter\r\n\r\nCare Instructions\r\nCare recommendations\r\nGentle wash 30°C. Do not bleach. Tumble drying prohibited. Ironing prohibited. Dry cleaning prohibited.\r\nAdditional information\r\nWash with special detergent. Do not soak.', 199, 199, 'lots/sJIJTFRHowaQBoXATgq8oGqL37Nl2z7wNng7hWT6.jpg', '2025-06-25 00:00:00', 4, 2, 'active', NULL, '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(9, 'Termit women\'s insulated jacket', 'termit-jacket-women', 'WATERPROOF MEMBRANE\r\nDry\'vex membrane protects against water penetration and wicks away excess heat and moisture from the body. Waterproof and breathability ratings: 5000 mm / 5000 g/m²/24h.', 379, 379, 'lots/6XmNL2qGwX2CmlAW7kIwKsGuAxfMHJVWYmU4wv8U.jpg', '2025-11-19 00:00:00', 4, 2, 'active', NULL, '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(10, 'Union Flite Pro snowboard bindings', 'union-flite-pro', 'Union Flite Pro — the lightweight champions among snowboard bindings. This freestyle model is perfect for beginner and progressing snowboarders. Universal discs are compatible with 4x4, 4x2, Channel, 3D mounting systems.\r\n\r\nSECURE FIXATION\r\nForma highback provides good force transmission and stability when controlling the board. TS 4.0 toe strap securely holds the boot. Aluminum buckles feature smooth operation.\r\n\r\nDURABILITY\r\nHeelcup is made from durable Extruded 3D Aluminum that doesn\'t deform under load. It provides optimal heel support and minimizes resistance.\r\n\r\nPRECISE ENERGY TRANSFER\r\nLightweight and stiff baseplate made from super-durable Duraflex material guarantees high performance across a wide range of sub-zero temperatures. Reduced contact area with the board provides even greater responsiveness.\r\n\r\nCUSHIONING\r\nBaseplate is partially made from EVA foam and excellently absorbs impact loads during landings.', 175, 175, 'lots/Q9dvIEIlAzTTT3hOVf1GyqRvhjnHfHapgkqJdXKS.jpg', '2025-11-12 00:00:00', 2, 6, 'active', NULL, '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(11, 'Terror Fastec snowboard boots', 'terror-fastec', 'Terror snowboard boots with lace lock on the tongue. Durable waterproof material withstands binding friction and keeps feet warm and dry during riding.\r\n\r\nCOMFORT\r\nHeat-moldable liner with anatomical inserts and ankle support. 3D tongue for additional comfort.\r\n\r\nQUICK LACING\r\nQuick boot lacing system is provided. Putting on won\'t take much time!\r\n\r\nCUSHIONING\r\nEVA foam insole and lightweight rubber sole for cushioning.\r\n\r\nWEAR RESISTANCE\r\nOuter boot is made from durable material. Thanks to this, the model will last more than one season.', 187, 187, 'lots/b782ZoZbmk8sMy6YjYmjDSEzEO8qfpeKCTQdoqg1.jpg', '2025-06-25 00:00:00', 3, 6, 'active', NULL, '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(12, 'Uvex Pyrit FM Goggles', 'uvex-pyrit-fm', 'Basic mask from Uvex. Frameless construction and mirror lens provide stylish appearance and functionality. Light filter S2 (green base, blue mirror external coating).\r\n\r\nUV PROTECTION\r\nBuilt-in UVA, UVB, and UVC radiation filters provide reliable protection for your eyes.\r\n\r\nANTI-FOG PROTECTION\r\nSupravision coating prevents condensation formation on the lens.\r\n\r\nGLASSES COMPATIBILITY\r\nThe mask can be worn over prescription glasses.\r\n\r\nCOMFORT\r\nVelour padding, ventilated frame and silicone-coated strap guarantee comfortable and secure mask fit.', 90, 90, 'lots/MSqozGcot3SUcyt9TaGlKlnko4Cd8GLuhbuXYGa6.jpg', '2025-06-25 00:00:00', 6, 1, 'active', NULL, '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(13, 'Uvex Splash ski goggles', 'uvex-splash-ski-goggles', 'Lightweight mask for riding in cloudy weather from Uvex.\r\n\r\nUV PROTECTION\r\nLens with built-in filters providing 100% protection from all types of ultraviolet radiation.\r\n\r\nANTI-FOG PROTECTION\r\nSpecial coating prevents the mask from fogging up.', 115, 115, 'lots/05FWVXICCu5GFsiQ3wJd85ialCbnPwtDV7NuIN3e.jpg', '2025-08-22 00:00:00', 6, 2, 'active', NULL, '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(14, 'Nitro Team TLS snowboard boots', 'nitro-team-tls-snowboard-boots', 'Stiff Nitro boots for advanced and professional riders. Suitable for all-mountain riding and backcountry. Removable tongue stiffener allows you to adjust stiffness to your riding style.', 100, 100, 'lots/OKByzJCXEEWs119m1ecJ5jJfSHCIkHhNALamyT0p.jpg', '2025-08-18 00:00:00', 3, 2, 'active', NULL, '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(15, 'Airhole Balaclava Full Hinge', 'airhole-balaclava-full-hinge', 'Comfortable Airhole balaclava with signature breathing hole designed for winter sports. The model reliably protects the face from snow and headwind.', 46, 46, 'lots/2JZjHgFtk27nl7da2VcPwpHFL4Ogpp9FrSqISZ0M.jpg', '2025-08-07 00:00:00', 4, 6, 'active', NULL, '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(16, 'OutdoorMaster Suni Ski Helmet', 'outdoormaster-suni-ski-helmet', 'Superior Protection: OutdoorMaster Ski Helmet is made with high-strength ABS shell and high-density EPS core, ensuring durability and impact resistance. It complies with ASTM F2040-18 certification standards, providing reliable safety protection.\r\n2 Sizes: M (55-58 cm, 600 g) and L (58-61 cm, 610 g). Snowboard helmet is equipped with an adjustable dial and straps to ensure a proper fit for various head shapes.\r\nStay Comfortable: Snowboard helmet features a goggle strap holder, a removable and washable velvet liner and ear pads, ear pads compatible with Bluetooth headphones, 6 ventilation holes. It offers a comfortable wearing experience.\r\nVersatile Helmet: Includes a visor to effectively block sunlight and reduce wind resistance, making it ideal for skiing, biking, skateboarding, and other activities.\r\nWhat You Get: 1 x OutdoorMaster Ski Helmet and dedicated customer service. If you have any questions or issues with the helmet, Please feel free to contact us. We are here to assist you.', 40, 40, 'lots/gT7MIP15WopGIlzC9RGMjBvgR5JF0SEyIRTiS600.webp', '2025-10-04 00:00:00', 6, 1, 'active', NULL, '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(17, 'Uvex Wanted helmet', 'helmet-uvex-wanted', 'All-mountain helmet with deep fit Uvex wanted. Durable Hardshell external construction and shock-absorbing EPS inner layer guarantee maximum protection. Lining with additional insulation in the neck area for comfort during riding. Adjustable ventilation maintains optimal microclimate inside the helmet.', 100, 100, 'lots/KPoeqOwfMz7BFYh3mnyeGuEO7eyvLflM9YC4KHHP.jpg', '2025-06-25 00:00:00', 6, 2, 'active', NULL, '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(18, 'Snowboard Termit Savage', 'snowboard-termit-savage', 'Savage snowboard from Termit — the perfect choice for freeride enthusiasts. Reliable all-mountain board suitable for riding on high slopes, excellently handles high-speed descents on groomed runs and floats in light powder.', 259, 259, 'lots/QM8YFrrZbOWKcdmcXOvXHpyxse2zrrPZ2vE920F2.jpg', '2025-06-25 00:00:00', 1, 6, 'active', NULL, '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(19, 'Airhole Full Hinge balaclava', 'airhole-full-hinge-balaclava\n', 'Comfortable Airhole balaclava with signature breathing hole designed for winter sports. The model reliably protects the face from snow and headwind.', 45, 45, 'lots/yP8GpxcGcnV9E0dk9XAcxoLzpjNcZpGxE9McKVqZ.jpg', '2025-06-25 00:00:00', 4, 1, 'active', NULL, '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(20, 'Rossignol Cobra Snowboard Bindings', 'rossignol-cobra-snowboard-bindings', 'Powerful Cobra M/L bindings for the most precise board control.\r\n\r\nPRECISE ENERGY TRANSFER Asymmetric highback fits snugly against the boot and transfers energy to the board, 2.5° base angle for a more natural stance and efficient control.\r\nCUSHIONING Dual-density padding under the foot absorbs impacts and vibrations.\r\nSECURE FIXATION Features anatomical 3D straps, lightweight and reliable aluminum buckles for secure fixation.\r\nDURABILITY Reliable polyurethane base increases product durability.', 300, 300, 'lots/zpHZTfU9Y7t7SYbgiTgaAyBvsk7m8k0k9L064ZRm.jpg', '2025-10-05 00:00:00', 2, 1, 'active', NULL, '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(21, 'Snowboard Head True 2.0', 'snowboard-head-true-2-0', 'The updated directional hybrid board of medium rigidity is suitable for riders who want to progress, increase speed and improve the quality of riding. The camber-like shape of the model is more practical: the central area of the board is completely flat and strongly elevated above the contact points. This guarantees high accuracy of driving, the ability to pump the board well, while better feeling the edge in the batter on the plane.', 400, 400, NULL, '2025-06-23 00:00:00', 1, 1, 'completed', 7, '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(22, 'Burton Men\'s Process Snowboard', 'burton-men-s-process-snowboard', 'Twin Shape is perfectly symmetrical for a balanced ride whether you\'re riding regular or switch; Twin Flex is perfectly symmetrical from tip to tail\r\nPurePop Camber Bend features subtle flat zones to amplify pop; Super Fly II 700G Core uses stronger and lighter woods for added pop and strength\r\nDualzone EGD increases edge-hold and response using engineered wood grain along the toe and heel edges; Triax Fiberglass provides versatile flex and response on every ride\r\nSintered Base is highly porous for added durability and superior wax absorption; The Channel Board Mount dials-in your stance and works with all major bindings\r\n3-YEAR WARRANTY All 2014 and newer Burton snowboards with The Channel mounting system (such as the one you are looking at here) are backed by a three-year warranty from date of purchase.', 357, 357, 'lots/oScaBO16C0GyqN2nVLJiuzUwVcC1oDJIdRkJ6uxR.jpg', '2025-09-07 00:00:00', 1, 1, 'active', NULL, '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(23, 'MOERDENG Women\'s Waterproof Ski Jacket', 'moerdeng-women-s-waterproof-ski-jacket', 'Waterproof: Professional waterproof jacket. The outer fabric is waterproof and quick dry. All the zipper design can effectively resisting the water or rain, also can fights for bad weather, always keep your body dry and comfortable\r\nWindproof: 1) Adjustable hook & loop fastener cuffs help seal in warmth. 2) Internal drawcord hem, detachable and adjustable storm hood help to keep wind out. 3) Wear resisting soft shell is highly windproof.\r\nProfessional waterproof coated, fluff lining and durable fabric guarantees the best heat retention, Relaxed-fit style with quick-dry material.\r\nMultipurpose：Downhill Skiing, Snowboarding, Snowsports and other winter outdoor sports.\r\nSize notes: Jacket offers a standard fit. Please choose by US size.', 56, 56, 'lots/xtxjlpdTFsU3N1KdS4FvR8GkpLngUx9KuITp4BDc.jpg', '2025-08-31 00:00:00', 4, 1, 'active', NULL, '2025-06-24 17:19:03', '2025-06-24 17:19:03');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_09_04_102242_create_categories_table', 1),
(6, '2024_09_04_102353_create_items_table', 1),
(7, '2024_09_23_122209_create_pages_table', 1),
(8, '2025_01_20_105833_create_bids_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default_value',
  `route` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default_value',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `slug`, `name`, `title`, `content`, `type`, `route`, `created_at`, `updated_at`) VALUES
(1, 'main', 'Homepage', 'Homepage', NULL, 'default_value', 'default_value', '2025-06-24 17:19:02', '2025-06-24 17:19:02'),
(2, 'add', 'Add Item', 'Add Item', NULL, 'default_value', 'lot.create', '2025-06-24 17:19:02', '2025-06-24 17:19:02'),
(3, 'lot', 'Item', 'Item', '1', 'default_value', 'default_value', '2025-06-24 17:19:02', '2025-06-24 17:19:02'),
(4, 'viewed-lots', 'Viewing History', 'Viewing History', NULL, 'default_value', 'viewed.lots', '2025-06-24 17:19:02', '2025-06-24 17:19:02'),
(5, 'register', 'Sign up', 'Sign up', NULL, 'default_value', 'register', '2025-06-24 17:19:02', '2025-06-24 17:19:02'),
(6, 'login', 'Login', 'Login', NULL, 'default_value', 'login', '2025-06-24 17:19:02', '2025-06-24 17:19:02'),
(7, 'search', 'Search results', 'Search results', NULL, 'default_value', 'search', '2025-06-24 17:19:02', '2025-06-24 17:19:02'),
(8, 'search-suggestions', 'Search suggestions', 'Search suggestions', NULL, 'default_value', 'search.suggestions', '2025-06-24 17:19:02', '2025-06-24 17:19:02'),
(9, 'category', 'Category', 'Category', '1', 'default_value', 'default_value', '2025-06-24 17:19:03', '2025-06-24 17:19:03'),
(10, 'profile', 'Account', 'Account', NULL, 'default_value', 'profile', '2025-06-24 17:19:03', '2025-06-24 17:19:03');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('user','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `contact_details`, `avatar`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Kate', 'kvilcins@mail.ru', '2025-06-22 10:47:13', '$2y$12$./ra4/2XPRFQZPU2AOn1VuNen11ZrBuPVaO26PFWfWkFa9IQRMkSm', '@katie', 'avatars/KTor8w83JuohGWy8mVWrIoBQ6YbX2xwAeSzOtPcY.webp', 'admin', NULL, '2025-06-22 10:32:46', '2025-06-26 04:08:39'),
(2, 'test', 'test@gmail.com', NULL, '$2y$12$bFzEnQewNYtTqLu6Lw4lYOyKiftPIaLS9r9KR.jNgrEWYoi318wNm', NULL, NULL, 'user', NULL, '2025-06-20 18:55:09', '2025-06-20 18:55:09'),
(3, 'Potato', 'kvilcins@gmail.com', '2025-06-22 09:26:01', '$2y$12$RSDeAmjiMvODutZFhmXZiuqUCWOTsU0/x.MbgbBr9Bt/Zg/or/zN2', NULL, NULL, 'user', NULL, '2025-06-20 18:55:09', '2025-06-22 09:26:01'),
(6, 'User94', 'kvilcins@list.ru', '2025-06-23 12:40:17', '$2y$12$Sa7A31ejuMFG5rxWHffnsu.8fmT/.k4JHuDduwxWiue0xk/N.h9ZS', NULL, 'avatars/MqRYI30AGWgVwuTNFejvdTLiCSuJWAtdHP0wfwzU.webp', 'user', NULL, '2025-06-22 11:53:26', '2025-06-23 12:40:17'),
(7, 'Katherine', 'kategarina94@gmail.com', '2025-06-23 14:17:08', '$2y$12$kPSBFYvjWmbHImB4ZtfBpOx6hA1mG7MFbBwXvQQgM0a8Oz1x.LbrW', '+1 900 000 00 00', NULL, 'user', NULL, '2025-06-23 14:16:46', '2025-06-23 15:35:16'),
(9, 'test', 'test2@mail.com', NULL, '$2y$12$79WnfmaTGN274mTRHvKGOu5MPNjpFcYJx9iSW/URsp7P4haHgf4cG', '123456', NULL, 'user', NULL, '2025-06-25 11:44:27', '2025-06-25 11:44:27'),
(10, 'Roy Douglas', 'roydou12@gmail.com', '2025-06-25 12:12:42', '$2y$12$W0DS0veh0Xm/wCQki9VRPOydEz8/gEixcm5MnFDOkxwmcdwAIR4re', 'My butthole', 'avatars/Z4mQTTWrPp511xlS3yEIYlWl7QePoXXFtgLmI1N2.png', 'user', NULL, '2025-06-25 11:54:10', '2025-06-25 12:35:47'),
(11, 'Чебурек', 'kent@kentovskoy.com', '2025-06-25 16:45:25', '$2y$12$sRgck/m9gAtzs8z89A.JbeyXS0Uh2idIfhKpUDn1ZbzbwCoDgjugi', 'now', 'avatars/1ChczZQ0OYZEEWG4ZHltUPWqFFaCIvWExmqh8Vw8.png', 'user', NULL, '2025-06-25 12:42:33', '2025-06-25 12:47:26'),
(12, 'KARINA ALEIAN', 'lookbookkarina@mail.ru', '2025-07-13 15:36:50', '$2y$12$6eI/HjTVPLEqCWkwWIuJS.GnZxwrfYp1VT0vlXmw/CVd6C0ukiloa', 'Better not', 'avatars/SOobktstv5GwWPc5D6m7wa2DFoOir0CQpy8ehL1p.jpg', 'user', NULL, '2025-07-13 15:31:33', '2025-07-13 15:39:09');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bids`
--
ALTER TABLE `bids`
  ADD CONSTRAINT `bids_lot_id_foreign` FOREIGN KEY (`lot_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bids_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `items_winner_id_foreign` FOREIGN KEY (`winner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
