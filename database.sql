/*
 Navicat Premium Data Transfer

 Source Server         : Cosmic New
 Source Server Type    : MySQL
 Source Server Version : 100144
 Source Host           : 151.80.54.177:3306
 Source Schema         : beta

 Target Server Type    : MySQL
 Target Server Version : 100144
 File Encoding         : 65001

 Date: 07/04/2020 19:22:09
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cms_ban_messages
-- ----------------------------
DROP TABLE IF EXISTS `cms_ban_messages`;
CREATE TABLE `cms_ban_messages`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of cms_ban_messages
-- ----------------------------
INSERT INTO `cms_ban_messages` VALUES (1, 'Adverteren voor Retro Hotels');
INSERT INTO `cms_ban_messages` VALUES (2, 'Oplichten van een of meerdere beta\'s');
INSERT INTO `cms_ban_messages` VALUES (3, 'Illegale activiteiten');
INSERT INTO `cms_ban_messages` VALUES (4, 'Haatzaaien/discriminatie');
INSERT INTO `cms_ban_messages` VALUES (5, 'Pedofiele activiteiten');
INSERT INTO `cms_ban_messages` VALUES (6, 'Vragen/weggeven van persoonlijke informatie');
INSERT INTO `cms_ban_messages` VALUES (7, 'Vragen/weggeven van snapchat, insta of andere Social Media');
INSERT INTO `cms_ban_messages` VALUES (8, 'Lastigvallen / onacceptabel taalgebruik of gedrag');
INSERT INTO `cms_ban_messages` VALUES (9, 'Ordeverstoring');
INSERT INTO `cms_ban_messages` VALUES (10, 'Nadrukkelijk seksuele gedragingen');
INSERT INTO `cms_ban_messages` VALUES (11, 'Vragen/aanbieden van webscam seks of seksuele afbeeldingen');
INSERT INTO `cms_ban_messages` VALUES (12, 'Oplichten van een of meerdere beta\'s');
INSERT INTO `cms_ban_messages` VALUES (13, 'Bedreigen van een of meerdere beta\'s met ddos/hack/expose');
INSERT INTO `cms_ban_messages` VALUES (14, 'Voordoen als beta Staff');
INSERT INTO `cms_ban_messages` VALUES (15, 'betanaam in strijd met de beta Regels');

-- ----------------------------
-- Table structure for cms_ban_type
-- ----------------------------
DROP TABLE IF EXISTS `cms_ban_type`;
CREATE TABLE `cms_ban_type`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seconds` int(11) NOT NULL,
  `message` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `min_rank` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of cms_ban_type
-- ----------------------------
INSERT INTO `cms_ban_type` VALUES (1, 7200, '2 uur', NULL);
INSERT INTO `cms_ban_type` VALUES (2, 14400, '4 uur', NULL);
INSERT INTO `cms_ban_type` VALUES (3, 28800, '8 uur', NULL);
INSERT INTO `cms_ban_type` VALUES (4, 43200, '12 uur', NULL);
INSERT INTO `cms_ban_type` VALUES (5, 86400, '1 dag', NULL);
INSERT INTO `cms_ban_type` VALUES (6, 604800, '1 week', 5);
INSERT INTO `cms_ban_type` VALUES (7, 2629743, '1 maand', 5);
INSERT INTO `cms_ban_type` VALUES (8, 7889231, '3 maanden', 9);
INSERT INTO `cms_ban_type` VALUES (9, 946707780, 'permanent', 9);

-- ----------------------------
-- Table structure for cms_coupon
-- ----------------------------
DROP TABLE IF EXISTS `cms_coupon`;
CREATE TABLE `cms_coupon`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coupon` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `expire_date` date NOT NULL,
  `item_id` int(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for cms_feeds
-- ----------------------------
DROP TABLE IF EXISTS `cms_feeds`;
CREATE TABLE `cms_feeds`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to_user_id` int(11) NOT NULL,
  `message` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `from_user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for cms_feeds_likes
-- ----------------------------
DROP TABLE IF EXISTS `cms_feeds_likes`;
CREATE TABLE `cms_feeds_likes`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `feed_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for cms_feeds_reactions
-- ----------------------------
DROP TABLE IF EXISTS `cms_feeds_reactions`;
CREATE TABLE `cms_feeds_reactions`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feed_id` int(11) NOT NULL,
  `message` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `timestamp` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for cms_forum_category
-- ----------------------------
DROP TABLE IF EXISTS `cms_forum_category`;
CREATE TABLE `cms_forum_category`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of cms_forum_category
-- ----------------------------
INSERT INTO `cms_forum_category` VALUES (1, 'Welkom', 1);
INSERT INTO `cms_forum_category` VALUES (2, 'Beta', 2);
INSERT INTO `cms_forum_category` VALUES (3, 'Development', 3);
INSERT INTO `cms_forum_category` VALUES (4, 'Overig', 4);

-- ----------------------------
-- Table structure for cms_forum_forums
-- ----------------------------
DROP TABLE IF EXISTS `cms_forum_forums`;
CREATE TABLE `cms_forum_forums`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_at` datetime(0) NOT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `image` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `cat_id` int(11) NULL DEFAULT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of cms_forum_forums
-- ----------------------------
INSERT INTO `cms_forum_forums` VALUES (1, 'Algemene Regels', 'De regels van Habbox zijn speciaal ontworpen om een gezellige sfeer te maken en te behouden, ieder lid dient deze regels te volgen.', '2019-02-03 00:00:00', '2019-02-03 00:00:00', 'notice.gif', 1, 0);
INSERT INTO `cms_forum_forums` VALUES (2, 'Klachten, feedback en vragen', 'Heb je een klacht, of wil je ons een tip geven? Dat kan hier, in de klachten en feedback sectie!', '2019-02-03 00:00:00', '2019-02-03 00:00:00', 'lightbulb.gif', 1, 1);
INSERT INTO `cms_forum_forums` VALUES (4, 'Stel jezelf voor', 'Ben je nieuw op Habbox, of heb je je nog niet voorgesteld? Dan kan je dat nu doen.', '2019-02-01 00:00:00', '2019-02-01 00:00:00', 'hand.gif', 1, 2);
INSERT INTO `cms_forum_forums` VALUES (5, 'De kletshoek', 'Hier kun je over vanalles en nog wat praten.', '2019-02-03 00:00:00', '2019-02-03 00:00:00', 'newspapertext.gif', 2, 4);
INSERT INTO `cms_forum_forums` VALUES (6, 'Actualiteiten & Serieuze discussies', 'Hier kan je praten over alles dat actueel is', '2019-02-03 00:00:00', '2019-02-03 00:00:00', 'event.gif', 2, 0);
INSERT INTO `cms_forum_forums` VALUES (7, 'Webdevelopment', 'Alles m.b.t. webdevelopment vind je hier.', '2019-02-03 00:00:00', '2019-02-03 00:00:00', 'computer.png', 3, 0);
INSERT INTO `cms_forum_forums` VALUES (8, 'PHP Forum', 'Alles mbt PHP kan je hier plaatsen en vinden.', '2019-02-03 00:00:00', '2019-02-03 00:00:00', 'phone.gif', 3, 0);
INSERT INTO `cms_forum_forums` VALUES (9, 'Project Showcase & Releases', 'Heb jij een fantastisch project gemaakt en wil je die aan iedereen laten zien? Dan kan je dat hier doen!', '2019-02-03 00:00:00', '2019-02-03 00:00:00', 'camera.gif', 3, 0);
INSERT INTO `cms_forum_forums` VALUES (10, 'Development Overige', 'Mist er iets binnen de Development sectie, waar jij toevallig iets wilt over plaatsen? Geen vrees, dat kan hier!', '2019-02-03 00:00:00', '2019-02-03 00:00:00', 'sunglass.gif', 3, 0);
INSERT INTO `cms_forum_forums` VALUES (11, 'School en studies', 'Ben jij ook de docent wiskunde beu? Of wil je eerder praten over je lievelingsvak?', '2019-02-03 00:00:00', '2019-02-03 00:00:00', 'ufoeffect.gif', 4, 0);
INSERT INTO `cms_forum_forums` VALUES (12, 'Muziek, films, series...', 'Wat is jouw favoriete serie/film? Wat voor genre muziek beluister jij graag?', '2019-02-03 00:00:00', '2019-02-03 00:00:00', 'boombox.gif', 4, 0);
INSERT INTO `cms_forum_forums` VALUES (13, 'Forum spelletjes', 'Hier vind je de algemene forumspelletjes, zoals het Teltopic e.d.', '2019-02-03 00:00:00', '2019-02-03 00:00:00', 'forum-games.gif', 4, 0);
INSERT INTO `cms_forum_forums` VALUES (14, 'Overige', 'Alles wat niet in een ander forum past kan hier worden geplaatst', '2019-02-03 00:00:00', '2019-02-03 00:00:00', 'rubbish.png', 4, 0);
INSERT INTO `cms_forum_forums` VALUES (16, 'Wauuw!', 'Dit werkt ook perfect!', '0000-00-00 00:00:00', NULL, NULL, 7, 0);

-- ----------------------------
-- Table structure for cms_forum_likes
-- ----------------------------
DROP TABLE IF EXISTS `cms_forum_likes`;
CREATE TABLE `cms_forum_likes`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for cms_forum_posts
-- ----------------------------
DROP TABLE IF EXISTS `cms_forum_posts`;
CREATE TABLE `cms_forum_posts`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `content` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `topic_id` int(11) UNSIGNED NOT NULL,
  `created_at` datetime(0) NOT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `is_topic` int(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of cms_forum_posts
-- ----------------------------
INSERT INTO `cms_forum_posts` VALUES (1, 'dsadadsasdas', 1, 1, '2020-04-07 19:17:51', NULL, 1);

-- ----------------------------
-- Table structure for cms_forum_topics
-- ----------------------------
DROP TABLE IF EXISTS `cms_forum_topics`;
CREATE TABLE `cms_forum_topics`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `created_at` datetime(0) NOT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `forum_id` int(11) UNSIGNED NOT NULL,
  `is_sticky` tinyint(1) NOT NULL DEFAULT 0,
  `is_closed` tinyint(1) NOT NULL DEFAULT 0,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of cms_forum_topics
-- ----------------------------
INSERT INTO `cms_forum_topics` VALUES (1, 'Tstestest', '2020-04-07 19:17:51', NULL, 1, 1, 0, 0, 1);

-- ----------------------------
-- Table structure for cms_logs
-- ----------------------------
DROP TABLE IF EXISTS `cms_logs`;
CREATE TABLE `cms_logs`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `data` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `target` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for cms_news
-- ----------------------------
DROP TABLE IF EXISTS `cms_news`;
CREATE TABLE `cms_news`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `image` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `shortstory` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `longstory` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` int(11) NOT NULL DEFAULT 0,
  `type` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '1',
  `roomid` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '1',
  `updated` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `visible` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '1',
  `cat_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 47 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of cms_news
-- ----------------------------
INSERT INTO `cms_news` VALUES (39, 'Hackers attempt!', 'https://habbobites.com/uploads/15bb2f25ab3646_hjfpoqkmnlieg.gif', 'Laat je niet kisten!', '\r\n<b>Let op!!</b><br>\r\nAan al onze betaHotel spelers.\r\n<br />\r\n<br>\r\nHelaas komt het geregeld voor dat wij meldingen krijgen van spelers waarvan hun account is weggenomen door \'\'hackers\'\', vaak beloven deze hackers allemaal gratis items zoals Diamanten, Bel-Credits, Superzeldzame meubels, etc. \r\nHet is verstandig om niet akkoord te gaan met deze aangeboden \'deals\' omdat deze \'\'hackers\'\' jou vaak om je wachtwoord van je beta-account vragen kunnen zij je wachtwoord en emailadres veranderen met als gevolg dat je niet meer in kan loggen op beta met jouw account.\r\nUiteraard begrijpen wij van de betaHotel staff dat gratis items erg leuk zijn om te krijgen, dit is ook zeker mogelijk.\r\n<br /><br /><b>Op de volgende manieren krijg/maak je kans op gratis items.</b><br />\r\n<ul>\r\n<li> Per 30 minuten dat je online bent krijg je gratis één diamant. </li>\r\n<li> Doe mee met gokspellen (hier zit echter wel een risico aan vast) </li>\r\n<li> Doe mee met prijsvragen en events van andere spelers</li>\r\n<li> Doe mee met staffevents </li>\r\n<li> Soms kan je diamanten/Bel-Credits krijgen als je het Hotel een uitzonderlijke dienst hebt bewezen! </li>\r\n</ul><br /><br />\r\n<b>Iemand biedt mij gratis spullen aan maar ik vertrouw het niet, wat nu?</b><br><img alt=\"\" src=\"https://images.habbo.com/c_images/Security/safetytips1_n.png\" style=\"float:right;\"  />\r\nAls iemand je gratis spullen aanbiedt en je dit niet vertrouwt bedank dan vriendelijk. Verder raden wij je aan om deze beta aan te geven via de Helptool, dan komen wij in actie en zullen wij zonodig verregaande maatregelen treffen tegen deze speler.	\r\n<br>\r\n<br>\r\n<b>Tot slot..</b><br>\r\nLaat je niet kisten door \"Hackers\" ze zijn niet stoer, ze zijn crimineel. \r\nMocht er iemand zijn die jou wel degelijk gratis aan items heeft geholpen via een (vermoedelijk) illegale manier, meldt dit dan meteen.\r\nAls later blijkt dat jij op de hoogte was van de manier waarop deze items zijn vergaard zul je voor altijd worden verbannen en doen wij aangifte bij de politie.<br /><br />\r\nMet Pixelrijke groeten,,<br />\r\n<b>De beta staff</b>', 3768, 1551982740, '1', '1', '0', '1', 2);
INSERT INTO `cms_news` VALUES (45, 'Welkom op beta!', 'https://www.habbobites.com/uploads/15c6eae9cf285e_jloefimgkqnph.png', 'Welkom!', '<p><strong>De nieuwste website van betaHotel!</strong><br />Zoals je mogelijk wel hebt zien is de website betaHotel niet compleet af.. maar toch heeft het team besloten om beta open te stellen voor iedereen! Goed nieuws toch? Je zult elke dag wel een aantal verandering zien gebeuren, dit komt omdat wij realtime updates aan het uitvoeren zijn. Dus mocht de website even niet bereikbaar zijn of zie je een aantal foutmeldingen waar je van schrikt, geen paniek! We zullen snel weer terugbereikbaar zijn :-)!<br /><br /><strong>Idee&euml;n box</strong><br />beta is altijd opzoek naar vernieuwingen die we kunnen doorvoeren op de website, zo om het interessant te houden voor onze spelers maar uiteraard vinden wij het ook belangrijk dat jij je thuis voelt! Heb je een idee waarvan je denkt; h&eacute;! dit vind ik wel een toegevoegde waarde, laat het ons dan weten!<br /><br /><img src=\"https://images.habbo.com/c_images/Fansites/habbo_friends.png\" alt=\"\" /><strong>Hotel updates</strong><br />Niet alleen op de website zullen wij updates doorvoeren maar zo ook op het hotel. We proberen de servers zo min mogelijk te herstarten, alleen kom je daar niet altijd 1,2,3 onderuit. We vragen graag jouw begrip daarvoor! Ook voor idee&euml;n rondom het hotel horen wij graag, we zullen deze meenemen in een van onze volgende besprekingen om te kijken of dit binnen beta past. Wil je nu al solliciteren? Klik dan hier!<br /><br /><strong>Verder...</strong><br />beta heeft nog een aantal sollicitaties staan die binnenkort vrij komen. Gaarne hier niet constant over te vragen, je ziet vanzelf op de website wanneer de sollicitaties open staan!<br /><br />Mocht je nog vragen hebben, stel ze gerust! STRAATxJONGEN</p>', 3760, 1585637803, '1', '1', '0', '1', 1);

-- ----------------------------
-- Table structure for cms_news_category
-- ----------------------------
DROP TABLE IF EXISTS `cms_news_category`;
CREATE TABLE `cms_news_category`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of cms_news_category
-- ----------------------------
INSERT INTO `cms_news_category` VALUES (1, 'Website');
INSERT INTO `cms_news_category` VALUES (2, 'Hotel');
INSERT INTO `cms_news_category` VALUES (3, 'KvdW');

-- ----------------------------
-- Table structure for cms_news_like
-- ----------------------------
DROP TABLE IF EXISTS `cms_news_like`;
CREATE TABLE `cms_news_like`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(255) NULL DEFAULT NULL,
  `newsid` int(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for cms_news_message
-- ----------------------------
DROP TABLE IF EXISTS `cms_news_message`;
CREATE TABLE `cms_news_message`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL DEFAULT 0,
  `news_id` int(11) NULL DEFAULT NULL,
  `user_id` int(11) NULL DEFAULT NULL,
  `message` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for cms_pages
-- ----------------------------
DROP TABLE IF EXISTS `cms_pages`;
CREATE TABLE `cms_pages`  (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `page` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `blockname` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of cms_pages
-- ----------------------------
INSERT INTO `cms_pages` VALUES (1, 'Dashboard', 'dashboard');
INSERT INTO `cms_pages` VALUES (2, 'Forum', 'forum');
INSERT INTO `cms_pages` VALUES (3, 'News', 'news');
INSERT INTO `cms_pages` VALUES (4, 'Online', 'online');
INSERT INTO `cms_pages` VALUES (5, 'Feeds', 'feeds');

-- ----------------------------
-- Table structure for cms_permissions
-- ----------------------------
DROP TABLE IF EXISTS `cms_permissions`;
CREATE TABLE `cms_permissions`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of cms_permissions
-- ----------------------------
INSERT INTO `cms_permissions` VALUES (1, 'housekeeping', 'Gebruiker heeft toegang tot het controlepaneel. Speler kan verder nog niets doen wanneer hij/zij alleen deze skill toegewezen krijgt');
INSERT INTO `cms_permissions` VALUES (2, 'housekeeping_remote_control', 'Gebruiker kan en mag andere spelers hun account informatie aanpassen, behalve de ranks.');
INSERT INTO `cms_permissions` VALUES (3, 'housekeeping_ban_user', 'Gebruiker mag gebruikers bannen vanuit het controlepaneel. In deze permissie zit een rank-systeem verwerkt.');
INSERT INTO `cms_permissions` VALUES (4, 'housekeeping_ban_logs', 'Gebruiker mag inzien van welke spelers het toegang tot het het hotel is ontnomen');
INSERT INTO `cms_permissions` VALUES (5, 'housekeeping_staff_logs', 'Gebruiker mag alle loggings inzien die staffs hebben begaan in het hotel');
INSERT INTO `cms_permissions` VALUES (6, 'housekeeping_chat_logs', 'Gebruiker mag chat-logs uitlezen van andere spelers');
INSERT INTO `cms_permissions` VALUES (7, 'housekeeping_website', 'Gebruiker heeft toegang tot de website categorie');
INSERT INTO `cms_permissions` VALUES (8, 'housekeeping_website_news', 'Gebruiker mag nieuws toevoegen/verwijderen en of wijzigen');
INSERT INTO `cms_permissions` VALUES (9, 'housekeeping_ranks', 'Gebruiker mag ranks van andere spelers wijzigen');
INSERT INTO `cms_permissions` VALUES (10, 'housekeeping_permissions', 'Gebruiker kan en mag permissies instellen voor het gebruik van de housekeeping');
INSERT INTO `cms_permissions` VALUES (11, 'housekeeping_settings', 'Gebruiker kan cms instellingen aanpassen');
INSERT INTO `cms_permissions` VALUES (12, 'housekeeping_cms_tools', 'Gebruiker heeft toegang tot het beheren van forum/shop en widgets');
INSERT INTO `cms_permissions` VALUES (13, 'housekeeping_ip_display', 'Gebruiker kan en mag ipadressen zien van andere spelers');

-- ----------------------------
-- Table structure for cms_permissions_ranks
-- ----------------------------
DROP TABLE IF EXISTS `cms_permissions_ranks`;
CREATE TABLE `cms_permissions_ranks`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_id` int(11) NOT NULL,
  `permissions_groups` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 59 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of cms_permissions_ranks
-- ----------------------------
INSERT INTO `cms_permissions_ranks` VALUES (1, 1, 11);
INSERT INTO `cms_permissions_ranks` VALUES (2, 2, 11);
INSERT INTO `cms_permissions_ranks` VALUES (3, 3, 11);
INSERT INTO `cms_permissions_ranks` VALUES (4, 4, 11);
INSERT INTO `cms_permissions_ranks` VALUES (5, 5, 11);
INSERT INTO `cms_permissions_ranks` VALUES (6, 6, 11);
INSERT INTO `cms_permissions_ranks` VALUES (7, 7, 11);
INSERT INTO `cms_permissions_ranks` VALUES (8, 8, 11);
INSERT INTO `cms_permissions_ranks` VALUES (9, 9, 11);
INSERT INTO `cms_permissions_ranks` VALUES (10, 10, 11);
INSERT INTO `cms_permissions_ranks` VALUES (25, 11, 11);
INSERT INTO `cms_permissions_ranks` VALUES (27, 12, 11);
INSERT INTO `cms_permissions_ranks` VALUES (30, 13, 11);
INSERT INTO `cms_permissions_ranks` VALUES (31, 1, 7);
INSERT INTO `cms_permissions_ranks` VALUES (32, 3, 7);
INSERT INTO `cms_permissions_ranks` VALUES (33, 6, 7);
INSERT INTO `cms_permissions_ranks` VALUES (34, 7, 7);
INSERT INTO `cms_permissions_ranks` VALUES (35, 8, 7);
INSERT INTO `cms_permissions_ranks` VALUES (37, 4, 7);
INSERT INTO `cms_permissions_ranks` VALUES (38, 2, 7);
INSERT INTO `cms_permissions_ranks` VALUES (39, 1, 10);
INSERT INTO `cms_permissions_ranks` VALUES (40, 2, 10);
INSERT INTO `cms_permissions_ranks` VALUES (41, 3, 10);
INSERT INTO `cms_permissions_ranks` VALUES (42, 4, 10);
INSERT INTO `cms_permissions_ranks` VALUES (43, 6, 10);
INSERT INTO `cms_permissions_ranks` VALUES (44, 7, 10);
INSERT INTO `cms_permissions_ranks` VALUES (45, 8, 10);
INSERT INTO `cms_permissions_ranks` VALUES (47, 1, 4);
INSERT INTO `cms_permissions_ranks` VALUES (48, 2, 4);
INSERT INTO `cms_permissions_ranks` VALUES (49, 4, 4);
INSERT INTO `cms_permissions_ranks` VALUES (50, 3, 4);
INSERT INTO `cms_permissions_ranks` VALUES (51, 6, 4);
INSERT INTO `cms_permissions_ranks` VALUES (54, 1, 9);
INSERT INTO `cms_permissions_ranks` VALUES (55, 2, 9);
INSERT INTO `cms_permissions_ranks` VALUES (56, 3, 9);
INSERT INTO `cms_permissions_ranks` VALUES (57, 4, 9);
INSERT INTO `cms_permissions_ranks` VALUES (58, 6, 9);

-- ----------------------------
-- Table structure for cms_present_items
-- ----------------------------
DROP TABLE IF EXISTS `cms_present_items`;
CREATE TABLE `cms_present_items`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `column` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `type` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for cms_settings
-- ----------------------------
DROP TABLE IF EXISTS `cms_settings`;
CREATE TABLE `cms_settings`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sitename` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `hotelname` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `siteurl` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `secretkey` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `emulatorname` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `rank` int(11) NOT NULL,
  `credits` int(11) NOT NULL,
  `pixels` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `motto` varchar(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `postslimit` int(11) NOT NULL,
  `spamlimit` int(11) NOT NULL,
  `rangmodtools` int(11) NOT NULL,
  `userinactivity` int(11) NOT NULL,
  `maintenance` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `ip` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `port` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `musport` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `swf` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `habboswf` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `habboswffolder` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `externaltexts` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `overridetexts` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `externalvariables` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `overridevariables` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `productdata` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `furnidata` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `flashclient` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `recaptchakey` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `latestpostslimit` int(11) NOT NULL DEFAULT 5,
  `discord_invite` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of cms_settings
-- ----------------------------
INSERT INTO `cms_settings` VALUES (1, 'Beta', 'Beta Hotel', 'https://projectbeta.online/', 'YJ0nPVisM0JoLMrSer7KWPP7A93CPJXl', 'Arcturus', 1, 10000, 500, 500, 'Welkom op beta!', 5, 5, 5, 3600, '0', 'uk2.protected.pw', '30085', '', '', 'https://swf.betahotel.nl/', 'bibliotheek/', 'gamedata/acc_head_U_antenna.txt', 'gamedata/override/acc_head_U_happy.txt', 'gamedata/acc_head_U_excited.txt', 'gamedata/override/acc_head_U_angry.txt', 'gamedata/acc_head_U_bobba.txt', 'gamedata/furnidata.xml', 'gordon/PRODUCTION-201601012205-226667486/', '6Ld7seYUAAAAAGzDcAorJbPN0-x9GIJFw_8s85jn', 5, 'jfzNca');

-- ----------------------------
-- Table structure for cms_shop_category
-- ----------------------------
DROP TABLE IF EXISTS `cms_shop_category`;
CREATE TABLE `cms_shop_category`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of cms_shop_category
-- ----------------------------
INSERT INTO `cms_shop_category` VALUES (1, 'Account Addons', 'Koop toevoegingen voor jouw account!');
INSERT INTO `cms_shop_category` VALUES (2, 'Forum Badges', 'Forum badges description');
INSERT INTO `cms_shop_category` VALUES (3, 'Font Styling', '....');

-- ----------------------------
-- Table structure for cms_shop_items
-- ----------------------------
DROP TABLE IF EXISTS `cms_shop_items`;
CREATE TABLE `cms_shop_items`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `item` varchar(64) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `price` int(11) NOT NULL,
  `objectname` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `image` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of cms_shop_items
-- ----------------------------
INSERT INTO `cms_shop_items` VALUES (1, 1, 'BBCode Addon', 'Voeg BBCode toe aan jouw account. BBCodes zorgen voor de juiste styling op jouw profiel en op het forum!', 100, 'bbcode', NULL);
INSERT INTO `cms_shop_items` VALUES (2, 2, 'Cutie Pie ', 'Met deze badge laat jij zien dat je niet alleen wilt zijn met valentijn. Want zeg nou eerlijk? Wie wilt dat nou niet!', 500, 'badge', '/templates/brain/style/images/forum/badges/cutiepie.png');
INSERT INTO `cms_shop_items` VALUES (3, 2, 'Glamorous', 'Hoe glamorous ben jij? Laat zien dat jij de ware perfectie uitstraalt', 500, 'badge', '/templates/brain/style/images/forum/badges/glamorous.png');
INSERT INTO `cms_shop_items` VALUES (4, 3, 'Gold gebruikersnaam', 'We gaan voor goud! Oh.. dat is de uitspraak van Joel Beukers.', 300, 'font', 'gold');
INSERT INTO `cms_shop_items` VALUES (5, 3, 'Unicorn font', 'Unicorn is toch het meest gewilde dier?', 300, 'font', 'unicorn');
INSERT INTO `cms_shop_items` VALUES (6, 2, 'Pretty Chill', 'Hoe chill ben jij? Ben jij altijd de gene die nooit reageert met wel leest? Bewijs het met deze badge!', 380, 'badge', '/templates/brain/style/images/forum/badges/prettychill.png');
INSERT INTO `cms_shop_items` VALUES (7, 2, 'Mew Mew', 'Voor alle dieren liefhebbers', 250, 'badge', '/templates/brain/style/images/forum/badges/mewmew.png');
INSERT INTO `cms_shop_items` VALUES (8, 2, 'Always AFK', 'Je bent altijd in het hotel maar niemand die je te spreken krijgt. De ultieme badge die jij moet hebben!', 100, 'badge', '/templates/brain/style/images/forum/badges/alwaysafk.png');

-- ----------------------------
-- Table structure for cms_shop_objects
-- ----------------------------
DROP TABLE IF EXISTS `cms_shop_objects`;
CREATE TABLE `cms_shop_objects`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `param` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `object` varchar(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of cms_shop_objects
-- ----------------------------
INSERT INTO `cms_shop_objects` VALUES (1, 'badge', 'array');
INSERT INTO `cms_shop_objects` VALUES (3, 'font', 'first');

-- ----------------------------
-- Table structure for cms_shop_purchases
-- ----------------------------
DROP TABLE IF EXISTS `cms_shop_purchases`;
CREATE TABLE `cms_shop_purchases`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for cms_spotlight
-- ----------------------------
DROP TABLE IF EXISTS `cms_spotlight`;
CREATE TABLE `cms_spotlight`  (
  `id` int(11) NOT NULL,
  `staff` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `messagestaff` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `messageuser` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of cms_spotlight
-- ----------------------------
INSERT INTO `cms_spotlight` VALUES (1, 100, 1, 'je inzet voor beta wordt gezien en gewaardeerd!', 'bij ons ben je niet onopvallend gebleven, ga zo door!');

-- ----------------------------
-- Table structure for cms_tags
-- ----------------------------
DROP TABLE IF EXISTS `cms_tags`;
CREATE TABLE `cms_tags`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `tag` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for cms_user_settings
-- ----------------------------
DROP TABLE IF EXISTS `cms_user_settings`;
CREATE TABLE `cms_user_settings`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(64) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `pin` int(6) NULL DEFAULT NULL,
  `token_expires_at` datetime(0) NULL DEFAULT NULL,
  `login_attempt` int(2) NULL DEFAULT NULL,
  `item_avatar` int(1) NULL DEFAULT NULL,
  `item_font` int(1) NULL DEFAULT NULL,
  `private_profile` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `profile_bio` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `is_sadmin` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `role` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `country` varchar(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of cms_user_settings
-- ----------------------------

-- ----------------------------
-- Table structure for cms_user_transactions
-- ----------------------------
DROP TABLE IF EXISTS `cms_user_transactions`;
CREATE TABLE `cms_user_transactions`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to_user_id` int(11) NOT NULL,
  `present_id` int(11) NOT NULL,
  `timestamp` int(11) NULL DEFAULT NULL,
  `from_user_id` int(11) NOT NULL,
  `gift` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for cms_widget_to_page
-- ----------------------------
DROP TABLE IF EXISTS `cms_widget_to_page`;
CREATE TABLE `cms_widget_to_page`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `widget_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of cms_widget_to_page
-- ----------------------------
INSERT INTO `cms_widget_to_page` VALUES (1, 1, 1);
INSERT INTO `cms_widget_to_page` VALUES (2, 2, 1);
INSERT INTO `cms_widget_to_page` VALUES (5, 3, 2);
INSERT INTO `cms_widget_to_page` VALUES (6, 1, 3);
INSERT INTO `cms_widget_to_page` VALUES (7, 4, 1);
INSERT INTO `cms_widget_to_page` VALUES (8, 2, 3);
INSERT INTO `cms_widget_to_page` VALUES (10, 2, 4);
INSERT INTO `cms_widget_to_page` VALUES (12, 3, 5);

-- ----------------------------
-- Table structure for cms_widgets
-- ----------------------------
DROP TABLE IF EXISTS `cms_widgets`;
CREATE TABLE `cms_widgets`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `widget` varchar(24) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of cms_widgets
-- ----------------------------
INSERT INTO `cms_widgets` VALUES (1, 'tags');
INSERT INTO `cms_widgets` VALUES (2, 'spotlight');
INSERT INTO `cms_widgets` VALUES (3, 'latestforum');
INSERT INTO `cms_widgets` VALUES (4, 'discord');
INSERT INTO `cms_widgets` VALUES (5, 'nowonline');

SET FOREIGN_KEY_CHECKS = 1;
