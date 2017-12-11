/*
Navicat MySQL Data Transfer

Source Server         : ASSURMIX PROD
Source Server Version : 50543
Source Host           : 127.0.0.1:3306
Source Database       : assurmix

Target Server Type    : MYSQL
Target Server Version : 50543
File Encoding         : 65001

Date: 2017-12-11 13:47:11
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for webhook_comments
-- ----------------------------
DROP TABLE IF EXISTS `webhook_comments`;
CREATE TABLE `webhook_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_issue` int(11) DEFAULT NULL,
  `id_comment` int(11) DEFAULT NULL,
  `comment_content` blob,
  `comment_user` varchar(255) DEFAULT NULL,
  `comment_state` tinyint(4) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for webhook_comments_history
-- ----------------------------
DROP TABLE IF EXISTS `webhook_comments_history`;
CREATE TABLE `webhook_comments_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_comment` int(11) DEFAULT NULL,
  `date_modify` datetime DEFAULT NULL,
  `comment_before` blob,
  `comment_after` blob,
  `user` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for webhook_commits
-- ----------------------------
DROP TABLE IF EXISTS `webhook_commits`;
CREATE TABLE `webhook_commits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commit_content` varchar(255) DEFAULT NULL,
  `commit_url` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `payload` varchar(255) DEFAULT NULL,
  `id_issue` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for webhook_issues
-- ----------------------------
DROP TABLE IF EXISTS `webhook_issues`;
CREATE TABLE `webhook_issues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `issue_number` int(11) DEFAULT NULL,
  `etat_issue` tinyint(3) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `assigned` varchar(255) DEFAULT NULL,
  `content` blob,
  `title` varchar(255) DEFAULT NULL,
  `date_butoire` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for webhook_issues_history
-- ----------------------------
DROP TABLE IF EXISTS `webhook_issues_history`;
CREATE TABLE `webhook_issues_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_issue` int(11) DEFAULT NULL,
  `date_modify` datetime DEFAULT NULL,
  `field_modify` varchar(255) DEFAULT NULL,
  `issue_before` blob,
  `issue_after` blob,
  `user` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for webhook_issues_labels
-- ----------------------------
DROP TABLE IF EXISTS `webhook_issues_labels`;
CREATE TABLE `webhook_issues_labels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_issue` int(11) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=257 DEFAULT CHARSET=utf8;
SET FOREIGN_KEY_CHECKS=1;
