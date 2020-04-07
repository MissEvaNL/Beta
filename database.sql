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

 Date: 06/04/2020 18:14:24
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
INSERT INTO `cms_ban_messages` VALUES (2, 'Oplichten van een of meerdere Talpa\'s');
INSERT INTO `cms_ban_messages` VALUES (3, 'Illegale activiteiten');
INSERT INTO `cms_ban_messages` VALUES (4, 'Haatzaaien/discriminatie');
INSERT INTO `cms_ban_messages` VALUES (5, 'Pedofiele activiteiten');
INSERT INTO `cms_ban_messages` VALUES (6, 'Vragen/weggeven van persoonlijke informatie');
INSERT INTO `cms_ban_messages` VALUES (7, 'Vragen/weggeven van snapchat, insta of andere Social Media');
INSERT INTO `cms_ban_messages` VALUES (8, 'Lastigvallen / onacceptabel taalgebruik of gedrag');
INSERT INTO `cms_ban_messages` VALUES (9, 'Ordeverstoring');
INSERT INTO `cms_ban_messages` VALUES (10, 'Nadrukkelijk seksuele gedragingen');
INSERT INTO `cms_ban_messages` VALUES (11, 'Vragen/aanbieden van webscam seks of seksuele afbeeldingen');
INSERT INTO `cms_ban_messages` VALUES (12, 'Oplichten van een of meerdere Talpa\'s');
INSERT INTO `cms_ban_messages` VALUES (13, 'Bedreigen van een of meerdere Talpa\'s met ddos/hack/expose');
INSERT INTO `cms_ban_messages` VALUES (14, 'Voordoen als Talpa Staff');
INSERT INTO `cms_ban_messages` VALUES (15, 'Talpanaam in strijd met de Talpa Regels');

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for cms_news_category
-- ----------------------------
DROP TABLE IF EXISTS `cms_news_category`;
CREATE TABLE `cms_news_category`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for cms_pages_copy1
-- ----------------------------
DROP TABLE IF EXISTS `cms_pages_copy1`;
CREATE TABLE `cms_pages_copy1`  (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `page` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `blockname` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for cms_permissions
-- ----------------------------
DROP TABLE IF EXISTS `cms_permissions`;
CREATE TABLE `cms_permissions`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for cms_permissions_copy1
-- ----------------------------
DROP TABLE IF EXISTS `cms_permissions_copy1`;
CREATE TABLE `cms_permissions_copy1`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for cms_permissions_ranks
-- ----------------------------
DROP TABLE IF EXISTS `cms_permissions_ranks`;
CREATE TABLE `cms_permissions_ranks`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_id` int(11) NOT NULL,
  `permissions_groups` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for cms_shop_category
-- ----------------------------
DROP TABLE IF EXISTS `cms_shop_category`;
CREATE TABLE `cms_shop_category`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for cms_shop_objects
-- ----------------------------
DROP TABLE IF EXISTS `cms_shop_objects`;
CREATE TABLE `cms_shop_objects`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `param` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `object` varchar(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

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
-- Table structure for cms_widgets
-- ----------------------------
DROP TABLE IF EXISTS `cms_widgets`;
CREATE TABLE `cms_widgets`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `widget` varchar(24) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
