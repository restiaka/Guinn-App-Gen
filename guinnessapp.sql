/*
MySQL Data Transfer
Source Host: localhost
Source Database: guinnessapp
Target Host: localhost
Target Database: guinnessapp
Date: 1/27/2012 4:29:06 PM
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for campaign_app
-- ----------------------------
CREATE TABLE `campaign_app` (
  `APP_APPLICATION_ID` bigint(100) NOT NULL,
  `APP_API_KEY` varchar(100) NOT NULL,
  `APP_SECRET_KEY` varchar(100) NOT NULL,
  `APP_CANVAS_PAGE` varchar(100) NOT NULL,
  `APP_CANVAS_URL` varchar(100) NOT NULL,
  `APP_APPLICATION_NAME` text NOT NULL,
  `APP_EXT_PERMISSIONS` text,
  `APP_FANPAGE` text,
  PRIMARY KEY (`APP_APPLICATION_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaign_customer
-- ----------------------------
CREATE TABLE `campaign_customer` (
  `uid` bigint(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `email_active` varchar(100) DEFAULT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'active',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `email` (`email`)
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
-- Table structure for campaign_fbcomment_data
-- ----------------------------
CREATE TABLE `campaign_fbcomment_data` (
  `media_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `comment_text` text,
  `comment_from` bigint(20) DEFAULT NULL,
  `comment_date` datetime DEFAULT NULL,
  PRIMARY KEY (`media_id`,`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaign_fblike_counter
-- ----------------------------
CREATE TABLE `campaign_fblike_counter` (
  `id` int(11) NOT NULL DEFAULT '0',
  `total` int(20) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaign_group
-- ----------------------------
CREATE TABLE `campaign_group` (
  `GID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `startdate` datetime NOT NULL,
  `upload_enddate` datetime NOT NULL,
  `enddate` datetime NOT NULL,
  `status` varchar(12) NOT NULL DEFAULT 'active',
  `code` varchar(255) DEFAULT NULL,
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
  PRIMARY KEY (`GID`),
  UNIQUE KEY `campaign_code` (`code`),
  UNIQUE KEY `APP_APPLICATION_ID` (`APP_APPLICATION_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

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
  `media_status` varchar(12) DEFAULT NULL,
  `GID` int(11) DEFAULT NULL,
  `media_uploaded_date` datetime DEFAULT NULL,
  `media_uploaded_timestamp` int(11) DEFAULT NULL,
  `media_basename` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`media_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaign_media_owner
-- ----------------------------
CREATE TABLE `campaign_media_owner` (
  `media_id` int(11) NOT NULL,
  `uid` bigint(11) NOT NULL,
  `GID` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaign_post
-- ----------------------------
CREATE TABLE `campaign_post` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_content` longtext NOT NULL,
  `post_excerpt` text,
  `post_category` varchar(100) NOT NULL,
  `post_submitted_date` datetime DEFAULT NULL,
  `post_publish_date` datetime NOT NULL,
  `post_status` varchar(100) NOT NULL DEFAULT 'active',
  `post_author` int(11) DEFAULT NULL,
  `GID` int(11) NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for campaign_setting
-- ----------------------------
CREATE TABLE `campaign_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `campaign_app` VALUES ('209681662395432', 'b805f7f9ad97c442eae2f29ee4705f1b', '6c29c3dc1ded207ac217cbe48deb29ef', 'http://apps.facebook.com/guinnidphotocontest/', 'http://fbguinnessphotocontest.think.web.id/', 'Guinness Photo Contest', 'publish_stream,email,offline_access', 'http://www.facebook.com/devthink?sk=app_209681662395432');
INSERT INTO `campaign_app` VALUES ('201876996523314', 'd3b6636c42294037d6b7b7eab6faeb5c', '364639819b4649d19c1b0533e96bdda9', 'http://apps.facebook.com/guinidcontesttwo/', 'http://fbguinnessphotocontest.think.web.id/', 'Guinness photo contest 2', 'publish_stream,email,offline_access', 'http://www.facebook.com/devthink?sk=app_201876996523314');
INSERT INTO `campaign_fblike_counter` VALUES ('10', '0');
INSERT INTO `campaign_fblike_counter` VALUES ('3', '2');
INSERT INTO `campaign_group` VALUES ('1', 'Testing Campaign', 'its a test', '2011-05-01 00:00:00', '2011-06-27 00:00:00', '2011-06-29 00:00:00', 'active', null, 'youtube', 'video', 'media_title=Media Title&media_source=Your Media Source', 'image/gif,image/jpeg,image/pjpeg,image/png', '', '', '', '', '', '201876996523314', 'gwsopphotocontest', '1', '1', '1');
INSERT INTO `campaign_group` VALUES ('3', 'Campaign Lagi Coba', 'Campaign Lagi Coba', '2011-05-10 00:00:00', '2011-06-30 16:10:46', '2011-09-13 00:00:00', 'active', null, 'file', 'image', 'media_title=Media Title&media_source=Your Media Source', 'image/gif,image/jpeg,image/pjpeg,image/png', '&lt;ul&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;/ul&gt;', '&lt;ul&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;/ul&gt;', '&lt;ul&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;/ul&gt;', '', '', '', 'gwsopphotocontest', '1', '1', '1');
INSERT INTO `campaign_media` VALUES ('1', 'asdfas', 'asdfasdf', 'video', 'youtube', 'http://www.youtube.com/v/B37wW9CGWyY', 'http://img.youtube.com/vi/B37wW9CGWyY/2.jpg', 'active', '1', '2011-05-12 05:50:38', '1305197438', 'B37wW9CGWyY');
INSERT INTO `campaign_media` VALUES ('2', 'Ini Image aja', 'asdfadf', 'image', 'file', 'http://fbguinnessphotocontest.think.web.id/image?src=730189516_4a089236413a09114548e6d061d78406.jpg&gid=1', 'http://fbguinnessphotocontest.think.web.id/image?src=thumb_730189516_4a089236413a09114548e6d061d78406.jpg&gid=1', 'active', '1', '2011-05-12 05:50:38', '1305197438', '730189516_4a089236413a09114548e6d061d78406.jpg');
INSERT INTO `campaign_media` VALUES ('3', 'asdf', 'asdfasdf', 'video', 'youtube', 'http://www.youtube.com/v/QeWNvvmwDwg', 'http://img.youtube.com/vi/QeWNvvmwDwg/2.jpg', 'active', '1', '2011-05-24 07:22:14', '1306239734', null);
INSERT INTO `campaign_media` VALUES ('4', 'asdfasdf', 'asdgasdg', 'video', 'facebook', 'http://www.facebook.com/v/748516185403', 'https://graph.facebook.com/748516185403/picture', 'active', '1', '2011-05-24 07:24:31', '1306239871', null);
INSERT INTO `campaign_media` VALUES ('8', null, null, 'image', 'file', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=730189516_2e306cffdb3a631d1c38748d823b3530.jpg', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=thumb_730189516_2e306cffdb3a631d1c38748d823b3530.jpg', 'active', '3', '2011-05-24 08:18:05', '1306243085', '730189516_2e306cffdb3a631d1c38748d823b3530.jpg');
INSERT INTO `campaign_media` VALUES ('9', null, null, 'image', 'file', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=730189516_044e62832dd9398001e5f3daa4c3b90b.jpg', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=thumb_730189516_044e62832dd9398001e5f3daa4c3b90b.jpg', 'active', '3', '2011-06-08 03:15:55', '1307520955', '730189516_044e62832dd9398001e5f3daa4c3b90b.jpg');
INSERT INTO `campaign_media` VALUES ('10', null, null, 'image', 'file', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=730189516_62dd7bf12d385b9442f437ae9271e0b2.jpg', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=thumb_730189516_62dd7bf12d385b9442f437ae9271e0b2.jpg', 'active', '3', '2011-06-08 15:20:01', '1307521201', '730189516_62dd7bf12d385b9442f437ae9271e0b2.jpg');
INSERT INTO `campaign_media` VALUES ('11', null, null, 'image', 'file', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=730189516_bce1cecf32d9d730435f32411f3c8d4f.jpg', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=thumb_730189516_bce1cecf32d9d730435f32411f3c8d4f.jpg', 'active', '3', '2011-06-08 15:21:12', '1307521272', '730189516_bce1cecf32d9d730435f32411f3c8d4f.jpg');
INSERT INTO `campaign_media` VALUES ('12', null, null, 'image', 'file', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=730189516_27fd2c6e7f855c7bc86ee487d9591dce.jpg', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=thumb_730189516_27fd2c6e7f855c7bc86ee487d9591dce.jpg', 'active', '3', '2011-06-08 15:21:42', '1307521302', '730189516_27fd2c6e7f855c7bc86ee487d9591dce.jpg');
INSERT INTO `campaign_media` VALUES ('13', 'iugiugiuguigiuguig ugiuguig uiguig', null, 'image', 'file', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=730189516_605c28d5c7ea83c31e7ae28c3dde6d62.jpg', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=thumb_730189516_605c28d5c7ea83c31e7ae28c3dde6d62.jpg', 'inactive', '3', '2011-06-16 19:38:16', '1308227896', '730189516_605c28d5c7ea83c31e7ae28c3dde6d62.jpg');
INSERT INTO `campaign_media` VALUES ('14', 'klknlk kljklj lkjlkjkljlkj', null, 'image', 'file', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=730189516_985482e463bdd6929dfe5a28e67ce686.jpg', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=thumb_730189516_985482e463bdd6929dfe5a28e67ce686.jpg', 'inactive', '3', '2011-06-16 19:41:01', '1308228061', '730189516_985482e463bdd6929dfe5a28e67ce686.jpg');
INSERT INTO `campaign_media` VALUES ('15', 'Wowowow', null, 'video', 'youtube', 'http://www.youtube.com/v/CeaBimiUKlM', 'http://img.youtube.com/vi/CeaBimiUKlM/2.jpg', 'active', '1', '2011-06-22 15:02:06', '1308729726', null);
INSERT INTO `campaign_media` VALUES ('16', 'apa aya', null, 'video', 'youtube', 'http://www.youtube.com/v/g2oTuZPlfiM', 'http://img.youtube.com/vi/g2oTuZPlfiM/2.jpg', 'active', '1', '2011-06-22 16:03:44', '1308733424', null);
INSERT INTO `campaign_media` VALUES ('17', 'sdfsdfsd', null, 'video', 'youtube', 'http://www.youtube.com/v/Lc6U7_-BeGc', 'http://img.youtube.com/vi/Lc6U7_-BeGc/2.jpg', 'inactive', '1', '2011-06-22 16:16:02', '1308734162', null);
INSERT INTO `campaign_media` VALUES ('18', 'hrthrjrtjrjrtjrt', null, 'video', 'youtube', 'http://www.youtube.com/v/sfOlH4LOxFw', 'http://img.youtube.com/vi/sfOlH4LOxFw/2.jpg', 'inactive', '1', '2011-06-22 16:16:50', '1308734210', null);
INSERT INTO `campaign_media` VALUES ('19', 'sdgsdgsdghsdhsdh', null, 'video', 'youtube', 'http://www.youtube.com/v/47NNJeHuXgs', 'http://img.youtube.com/vi/47NNJeHuXgs/2.jpg', 'inactive', '1', '2011-06-22 16:50:34', '1308736234', null);
INSERT INTO `campaign_media` VALUES ('20', 'asdfasfasdf', null, 'video', 'youtube', 'http://www.youtube.com/v/47NNJeHuXgs', 'http://img.youtube.com/vi/47NNJeHuXgs/2.jpg', 'inactive', '1', '2011-06-22 16:51:36', '1308736296', null);
INSERT INTO `campaign_media` VALUES ('21', 'sdfsdfsdfsdf', null, 'video', 'youtube', 'http://www.youtube.com/v/AId-ZLFTbTQ', 'http://img.youtube.com/vi/AId-ZLFTbTQ/2.jpg', 'inactive', '1', '2011-06-22 16:56:16', '1308736576', null);
INSERT INTO `campaign_media_owner` VALUES ('1', '730189516', '1');
INSERT INTO `campaign_media_owner` VALUES ('2', '82948234', '1');
INSERT INTO `campaign_media_owner` VALUES ('3', '730189516', '1');
INSERT INTO `campaign_media_owner` VALUES ('4', '730189516', '1');
INSERT INTO `campaign_media_owner` VALUES ('5', '730189516', '3');
INSERT INTO `campaign_media_owner` VALUES ('6', '730189516', '3');
INSERT INTO `campaign_media_owner` VALUES ('8', '730189516', '3');
INSERT INTO `campaign_media_owner` VALUES ('9', '730189516', '3');
INSERT INTO `campaign_media_owner` VALUES ('10', '730189516', '3');
INSERT INTO `campaign_media_owner` VALUES ('11', '730189516', '3');
INSERT INTO `campaign_media_owner` VALUES ('12', '730189516', '3');
INSERT INTO `campaign_media_owner` VALUES ('13', '730189516', '3');
INSERT INTO `campaign_media_owner` VALUES ('14', '730189516', '3');
INSERT INTO `campaign_media_owner` VALUES ('15', '730189516', '1');
INSERT INTO `campaign_media_owner` VALUES ('16', '100000413942502', '1');
INSERT INTO `campaign_media_owner` VALUES ('17', '100000413942502', '1');
INSERT INTO `campaign_media_owner` VALUES ('18', '100000413942502', '1');
INSERT INTO `campaign_media_owner` VALUES ('20', '100000413942502', '1');
INSERT INTO `campaign_media_owner` VALUES ('21', '100000413942502', '1');
INSERT INTO `campaign_setting` VALUES ('23', 'SITE_URL_SSL', 'https://guinnessapp.dev/', 'SSL Base App Site URL');
INSERT INTO `campaign_setting` VALUES ('9', 'SITE_URL', 'http://guinnessapp.dev/', 'Base App Site URL');
INSERT INTO `campaign_setting` VALUES ('17', 'TRAC_USERID', 'fbdev', 'Traction API UserID');
INSERT INTO `campaign_setting` VALUES ('18', 'TRAC_PASSWORD', 'th1nkw3b', 'Traction API Password');
INSERT INTO `campaign_setting` VALUES ('19', 'TRAC_ENDPOINTID', '17259', 'Traction API EndpointID');
INSERT INTO `campaign_setting` VALUES ('20', 'TRAC_ATTR_FBUID', '3012669', 'Traction Custom Attribute ID for user Facebook ID');
INSERT INTO `campaign_setting` VALUES ('21', 'TRAC_ATTR_GID', '3014098', 'Traction Custom Attribute for GID');
INSERT INTO `campaign_user` VALUES ('1', null, 'Admin', 'admin@demo.com', '76a2173be6393254e72ffa4d6df1030a', 'administrator', 'active', null, null, null);
