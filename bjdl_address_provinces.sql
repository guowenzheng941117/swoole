/*
Navicat MySQL Data Transfer

Source Server         : 192.168.0.148
Source Server Version : 50726
Source Host           : 192.168.0.148:3306
Source Database       : beijingdl

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2019-10-22 09:31:05
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for bjdl_address_provinces
-- ----------------------------
DROP TABLE IF EXISTS `bjdl_address_provinces`;
CREATE TABLE `bjdl_address_provinces` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` int(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of bjdl_address_provinces
-- ----------------------------
INSERT INTO `bjdl_address_provinces` VALUES ('1', '11', '北京市');
INSERT INTO `bjdl_address_provinces` VALUES ('2', '12', '天津市');
INSERT INTO `bjdl_address_provinces` VALUES ('3', '13', '河北省');
INSERT INTO `bjdl_address_provinces` VALUES ('4', '14', '山西省');
INSERT INTO `bjdl_address_provinces` VALUES ('5', '15', '内蒙古自治区');
INSERT INTO `bjdl_address_provinces` VALUES ('6', '21', '辽宁省');
INSERT INTO `bjdl_address_provinces` VALUES ('7', '22', '吉林省');
INSERT INTO `bjdl_address_provinces` VALUES ('8', '23', '黑龙江省');
INSERT INTO `bjdl_address_provinces` VALUES ('9', '31', '上海市');
INSERT INTO `bjdl_address_provinces` VALUES ('10', '32', '江苏省');
INSERT INTO `bjdl_address_provinces` VALUES ('11', '33', '浙江省');
INSERT INTO `bjdl_address_provinces` VALUES ('12', '34', '安徽省');
INSERT INTO `bjdl_address_provinces` VALUES ('13', '35', '福建省');
INSERT INTO `bjdl_address_provinces` VALUES ('14', '36', '江西省');
INSERT INTO `bjdl_address_provinces` VALUES ('15', '37', '山东省');
INSERT INTO `bjdl_address_provinces` VALUES ('16', '41', '河南省');
INSERT INTO `bjdl_address_provinces` VALUES ('17', '42', '湖北省');
INSERT INTO `bjdl_address_provinces` VALUES ('18', '43', '湖南省');
INSERT INTO `bjdl_address_provinces` VALUES ('19', '44', '广东省');
INSERT INTO `bjdl_address_provinces` VALUES ('20', '45', '广西壮族自治区');
INSERT INTO `bjdl_address_provinces` VALUES ('21', '46', '海南省');
INSERT INTO `bjdl_address_provinces` VALUES ('22', '50', '重庆市');
INSERT INTO `bjdl_address_provinces` VALUES ('23', '51', '四川省');
INSERT INTO `bjdl_address_provinces` VALUES ('24', '52', '贵州省');
INSERT INTO `bjdl_address_provinces` VALUES ('25', '53', '云南省');
INSERT INTO `bjdl_address_provinces` VALUES ('26', '54', '西藏自治区');
INSERT INTO `bjdl_address_provinces` VALUES ('27', '61', '陕西省');
INSERT INTO `bjdl_address_provinces` VALUES ('28', '62', '甘肃省');
INSERT INTO `bjdl_address_provinces` VALUES ('29', '63', '青海省');
INSERT INTO `bjdl_address_provinces` VALUES ('30', '64', '宁夏回族自治区');
INSERT INTO `bjdl_address_provinces` VALUES ('31', '65', '新疆维吾尔自治区');
