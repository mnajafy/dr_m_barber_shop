/*
 Navicat Premium Data Transfer

 Source Server         : mysql
 Source Server Type    : MySQL
 Source Server Version : 50711
 Source Host           : localhost:3306
 Source Schema         : drmbarbershop

 Target Server Type    : MySQL
 Target Server Version : 50711
 File Encoding         : 65001

 Date: 21/08/2020 17:49:26
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin`  (
  `Last_Name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `First_Name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `E_Mail` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Auth` int(11) NOT NULL
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci;

-- ----------------------------
-- Records of admin
-- ----------------------------
BEGIN;
INSERT INTO `admin` VALUES ('mohammad', 'najafy', 'm.najafy@hotmail.com', 'azerty', 1234);
COMMIT;

-- ----------------------------
-- Table structure for category
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci;

-- ----------------------------
-- Records of category
-- ----------------------------
BEGIN;
INSERT INTO `category` VALUES (1, 'alpha'), (2, 'delta'), (3, 'romeo');
COMMIT;

-- ----------------------------
-- Table structure for imgs
-- ----------------------------
DROP TABLE IF EXISTS `imgs`;
CREATE TABLE `imgs`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_general_ci;

-- ----------------------------
-- Records of imgs
-- ----------------------------
BEGIN;
INSERT INTO `imgs` VALUES (1, 'gallery_1.jpg', 'titre N°1', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Officia, fugit?', 2), (2, 'gallery_2.jpg', 'titre N°2', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Officia, fugit?', 1), (3, 'gallery_3.jpg', 'titre N°3', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Officia, fugit?', 1), (4, 'gallery_4.jpg', 'titre N°4', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Officia, fugit?', 3), (5, 'gallery_5.jpg', 'titre N°5', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Officia, fugit?', 2), (6, 'gallery_6.jpg', 'titre N°6', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Officia, fugit?', 3);
COMMIT;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 32 CHARACTER SET = utf8 COLLATE = utf8_persian_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
BEGIN;
INSERT INTO `users` VALUES (30, 'a', 'b'), (31, 'aa', 'bb');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
