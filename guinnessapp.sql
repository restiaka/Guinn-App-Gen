/*
MySQL Data Transfer
Source Host: localhost
Source Database: guinnessapp
Target Host: localhost
Target Database: guinnessapp
Date: 3/9/2012 6:40:50 PM
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaign_group_assets
-- ----------------------------
CREATE TABLE `campaign_group_assets` (
  `GID` int(11) NOT NULL,
  `asset_id` int(11) NOT NULL,
  PRIMARY KEY (`GID`,`asset_id`)
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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `campaign_app` VALUES ('209681662395432', 'b805f7f9ad97c442eae2f29ee4705f1b', '6c29c3dc1ded207ac217cbe48deb29ef', 'http://apps.facebook.com/guinnidphotocontest/', 'http://fbguinnessphotocontest.think.web.id/', 'Guinness Photo Contest', 'publish_stream,email,offline_access', 'http://www.facebook.com/guinnessindonesia', '0');
INSERT INTO `campaign_app` VALUES ('201876996523314', '201876996523314', '364639819b4649d19c1b0533e96bdda9', 'http://apps.facebook.com/guinidcontesttwo/', 'http://guinnessapp.dev/', 'Guinness photo contest 2', 'publish_stream,email,user_birthday,user_hometown,user_interests,user_likes', 'http://www.facebook.com/guinnessindonesia', '1');
INSERT INTO `campaign_app` VALUES ('282088055180043', '282088055180043', 'a00334433dd5a7e5acd5f86f7c12928a', 'http://apps.facebook.com/guinnidgentestone/', 'http://guinnessapp.dev/campaign/canvas/282088055180043/', 'Guinness App Dev', 'publish_stream,email,user_birthday,user_hometown,user_interests,user_likes', 'http://www.facebook.com/guinnessindonesia', '0');
INSERT INTO `campaign_assets` VALUES ('9', 'Banner Header', 'e8cbb2a32a77df4ec08e09a3dac4778b.jpg', 'http://guinnessapp.dev/image/campaign?src=e8cbb2a32a77df4ec08e09a3dac4778b.jpg', 'http://guinnessapp.dev/image/campaign?src=thumb_e8cbb2a32a77df4ec08e09a3dac4778b.jpg', 'banner_header', '520', '100', 'image/jpeg', 'facebook', null);
INSERT INTO `campaign_assets` VALUES ('10', 'Main banner aja deh', '414b967de265a087d3d147e6f9254bed.jpg', 'http://guinnessapp.dev/image/campaign?src=414b967de265a087d3d147e6f9254bed.jpg', 'http://guinnessapp.dev/image/campaign?src=thumb_414b967de265a087d3d147e6f9254bed.jpg', 'banner_main', '400', '318', 'image/jpeg', 'facebook', null);
INSERT INTO `campaign_assets` VALUES ('11', 'Footer aja deh', 'fa77afca845a7105c1b09d2473a8d983.jpg', 'http://guinnessapp.dev/image/campaign?src=fa77afca845a7105c1b09d2473a8d983.jpg', 'http://guinnessapp.dev/image/campaign?src=thumb_fa77afca845a7105c1b09d2473a8d983.jpg', 'banner_footer', '120', '67', 'image/jpeg', 'facebook', null);
INSERT INTO `campaign_assets` VALUES ('13', 'Backgoroud', '7fe60578d824956f2211243ccaaee192.jpg', 'http://guinnessapp.dev/image/campaign?src=7fe60578d824956f2211243ccaaee192.jpg', 'http://guinnessapp.dev/image/campaign?src=thumb_7fe60578d824956f2211243ccaaee192.jpg', 'background_norepeat', '800', '520', 'image/jpeg', 'facebook', '#000000');
INSERT INTO `campaign_assets` VALUES ('14', 'Header', '6990b689287925de92b5f315a16b8317.jpg', 'http://guinnessapp.dev/image/campaign?src=6990b689287925de92b5f315a16b8317.jpg', 'http://guinnessapp.dev/image/campaign?src=thumb_6990b689287925de92b5f315a16b8317.jpg', 'banner_header', '320', '61', 'image/jpeg', 'mobile', null);
INSERT INTO `campaign_customer` VALUES ('615418145', '1', 'active');
INSERT INTO `campaign_customer` VALUES ('730189516', '2', 'active');
INSERT INTO `campaign_customer_fbauthorization` VALUES ('615418145', '282088055180043', '2012-03-07 09:11:07', null, '1', 'AAAEAjr5TCwsBAKisyMqUvhQVWZAq1j9FZBePDoTyeu9h2aUmXZCXsA9n4N9zXEwfciYrOBLreWOmFOZAAhBZBIZAPFb4Vgc6TtfFR9G4GOPQZDZD');
INSERT INTO `campaign_customer_fbauthorization` VALUES ('730189516', '282088055180043', '2012-03-08 01:21:28', null, '1', 'AAAEAjr5TCwsBAA7MLVpmZB0J0dAzQuuNyPdRs3T8MZAQ7ZCpSe2OGE9DgXZBh4H7XORwgl5E1ckAG6pU6bvRKEm2tqXe61NbJ9b35fzaRAZDZD');
INSERT INTO `campaign_customer_traction` VALUES ('1', 'Zoeldt', 'Zoel', 'zoeldt@yahoo.com', 'DASDASDASD', '123123', null, '1_282088055180043');
INSERT INTO `campaign_customer_traction` VALUES ('2', 'Khalid', 'Deh', 'kh411d@yahoo.com', 'asdf asdfasdf sdfasdfasdf asdf as', '414234234', null, '1_282088055180043');
INSERT INTO `campaign_group` VALUES ('1', 'Just another contest', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum. Curabitur tempus libero vel erat cursus et accumsan lorem euismod. Phasellus sit amet magna magna, eu vehicula quam. Aenean accumsan accumsan scelerisque. ', '2012-03-05 10:19:44', '2012-03-12 10:19:44', '2012-03-14 10:19:44', '2012-03-13 10:19:44', 'active', 'file', 'image', 'media_source=Upload Content Here&media_description=It\'s About', 'image/gif,image/jpeg,image/pjpeg,image/png', '<ol>\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n</ol>', null, null, null, null, '282088055180043', null, '1', '1', '1', '1', '1', '0');
INSERT INTO `campaign_group` VALUES ('2', 'Just another contest', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum. Curabitur tempus libero vel erat cursus et accumsan lorem euismod. Phasellus sit amet magna magna, eu vehicula quam. Aenean accumsan accumsan scelerisque. ', '2012-03-14 16:29:49', '2012-03-15 16:29:49', '2012-03-20 16:29:49', '2012-03-17 16:29:49', 'active', 'file', 'image', 'media_source=Upload Content Here&media_description=It\'s About', 'image/gif,image/jpeg,image/pjpeg,image/png', '<ol>\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n</ol>', null, null, null, null, '282088055180043', null, '1', '1', '1', '1', '1', '0');
INSERT INTO `campaign_group` VALUES ('3', 'Just another contest', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum. Curabitur tempus libero vel erat cursus et accumsan lorem euismod. Phasellus sit amet magna magna, eu vehicula quam. Aenean accumsan accumsan scelerisque. ', '2012-04-09 16:36:22', '2012-04-11 16:36:22', '2012-04-15 16:36:22', '2012-04-14 16:36:22', 'active', 'file', 'image', 'media_source=Upload Content Here&media_description=It\'s About', 'image/gif,image/jpeg,image/pjpeg,image/png', '<ol>\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n</ol>', null, null, null, null, '282088055180043', null, '1', '1', '1', '1', '1', '0');
INSERT INTO `campaign_group` VALUES ('4', 'Just another contest', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum. Curabitur tempus libero vel erat cursus et accumsan lorem euismod. Phasellus sit amet magna magna, eu vehicula quam. Aenean accumsan accumsan scelerisque. ', '2012-05-09 16:39:22', '2012-05-10 16:39:22', '2012-05-14 16:39:22', '2012-05-12 16:39:22', 'active', 'file', 'image', 'media_source=Upload Content Here&media_description=It\'s About', 'image/gif,image/jpeg,image/pjpeg,image/png', '<ol>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n</ol>', null, null, null, null, '282088055180043', null, '1', '1', '1', '1', '1', '0');
INSERT INTO `campaign_group` VALUES ('5', 'Just another contest', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum. Curabitur tempus libero vel erat cursus et accumsan lorem euismod. Phasellus sit amet magna magna, eu vehicula quam. Aenean accumsan accumsan scelerisque. ', '2012-06-09 16:42:48', '2012-06-12 16:42:48', '2012-06-19 16:42:48', '2012-06-14 16:42:48', 'active', 'file', 'image', 'media_source=Upload Content Here&media_description=It\'s About', 'image/gif,image/jpeg,image/pjpeg,image/png', '<ol>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n</ol>', null, null, null, null, '282088055180043', null, '1', '1', '1', '1', '1', '0');
INSERT INTO `campaign_group` VALUES ('6', 'Just another contest', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum. Curabitur tempus libero vel erat cursus et accumsan lorem euismod. Phasellus sit amet magna magna, eu vehicula quam. Aenean accumsan accumsan scelerisque. ', '2012-07-09 16:42:48', '2012-07-12 16:42:48', '2012-07-19 16:42:48', '2012-07-14 16:42:48', 'active', 'file', 'image', 'media_source=Upload Content Here&media_description=It\'s About', 'image/gif,image/jpeg,image/pjpeg,image/png', '<ol>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n\r\n\n</ol>', null, null, null, null, '282088055180043', null, '1', '1', '1', '1', '1', '0');
INSERT INTO `campaign_group` VALUES ('7', 'Just another contest', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum. Curabitur tempus libero vel erat cursus et accumsan lorem euismod. Phasellus sit amet magna magna, eu vehicula quam. Aenean accumsan accumsan scelerisque. ', '2012-09-09 16:53:25', '2012-09-13 16:53:25', '2012-09-20 16:53:25', '2012-09-15 16:53:25', 'active', 'file', 'image', 'media_source=Upload Content Here&media_description=It\'s About', 'image/gif,image/jpeg,image/pjpeg,image/png', '<ol>\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n</ol>', null, null, null, null, '282088055180043', null, '1', '1', '1', '1', '1', '0');
INSERT INTO `campaign_group` VALUES ('8', 'Just another contest', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum. Curabitur tempus libero vel erat cursus et accumsan lorem euismod. Phasellus sit amet magna magna, eu vehicula quam. Aenean accumsan accumsan scelerisque. ', '2012-11-09 16:54:32', '2012-11-15 16:54:32', '2012-11-20 16:54:32', '2012-11-18 16:54:32', 'active', 'file', 'image', 'media_source=Upload Content Here&media_description=It\'s About', 'image/gif,image/jpeg,image/pjpeg,image/png', '<ol>\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc a felis at nulla consectetur vulputate ac ut ipsum.</li>\r\n\n\r\n\n\r\n\n\r\n\n</ol>', null, null, null, null, '282088055180043', null, '1', '1', '1', '1', '1', '0');
INSERT INTO `campaign_group_assets` VALUES ('1', '9');
INSERT INTO `campaign_group_assets` VALUES ('1', '10');
INSERT INTO `campaign_group_assets` VALUES ('1', '11');
INSERT INTO `campaign_group_assets` VALUES ('1', '13');
INSERT INTO `campaign_group_assets` VALUES ('1', '14');
INSERT INTO `campaign_group_assets` VALUES ('2', '9');
INSERT INTO `campaign_group_assets` VALUES ('2', '10');
INSERT INTO `campaign_group_assets` VALUES ('2', '11');
INSERT INTO `campaign_group_assets` VALUES ('2', '13');
INSERT INTO `campaign_group_assets` VALUES ('2', '14');
INSERT INTO `campaign_group_assets` VALUES ('3', '9');
INSERT INTO `campaign_group_assets` VALUES ('3', '10');
INSERT INTO `campaign_group_assets` VALUES ('3', '11');
INSERT INTO `campaign_group_assets` VALUES ('3', '13');
INSERT INTO `campaign_group_assets` VALUES ('3', '14');
INSERT INTO `campaign_group_assets` VALUES ('7', '9');
INSERT INTO `campaign_group_assets` VALUES ('7', '10');
INSERT INTO `campaign_group_assets` VALUES ('7', '11');
INSERT INTO `campaign_group_assets` VALUES ('7', '13');
INSERT INTO `campaign_group_assets` VALUES ('7', '14');
INSERT INTO `campaign_media` VALUES ('1', null, 'RWERWERWERWER', 'image', 'file', 'http://guinnessapp.dev/image?gid=1&src=615418145_0c52158e8b0e2493f5dd5ebba9d6c643.jpg', 'http://guinnessapp.dev/image?gid=1&src=thumb_615418145_0c52158e8b0e2493f5dd5ebba9d6c643.jpg', 'http://guinnessapp.dev/image?gid=1&src=medium_615418145_0c52158e8b0e2493f5dd5ebba9d6c643.jpg', 'active', '1', '2012-03-07 21:11:47', '1331129507', '615418145_0c52158e8b0e2493f5dd5ebba9d6c643.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('2', null, 'Donec varius ante at lorem bibendum sed.\r\n\n', 'image', 'file', 'http://guinnessapp.dev/image?gid=1&src=730189516_5445c8453781647cc6f72a1afe1fe00c.jpg', 'http://guinnessapp.dev/image?gid=1&src=thumb_730189516_5445c8453781647cc6f72a1afe1fe00c.jpg', 'http://guinnessapp.dev/image?gid=1&src=medium_730189516_5445c8453781647cc6f72a1afe1fe00c.jpg', 'active', '1', '2012-03-08 13:29:15', '1331188155', '730189516_5445c8453781647cc6f72a1afe1fe00c.jpg', '0', '0');
INSERT INTO `campaign_media_owner` VALUES ('1', '615418145', '1');
INSERT INTO `campaign_media_owner` VALUES ('2', '730189516', '1');
INSERT INTO `campaign_page` VALUES ('1', '1', 'Outlet 2012 aja', 'Outlet 2012 ', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br />Integer vel neque et orci sollicitudin placerat non at dui.<br />Nulla at mi in risus viverra facilisis elementum quis sem.<br />Mauris vulputate augue dolor, sit amet rhoncus ante.<br /><br />Nunc iaculis mattis massa, eget congue risus suscipit a?<br />Nulla convallis metus porttitor lectus egestas pellentesque!<br />Phasellus eu tellus purus, ac volutpat libero.<br />Donec ultricies orci eget mi malesuada quis blandit sem rhoncus?<br /><br />Mauris eget dolor rhoncus mauris euismod ultrices et sed quam.<br />In pretium dui vitae justo tempor convallis.<br />Mauris aliquet vulputate nisl, sed porttitor erat interdum et.<br />Aenean nec lorem at lorem auctor molestie.<br />Proin tincidunt massa non odio pulvinar dictum.<br />Mauris vel justo diam, in dictum est.<br /><br /></p>', 'publish', '1', '0', '2012-03-07 16:18:48');
INSERT INTO `campaign_page` VALUES ('2', '1', 'Winner page', 'Winner Announce', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br />Integer vel neque et orci sollicitudin placerat non at dui.<br />Nulla at mi in risus viverra facilisis elementum quis sem.<br />Mauris vulputate augue dolor, sit amet rhoncus ante.<br /><br />Nunc iaculis mattis massa, eget congue risus suscipit a?<br />Nulla convallis metus porttitor lectus egestas pellentesque!<br />Phasellus eu tellus purus, ac volutpat libero.<br />Donec ultricies orci eget mi malesuada quis blandit sem rhoncus?<br /><br />Mauris eget dolor rhoncus mauris euismod ultrices et sed quam.<br />In pretium dui vitae justo tempor convallis.<br />Mauris aliquet vulputate nisl, sed porttitor erat interdum et.<br />Aenean nec lorem at lorem auctor molestie.<br />Proin tincidunt massa non odio pulvinar dictum.<br />Mauris vel justo diam, in dictum est.<br /><br /></p>', 'publish', '1', '0', '2012-03-07 16:28:41');
INSERT INTO `campaign_page` VALUES ('3', '1', 'Outlet 2012 aja', 'Terms n Condition', '<p>terms custom page</p>', 'publish', '1', '0', '2012-03-08 17:07:47');
INSERT INTO `campaign_page` VALUES ('4', '2', 'Terms and Condition', 'Terms n Condition', '<p>terms custom page</p>', 'publish', '1', '0', '2012-03-08 17:07:47');
INSERT INTO `campaign_page` VALUES ('5', '2', 'Winner page', 'Winner Announce', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br />Integer vel neque et orci sollicitudin placerat non at dui.<br />Nulla at mi in risus viverra facilisis elementum quis sem.<br />Mauris vulputate augue dolor, sit amet rhoncus ante.<br /><br />Nunc iaculis mattis massa, eget congue risus suscipit a?<br />Nulla convallis metus porttitor lectus egestas pellentesque!<br />Phasellus eu tellus purus, ac volutpat libero.<br />Donec ultricies orci eget mi malesuada quis blandit sem rhoncus?<br /><br />Mauris eget dolor rhoncus mauris euismod ultrices et sed quam.<br />In pretium dui vitae justo tempor convallis.<br />Mauris aliquet vulputate nisl, sed porttitor erat interdum et.<br />Aenean nec lorem at lorem auctor molestie.<br />Proin tincidunt massa non odio pulvinar dictum.<br />Mauris vel justo diam, in dictum est.<br /><br /></p>', 'publish', '1', '0', '2012-03-07 16:28:41');
INSERT INTO `campaign_page` VALUES ('6', '2', 'Outlet 2012 aja', 'Outlet 2012 ', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br />Integer vel neque et orci sollicitudin placerat non at dui.<br />Nulla at mi in risus viverra facilisis elementum quis sem.<br />Mauris vulputate augue dolor, sit amet rhoncus ante.<br /><br />Nunc iaculis mattis massa, eget congue risus suscipit a?<br />Nulla convallis metus porttitor lectus egestas pellentesque!<br />Phasellus eu tellus purus, ac volutpat libero.<br />Donec ultricies orci eget mi malesuada quis blandit sem rhoncus?<br /><br />Mauris eget dolor rhoncus mauris euismod ultrices et sed quam.<br />In pretium dui vitae justo tempor convallis.<br />Mauris aliquet vulputate nisl, sed porttitor erat interdum et.<br />Aenean nec lorem at lorem auctor molestie.<br />Proin tincidunt massa non odio pulvinar dictum.<br />Mauris vel justo diam, in dictum est.<br /><br /></p>', 'publish', '1', '0', '2012-03-07 16:18:48');
INSERT INTO `campaign_page` VALUES ('7', '7', 'Outlet 2012 aja', 'Terms n Condition', '<p>terms custom page</p>', 'publish', '1', '0', '2012-03-08 17:07:47');
INSERT INTO `campaign_page` VALUES ('8', '7', 'Winner page', 'Winner Announce', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br />Integer vel neque et orci sollicitudin placerat non at dui.<br />Nulla at mi in risus viverra facilisis elementum quis sem.<br />Mauris vulputate augue dolor, sit amet rhoncus ante.<br /><br />Nunc iaculis mattis massa, eget congue risus suscipit a?<br />Nulla convallis metus porttitor lectus egestas pellentesque!<br />Phasellus eu tellus purus, ac volutpat libero.<br />Donec ultricies orci eget mi malesuada quis blandit sem rhoncus?<br /><br />Mauris eget dolor rhoncus mauris euismod ultrices et sed quam.<br />In pretium dui vitae justo tempor convallis.<br />Mauris aliquet vulputate nisl, sed porttitor erat interdum et.<br />Aenean nec lorem at lorem auctor molestie.<br />Proin tincidunt massa non odio pulvinar dictum.<br />Mauris vel justo diam, in dictum est.<br /><br /></p>', 'publish', '1', '0', '2012-03-07 16:28:41');
INSERT INTO `campaign_page` VALUES ('9', '7', 'Outlet 2012 aja', 'Outlet 2012 ', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br />Integer vel neque et orci sollicitudin placerat non at dui.<br />Nulla at mi in risus viverra facilisis elementum quis sem.<br />Mauris vulputate augue dolor, sit amet rhoncus ante.<br /><br />Nunc iaculis mattis massa, eget congue risus suscipit a?<br />Nulla convallis metus porttitor lectus egestas pellentesque!<br />Phasellus eu tellus purus, ac volutpat libero.<br />Donec ultricies orci eget mi malesuada quis blandit sem rhoncus?<br /><br />Mauris eget dolor rhoncus mauris euismod ultrices et sed quam.<br />In pretium dui vitae justo tempor convallis.<br />Mauris aliquet vulputate nisl, sed porttitor erat interdum et.<br />Aenean nec lorem at lorem auctor molestie.<br />Proin tincidunt massa non odio pulvinar dictum.<br />Mauris vel justo diam, in dictum est.<br /><br /></p>', 'publish', '1', '0', '2012-03-07 16:18:48');
INSERT INTO `campaign_page` VALUES ('10', '8', 'Outlet 2012 aja', 'Outlet 2012 ', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br />Integer vel neque et orci sollicitudin placerat non at dui.<br />Nulla at mi in risus viverra facilisis elementum quis sem.<br />Mauris vulputate augue dolor, sit amet rhoncus ante.<br /><br />Nunc iaculis mattis massa, eget congue risus suscipit a?<br />Nulla convallis metus porttitor lectus egestas pellentesque!<br />Phasellus eu tellus purus, ac volutpat libero.<br />Donec ultricies orci eget mi malesuada quis blandit sem rhoncus?<br /><br />Mauris eget dolor rhoncus mauris euismod ultrices et sed quam.<br />In pretium dui vitae justo tempor convallis.<br />Mauris aliquet vulputate nisl, sed porttitor erat interdum et.<br />Aenean nec lorem at lorem auctor molestie.<br />Proin tincidunt massa non odio pulvinar dictum.<br />Mauris vel justo diam, in dictum est.<br /><br /></p>', 'publish', '1', '0', '2012-03-07 16:18:48');
INSERT INTO `campaign_page` VALUES ('11', '8', 'Winner page', 'Winner Announce', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br />Integer vel neque et orci sollicitudin placerat non at dui.<br />Nulla at mi in risus viverra facilisis elementum quis sem.<br />Mauris vulputate augue dolor, sit amet rhoncus ante.<br /><br />Nunc iaculis mattis massa, eget congue risus suscipit a?<br />Nulla convallis metus porttitor lectus egestas pellentesque!<br />Phasellus eu tellus purus, ac volutpat libero.<br />Donec ultricies orci eget mi malesuada quis blandit sem rhoncus?<br /><br />Mauris eget dolor rhoncus mauris euismod ultrices et sed quam.<br />In pretium dui vitae justo tempor convallis.<br />Mauris aliquet vulputate nisl, sed porttitor erat interdum et.<br />Aenean nec lorem at lorem auctor molestie.<br />Proin tincidunt massa non odio pulvinar dictum.<br />Mauris vel justo diam, in dictum est.<br /><br /></p>', 'publish', '1', '0', '2012-03-07 16:28:41');
INSERT INTO `campaign_page` VALUES ('12', '8', 'Outlet 2012 aja', 'Terms n Condition', '<p>terms custom page</p>', 'publish', '1', '0', '2012-03-08 17:07:47');
INSERT INTO `campaign_page` VALUES ('13', '1', 'hgjhgjhgjghjhg', 'jhgjghjgjh', '<p>sdjhfkjdhglshdglsd</p>\r\n\n<p>sdfgsdhfgjksdhfkghsdkjf</p>\r\n\n<p>sdfgsdfgdf</p>', 'publish', '1', '0', '2012-03-09 17:51:01');
INSERT INTO `campaign_page` VALUES ('14', '1', 'sdsdfsdfsdfsd', 'sdfsd', '<p>sdssdsdsdsd</p>', 'publish', '0', '1', '2012-03-09 17:53:18');
INSERT INTO `campaign_user` VALUES ('1', null, 'Admin', 'admin@demo.com', '76a2173be6393254e72ffa4d6df1030a', 'administrator', 'active', null, null, null);
INSERT INTO `campaign_user` VALUES ('8', null, 'Khalid', 'kh411d@yahoo.com', 'mencret', 'administrator', 'active', null, '2012-03-05 04:34:48', '1');
