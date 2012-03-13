/*
MySQL Data Transfer
Source Host: localhost
Source Database: guinnessapp
Target Host: localhost
Target Database: guinnessapp
Date: 3/13/2012 1:32:58 PM
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for campaign_app
-- ----------------------------
CREATE TABLE `campaign_app` (
  `APP_APPLICATION_ID` bigint(100) NOT NULL,
  `APP_API_KEY` varchar(100) DEFAULT NULL,
  `APP_SECRET_KEY` varchar(100) NOT NULL,
  `APP_CANVAS_PAGE` varchar(100) DEFAULT NULL,
  `APP_CANVAS_URL` varchar(100) DEFAULT NULL,
  `APP_APPLICATION_NAME` text,
  `APP_EXT_PERMISSIONS` text,
  `APP_FANPAGE` text,
  `APP_AGE_RESTRICTION` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`APP_APPLICATION_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaign_assets
-- ----------------------------
CREATE TABLE `campaign_assets` (
  `asset_id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_name` varchar(255) NOT NULL,
  `asset_basename` varchar(255) NOT NULL,
  `asset_url` varchar(255) NOT NULL,
  `asset_thumb_url` varchar(255) NOT NULL,
  `asset_type` varchar(100) NOT NULL,
  `asset_width` int(11) DEFAULT NULL,
  `asset_height` int(11) DEFAULT NULL,
  `asset_mimetype` varchar(30) DEFAULT NULL,
  `asset_platform` enum('facebook','mobile') NOT NULL,
  `asset_bgcolor` varchar(7) DEFAULT NULL,
  PRIMARY KEY (`asset_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaign_customer
-- ----------------------------
CREATE TABLE `campaign_customer` (
  `uid` bigint(20) NOT NULL,
  `customer_id` bigint(20) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'active',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaign_customer_fbauthorization
-- ----------------------------
CREATE TABLE `campaign_customer_fbauthorization` (
  `uid` bigint(20) NOT NULL,
  `APP_APPLICATION_ID` bigint(11) NOT NULL,
  `authorized_date` datetime DEFAULT NULL,
  `deauthorized_date` datetime DEFAULT NULL,
  `authorized` tinyint(11) NOT NULL DEFAULT '0',
  `access_token` varchar(255) NOT NULL,
  PRIMARY KEY (`uid`,`APP_APPLICATION_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaign_customer_traction
-- ----------------------------
CREATE TABLE `campaign_customer_traction` (
  `CUSTOMER_ID` int(11) NOT NULL AUTO_INCREMENT,
  `FIRSTNAME` varchar(255) DEFAULT NULL,
  `LASTNAME` varchar(255) DEFAULT NULL,
  `EMAIL` varchar(255) DEFAULT NULL,
  `ADDRESS` varchar(255) DEFAULT NULL,
  `MOBILE` varchar(255) DEFAULT NULL,
  `SUBSCRIPTIONID1` varchar(255) DEFAULT NULL,
  `GID` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`CUSTOMER_ID`),
  UNIQUE KEY `unique emal` (`EMAIL`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaign_group
-- ----------------------------
CREATE TABLE `campaign_group` (
  `GID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `startdate` datetime NOT NULL,
  `upload_enddate` datetime NOT NULL,
  `enddate` datetime NOT NULL,
  `winner_selectiondate` datetime NOT NULL,
  `status` varchar(12) NOT NULL DEFAULT 'active',
  `allowed_media_source` varchar(20) NOT NULL,
  `allowed_media_type` varchar(20) NOT NULL,
  `allowed_media_fields` varchar(255) NOT NULL,
  `allowed_mimetype` varchar(255) NOT NULL,
  `campaign_rules` text,
  `campaign_mechanism` text,
  `campaign_policy` text,
  `campaign_fbshare_media` text,
  `campaign_twshare_media` text,
  `APP_APPLICATION_ID` varchar(100) DEFAULT NULL,
  `theme_dir` varchar(100) DEFAULT NULL,
  `media_has_approval` tinyint(4) NOT NULL DEFAULT '0',
  `media_has_fbcomment` tinyint(4) NOT NULL DEFAULT '0',
  `media_has_fblike` tinyint(4) NOT NULL DEFAULT '0',
  `media_has_vote` tinyint(4) NOT NULL DEFAULT '0',
  `media_has_uploadonce` tinyint(4) NOT NULL,
  `winner_announced` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`GID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaign_group_assets
-- ----------------------------
CREATE TABLE `campaign_group_assets` (
  `GID` int(11) NOT NULL,
  `asset_id` int(11) NOT NULL,
  PRIMARY KEY (`GID`,`asset_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaign_group_customer
-- ----------------------------
CREATE TABLE `campaign_group_customer` (
  `customer_id` int(11) NOT NULL,
  `uid` bigint(20) NOT NULL,
  `GID` int(11) NOT NULL,
  PRIMARY KEY (`customer_id`,`uid`,`GID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaign_media
-- ----------------------------
CREATE TABLE `campaign_media` (
  `media_id` int(11) NOT NULL AUTO_INCREMENT,
  `media_title` varchar(255) DEFAULT NULL,
  `media_description` text,
  `media_type` enum('image','video') DEFAULT NULL,
  `media_source` enum('youtube','facebook','twitpic','plixi','yfrog','file') DEFAULT NULL,
  `media_url` varchar(255) DEFAULT NULL,
  `media_thumb_url` varchar(255) DEFAULT NULL,
  `media_medium_url` varchar(255) DEFAULT NULL,
  `media_status` varchar(12) DEFAULT NULL,
  `GID` int(11) DEFAULT NULL,
  `media_uploaded_date` datetime DEFAULT NULL,
  `media_uploaded_timestamp` int(11) DEFAULT NULL,
  `media_basename` varchar(255) DEFAULT NULL,
  `media_winner` tinyint(4) DEFAULT '0',
  `media_vote_total` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`media_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaign_media_owner
-- ----------------------------
CREATE TABLE `campaign_media_owner` (
  `media_id` int(11) NOT NULL,
  `uid` bigint(11) NOT NULL,
  `GID` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaign_media_vote
-- ----------------------------
CREATE TABLE `campaign_media_vote` (
  `media_id` int(11) NOT NULL,
  `uid` bigint(20) NOT NULL,
  `unix_timestamp` int(11) DEFAULT NULL,
  PRIMARY KEY (`media_id`,`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaign_page
-- ----------------------------
CREATE TABLE `campaign_page` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `GID` int(11) DEFAULT NULL,
  `page_title` varchar(150) DEFAULT NULL,
  `page_short_name` varchar(30) DEFAULT NULL,
  `page_body` longtext,
  `page_status` varchar(10) NOT NULL DEFAULT 'publish',
  `page_facebook` tinyint(4) NOT NULL DEFAULT '0',
  `page_mobile` tinyint(4) NOT NULL DEFAULT '0',
  `page_publish_date` datetime DEFAULT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaign_user
-- ----------------------------
CREATE TABLE `campaign_user` (
  `user_ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_FBID` bigint(20) DEFAULT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(100) NOT NULL,
  `user_access_level` varchar(255) DEFAULT 'administrator',
  `user_status` varchar(100) DEFAULT 'active',
  `user_last_active` int(11) DEFAULT NULL,
  `user_registered_date` datetime DEFAULT NULL,
  `user_registered_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_ID`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `campaign_app` VALUES ('282088055180043', '282088055180043', 'a00334433dd5a7e5acd5f86f7c12928a', 'http://apps.facebook.com/guinnidgentestone/', 'http://guinnessapp.dev/campaign/canvas/282088055180043/', 'Guinness App Dev', 'publish_stream,email,user_birthday,user_hometown,user_interests,user_likes', 'http://www.facebook.com/devthink', '0');
INSERT INTO `campaign_user` VALUES ('1', null, 'Admin', 'admin@demo.com', '76a2173be6393254e72ffa4d6df1030a', 'administrator', 'active', null, null, null);
