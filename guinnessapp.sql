/*
MySQL Data Transfer
Source Host: localhost
Source Database: guinnessapp
Target Host: localhost
Target Database: guinnessapp
Date: 2/24/2012 10:01:21 PM
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
-- Table structure for campaign_group
-- ----------------------------
CREATE TABLE `campaign_group` (
  `GID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
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
  `media_has_vote` tinyint(4) NOT NULL DEFAULT '0',
  `image_header` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`GID`),
  UNIQUE KEY `campaign_code` (`code`),
  UNIQUE KEY `APP_APPLICATION_ID` (`APP_APPLICATION_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

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
INSERT INTO `campaign_app` VALUES ('209681662395432', 'b805f7f9ad97c442eae2f29ee4705f1b', '6c29c3dc1ded207ac217cbe48deb29ef', 'http://apps.facebook.com/guinnidphotocontest/', 'http://fbguinnessphotocontest.think.web.id/', 'Guinness Photo Contest', 'publish_stream,email,offline_access', 'http://www.facebook.com/guinnessindonesia', '0');
INSERT INTO `campaign_app` VALUES ('201876996523314', '201876996523314', '364639819b4649d19c1b0533e96bdda9', 'http://apps.facebook.com/guinidcontesttwo/', 'http://guinnessapp.dev/', 'Guinness photo contest 2', 'publish_stream,email', 'http://www.facebook.com/guinnessindonesia', '0');
INSERT INTO `campaign_app` VALUES ('282088055180043', '282088055180043', 'a00334433dd5a7e5acd5f86f7c12928a', 'http://apps.facebook.com/guinnidgentestone/', 'http://guinnessapp.dev/campaign/canvas/282088055180043/', 'Guinness App Dev', 'publish_stream,email,user_birthday,user_hometown,user_interests,user_likes', 'http://www.facebook.com/guinnessindonesia', '1');
INSERT INTO `campaign_customer` VALUES ('730189516', '74881399', 'active');
INSERT INTO `campaign_customer` VALUES ('811922327', '98123136', 'active');
INSERT INTO `campaign_customer_fbauthorization` VALUES ('730189516', '282088055180043', '2012-02-21 09:07:34', null, '1', 'AAAEAjr5TCwsBALZBy18E0TZAqUDCa5rtDjXnYMbryraxfOKTZAcH60I1CCV7LPqmJkRsrAkGjz78GIlcwRB34BzY7NxuLIrGa5PCjfZAWwZDZD');
INSERT INTO `campaign_customer_fbauthorization` VALUES ('730189516', '201876996523314', '2012-02-06 01:11:19', null, '1', 'AAAC3myaqhTIBAGwIdMvCUw5NlJKynMDHU5rsHLccBh5OYDWZAVAJP1JQ0g5SrwVbLf0F068HF2LZCFseYr9Et5LKLElLKsJTjfHZC2zdgZDZD');
INSERT INTO `campaign_customer_fbauthorization` VALUES ('811922327', '282088055180043', '2012-02-22 07:18:40', null, '1', 'AAAEAjr5TCwsBAJIlqEnp9845A6LzIfODZAQ49JZB0wsUTXIzVeSU1EGz0gb3XcvkMREsdyBSZAsG3izTxRf8kBjyuVqnZAhyVKiA307dPQZDZD');
INSERT INTO `campaign_group` VALUES ('1', 'Testing Campaign', 'its a test', '2012-02-01 00:00:00', '2012-06-27 00:00:00', '2012-06-29 00:00:00', 'active', null, 'youtube', 'video', 'media_source=Upload Content Here&media_description=It\'s About', 'image/gif,image/jpeg,image/pjpeg,image/png', 'No Rules defined yeah', '', 'No Policy defined yeah', '', '', '201876996523314', 'gwsopphotocontest', '1', '1', '1', '0', 'c56b26c68601d05f8788b15d6c4e99c8.jpg');
INSERT INTO `campaign_group` VALUES ('3', 'Campaign Lagi Coba', 'Campaign Lagi Coba', '2011-05-10 00:00:00', '2011-06-30 16:10:46', '2011-09-13 00:00:00', 'active', null, 'file', 'image', 'media_source=Upload Content Here&media_description=About', 'image/gif,image/jpeg,image/pjpeg,image/png', '&lt;ul&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;/ul&gt;', '&lt;ul&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;/ul&gt;', '&lt;ul&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;li&gt;asdfasdfasdfasdfasfasdfasdf&lt;/li&gt;\n&lt;/ul&gt;', '', '', '', 'gwsopphotocontest', '1', '1', '1', '0', null);
INSERT INTO `campaign_group` VALUES ('10', 'Campaign app new dev', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sit amet lorem orci, id pretium augue. Pellentesque vitae justo est. In hac habitasse platea dictumst. Quisque sagittis viverra sapien non tincidunt. Morbi id massa tellus. Proin in risus at enim rutrum posuere.', '2012-01-30 00:00:00', '2012-02-25 00:00:00', '2012-02-27 00:00:00', 'active', null, 'file', 'image', 'media_source=Upload Content Here&media_description=It\'s About', 'image/gif,image/jpeg,image/pjpeg,image/png', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sit amet lorem orci, id pretium augue. Pellentesque vitae justo est. In hac habitasse platea dictumst. Quisque sagittis viverra sapien non tincidunt. Morbi id massa tellus. Proin in risus at enim rutrum posuere. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sit amet lorem orci, id pretium augue. Pellentesque vitae justo est. In hac habitasse platea dictumst. Quisque sagittis viverra sapien non tincidunt. Morbi id massa tellus. Proin in risus at enim rutrum posuere.', null, '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sit amet lorem orci, id pretium augue. Pellentesque vitae justo est. In hac habitasse platea dictumst. Quisque sagittis viverra sapien non tincidunt. Morbi id massa tellus. Proin in risus at enim rutrum posuere.\r\n\n\r\n\n', null, '282088055180043', null, '1', '1', '0', '1', '35bdfa45dee1f1ea34258fbf9623e34f.jpg');
INSERT INTO `campaign_media` VALUES ('1', 'asdfas', 'asdfasdf', 'video', 'youtube', 'http://www.youtube.com/v/B37wW9CGWyY', 'http://img.youtube.com/vi/B37wW9CGWyY/2.jpg', null, 'active', '1', '2011-05-12 05:50:38', '1305197438', 'B37wW9CGWyY', '0', '0');
INSERT INTO `campaign_media` VALUES ('2', 'Ini Image aja', 'asdfadf', 'image', 'file', 'http://fbguinnessphotocontest.think.web.id/image?src=730189516_4a089236413a09114548e6d061d78406.jpg&gid=1', 'http://fbguinnessphotocontest.think.web.id/image?src=thumb_730189516_4a089236413a09114548e6d061d78406.jpg&gid=1', null, 'active', '1', '2011-05-12 05:50:38', '1305197438', '730189516_4a089236413a09114548e6d061d78406.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('3', 'asdf', 'asdfasdf', 'video', 'youtube', 'http://www.youtube.com/v/QeWNvvmwDwg', 'http://img.youtube.com/vi/QeWNvvmwDwg/2.jpg', null, 'banned', '1', '2011-05-24 07:22:14', '1306239734', null, '0', '0');
INSERT INTO `campaign_media` VALUES ('4', 'asdfasdf', 'asdgasdg', 'video', 'facebook', 'http://www.facebook.com/v/748516185403', 'https://graph.facebook.com/748516185403/picture', null, 'active', '1', '2011-05-24 07:24:31', '1306239871', null, '0', '0');
INSERT INTO `campaign_media` VALUES ('8', null, null, 'image', 'file', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=730189516_2e306cffdb3a631d1c38748d823b3530.jpg', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=thumb_730189516_2e306cffdb3a631d1c38748d823b3530.jpg', null, 'banned', '3', '2011-05-24 08:18:05', '1306243085', '730189516_2e306cffdb3a631d1c38748d823b3530.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('9', null, null, 'image', 'file', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=730189516_044e62832dd9398001e5f3daa4c3b90b.jpg', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=thumb_730189516_044e62832dd9398001e5f3daa4c3b90b.jpg', null, 'active', '3', '2011-06-08 03:15:55', '1307520955', '730189516_044e62832dd9398001e5f3daa4c3b90b.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('10', null, null, 'image', 'file', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=730189516_62dd7bf12d385b9442f437ae9271e0b2.jpg', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=thumb_730189516_62dd7bf12d385b9442f437ae9271e0b2.jpg', null, 'active', '3', '2011-06-08 15:20:01', '1307521201', '730189516_62dd7bf12d385b9442f437ae9271e0b2.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('11', null, null, 'image', 'file', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=730189516_bce1cecf32d9d730435f32411f3c8d4f.jpg', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=thumb_730189516_bce1cecf32d9d730435f32411f3c8d4f.jpg', null, 'active', '3', '2011-06-08 15:21:12', '1307521272', '730189516_bce1cecf32d9d730435f32411f3c8d4f.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('12', null, null, 'image', 'file', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=730189516_27fd2c6e7f855c7bc86ee487d9591dce.jpg', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=thumb_730189516_27fd2c6e7f855c7bc86ee487d9591dce.jpg', null, 'banned', '3', '2011-06-08 15:21:42', '1307521302', '730189516_27fd2c6e7f855c7bc86ee487d9591dce.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('13', 'iugiugiuguigiuguig ugiuguig uiguig', null, 'image', 'file', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=730189516_605c28d5c7ea83c31e7ae28c3dde6d62.jpg', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=thumb_730189516_605c28d5c7ea83c31e7ae28c3dde6d62.jpg', null, 'pending', '3', '2011-06-16 19:38:16', '1308227896', '730189516_605c28d5c7ea83c31e7ae28c3dde6d62.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('14', 'klknlk kljklj lkjlkjkljlkj', null, 'image', 'file', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=730189516_985482e463bdd6929dfe5a28e67ce686.jpg', 'http://fbguinnessphotocontest.think.web.id/image?gid=3&src=thumb_730189516_985482e463bdd6929dfe5a28e67ce686.jpg', null, 'pending', '3', '2011-06-16 19:41:01', '1308228061', '730189516_985482e463bdd6929dfe5a28e67ce686.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('15', 'Wowowow', null, 'video', 'youtube', 'http://www.youtube.com/v/CeaBimiUKlM', 'http://img.youtube.com/vi/CeaBimiUKlM/2.jpg', null, 'active', '1', '2011-06-22 15:02:06', '1308729726', null, '0', '0');
INSERT INTO `campaign_media` VALUES ('16', 'apa aya', null, 'video', 'youtube', 'http://www.youtube.com/v/g2oTuZPlfiM', 'http://img.youtube.com/vi/g2oTuZPlfiM/2.jpg', null, 'active', '1', '2011-06-22 16:03:44', '1308733424', null, '0', '0');
INSERT INTO `campaign_media` VALUES ('17', 'sdfsdfsd', null, 'video', 'youtube', 'http://www.youtube.com/v/Lc6U7_-BeGc', 'http://img.youtube.com/vi/Lc6U7_-BeGc/2.jpg', null, 'pending', '1', '2011-06-22 16:16:02', '1308734162', null, '0', '0');
INSERT INTO `campaign_media` VALUES ('18', 'hrthrjrtjrjrtjrt', null, 'video', 'youtube', 'http://www.youtube.com/v/sfOlH4LOxFw', 'http://img.youtube.com/vi/sfOlH4LOxFw/2.jpg', null, 'pending', '1', '2011-06-22 16:16:50', '1308734210', null, '0', '0');
INSERT INTO `campaign_media` VALUES ('19', 'sdgsdgsdghsdhsdh', null, 'video', 'youtube', 'http://www.youtube.com/v/47NNJeHuXgs', 'http://img.youtube.com/vi/47NNJeHuXgs/2.jpg', null, 'pending', '1', '2011-06-22 16:50:34', '1308736234', null, '0', '0');
INSERT INTO `campaign_media` VALUES ('20', 'asdfasfasdf', null, 'video', 'youtube', 'http://www.youtube.com/v/47NNJeHuXgs', 'http://img.youtube.com/vi/47NNJeHuXgs/2.jpg', null, 'pending', '1', '2011-06-22 16:51:36', '1308736296', null, '0', '0');
INSERT INTO `campaign_media` VALUES ('21', 'sdfsdfsdfsdf', null, 'video', 'youtube', 'http://www.youtube.com/v/AId-ZLFTbTQ', 'http://img.youtube.com/vi/AId-ZLFTbTQ/2.jpg', null, 'pending', '1', '2011-06-22 16:56:16', '1308736576', null, '0', '0');
INSERT INTO `campaign_media` VALUES ('22', 'jgjjjgj', null, 'image', 'file', 'http://guinnessapp.dev/campaign/canvas/282088055180043/image?gid=10&src=730189516_456956d7372030de48447d4672d4bb98.jpg', 'http://guinnessapp.dev/image?gid=10&src=thumb_730189516_456956d7372030de48447d4672d4bb98.jpg', null, 'active', '10', '2012-01-31 14:34:56', '1327995296', '730189516_456956d7372030de48447d4672d4bb98.jpg', '1', '0');
INSERT INTO `campaign_media` VALUES ('23', null, 'Nullam eu risus nunc. Proin blandit sed.\r\n\n', 'image', 'file', 'http://guinnessapp.dev/campaign/canvas/282088055180043/image?gid=10&src=730189516_72eccd764a1d7f0c73012000eb53dfe6.jpg', 'http://guinnessapp.dev/image?gid=10&src=thumb_730189516_72eccd764a1d7f0c73012000eb53dfe6.jpg', null, 'active', '10', '2012-02-03 15:39:16', '1328258356', '730189516_72eccd764a1d7f0c73012000eb53dfe6.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('24', null, 'aasdfasdfasdf', 'image', 'file', 'http://guinnessapp.dev/campaign/canvas/282088055180043/image?gid=10&src=730189516_70f0771fcf5b765df0f0cdee8406d320.jpg', 'http://guinnessapp.dev/image?gid=10&src=thumb_730189516_70f0771fcf5b765df0f0cdee8406d320.jpg', null, 'active', '10', '2012-02-03 15:39:44', '1328258384', '730189516_70f0771fcf5b765df0f0cdee8406d320.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('25', null, 'dgdfgdfgdfgdf', 'image', 'file', 'http://guinnessapp.dev/campaign/canvas/282088055180043/image?gid=10&src=730189516_c064afb74a51326db2468f068933f38f.jpg', 'http://guinnessapp.dev/image?gid=10&src=thumb_730189516_c064afb74a51326db2468f068933f38f.jpg', null, 'active', '10', '2012-02-03 15:40:01', '1328258401', '730189516_c064afb74a51326db2468f068933f38f.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('26', null, 'Praesent pretium, nisl in facilisis sed.\r\n\n', 'image', 'file', 'http://guinnessapp.dev/campaign/canvas/282088055180043/image?gid=10&src=730189516_844e9c130b814f1a08cb5755c474a5c9.jpg', 'http://guinnessapp.dev/image?gid=10&src=thumb_730189516_844e9c130b814f1a08cb5755c474a5c9.jpg', null, 'active', '10', '2012-02-03 15:40:24', '1328258424', '730189516_844e9c130b814f1a08cb5755c474a5c9.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('27', null, 'Donec hendrerit ipsum et libero posuere.\r\n\n', 'image', 'file', 'http://guinnessapp.dev/campaign/canvas/282088055180043/image?gid=10&src=730189516_c5613deac06c4c5356561d51ff45d675.jpg', 'http://guinnessapp.dev/image?gid=10&src=thumb_730189516_c5613deac06c4c5356561d51ff45d675.jpg', null, 'active', '10', '2012-02-03 15:40:45', '1328258445', '730189516_c5613deac06c4c5356561d51ff45d675.jpg', '0', '1');
INSERT INTO `campaign_media` VALUES ('28', null, 'Donec adipiscing; quam id semper nullam.\r\n\n', 'image', 'file', 'http://guinnessapp.dev/campaign/canvas/282088055180043/image?gid=10&src=730189516_4bad300585828687f3328e78c9b8de7c.jpg', 'http://guinnessapp.dev/image?gid=10&src=thumb_730189516_4bad300585828687f3328e78c9b8de7c.jpg', null, 'active', '10', '2012-02-03 15:41:11', '1328258471', '730189516_4bad300585828687f3328e78c9b8de7c.jpg', '0', '1');
INSERT INTO `campaign_media` VALUES ('29', null, 'Nunc rhoncus, tellus a dignissim nullam.\r\n\n', 'image', 'file', 'http://guinnessapp.dev/campaign/canvas/282088055180043/image?gid=10&src=730189516_73d2ff3e2be7060d49d6c58f7cfd63a9.jpg', 'http://guinnessapp.dev/image?gid=10&src=thumb_730189516_73d2ff3e2be7060d49d6c58f7cfd63a9.jpg', null, 'active', '10', '2012-02-03 15:41:42', '1328258502', '730189516_73d2ff3e2be7060d49d6c58f7cfd63a9.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('30', null, 'Donec ut lacus at sem sollicitudin amet.\r\n\n', 'image', 'file', 'http://guinnessapp.dev/campaign/canvas/282088055180043/image?gid=10&src=730189516_772fb8b55ebfee0e55c9b951d67709d0.jpg', 'http://guinnessapp.dev/image?gid=10&src=thumb_730189516_772fb8b55ebfee0e55c9b951d67709d0.jpg', null, 'active', '10', '2012-02-03 15:42:14', '1328258534', '730189516_772fb8b55ebfee0e55c9b951d67709d0.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('31', null, 'Donec et urna quam. Vestibulum volutpat.\r\n\n', 'image', 'file', 'http://guinnessapp.dev/campaign/canvas/282088055180043/image?gid=10&src=730189516_ff33a139dbb49bbb41058a6e99d9456b.jpg', 'http://guinnessapp.dev/image?gid=10&src=thumb_730189516_ff33a139dbb49bbb41058a6e99d9456b.jpg', null, 'active', '10', '2012-02-03 15:42:43', '1328258563', '730189516_ff33a139dbb49bbb41058a6e99d9456b.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('32', null, 'Guinness pint de hoya', 'video', 'youtube', 'http://www.youtube.com/v/d15lJn1r0Mk', 'http://img.youtube.com/vi/d15lJn1r0Mk/2.jpg', null, 'active', '1', '2012-02-06 13:20:18', '1328509218', null, '0', '0');
INSERT INTO `campaign_media` VALUES ('33', null, 'test lagi', 'video', 'youtube', 'http://www.youtube.com/v/-pgA8Z7lFVE', 'http://img.youtube.com/vi/-pgA8Z7lFVE/2.jpg', null, 'pending', '1', '2012-02-06 19:43:26', '1328532206', null, '0', '0');
INSERT INTO `campaign_media` VALUES ('34', null, 'test', 'image', 'file', 'https://guinnessapp.dev/image?gid=10&src=730189516_d40b2eee2e7c9ebd535543ae673c88b2.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_730189516_d40b2eee2e7c9ebd535543ae673c88b2.jpg', null, 'active', '10', '2012-02-06 19:47:06', '1328532426', '730189516_d40b2eee2e7c9ebd535543ae673c88b2.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('35', null, 'Fusce mauris ligula, bibendum eget amet.\r\n\n', 'image', 'file', 'https://guinnessapp.dev/image?gid=10&src=730189516_202cbf5127609db01b4b491cc15ad41f.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_730189516_202cbf5127609db01b4b491cc15ad41f.jpg', null, 'active', '10', '2012-02-06 19:50:22', '1328532622', '730189516_202cbf5127609db01b4b491cc15ad41f.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('36', null, 'Praesent sagittis diam in orci volutpat.\r\n\n', 'image', 'file', 'https://guinnessapp.dev/image?gid=10&src=730189516_b535b52ccbb8d99c07279a39569c2e3b.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_730189516_b535b52ccbb8d99c07279a39569c2e3b.jpg', null, 'active', '10', '2012-02-06 19:51:21', '1328532681', '730189516_b535b52ccbb8d99c07279a39569c2e3b.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('37', null, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sit amet lorem orci, id pretium augue. Pellentesque vitae justo est. In hac', 'image', 'file', 'https://guinnessapp.dev/image?gid=10&src=730189516_6f66f92efa228b6fceb4fc0513e61a84.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_730189516_6f66f92efa228b6fceb4fc0513e61a84.jpg', null, 'pending', '10', '2012-02-21 20:22:45', '1329830565', '730189516_6f66f92efa228b6fceb4fc0513e61a84.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('38', null, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sit amet lorem orci, id pretium augue. Pellentesque vitae justo est. In hac', 'image', 'file', 'https://guinnessapp.dev/image?gid=10&src=730189516_481dfe3696f911ad9c340369e83978be.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_730189516_481dfe3696f911ad9c340369e83978be.jpg', null, 'pending', '10', '2012-02-21 20:28:44', '1329830924', '730189516_481dfe3696f911ad9c340369e83978be.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('39', null, 'pattimura hgfhgfhgfhg hghgfhgfhgf hgf', 'image', 'file', 'http://guinnessapp.dev/image?gid=10&src=730189516_2a9b4f06a59d785ad7978e9a2127dc8a.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_730189516_2a9b4f06a59d785ad7978e9a2127dc8a.jpg', null, 'pending', '10', '2012-02-22 18:24:25', '1329909865', '730189516_2a9b4f06a59d785ad7978e9a2127dc8a.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('40', null, 'Arthur', 'image', 'file', 'http://guinnessapp.dev/image?gid=10&src=811922327_5d3933f75368aa8bd1be642fea201a57.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_811922327_5d3933f75368aa8bd1be642fea201a57.jpg', null, 'active', '10', '2012-02-22 19:19:44', '1329913184', '811922327_5d3933f75368aa8bd1be642fea201a57.jpg', '0', '3');
INSERT INTO `campaign_media` VALUES ('41', null, 'Lorem ipsum sja dehad aasdfser sers sdfs s dfs dfs ef sef s efsefsf', 'image', 'file', 'http://guinnessapp.dev/image?gid=10&src=730189516_f540a3e69c679466eb20ec6ef06e4c74.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_730189516_f540a3e69c679466eb20ec6ef06e4c74.jpg', null, 'pending', '10', '2012-02-23 10:45:35', '1329968735', '730189516_f540a3e69c679466eb20ec6ef06e4c74.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('42', null, 'lorem ipsum del', 'image', 'file', 'http://guinnessapp.dev/image?gid=10&src=730189516_2b81ce505bde7dec3279f0985b6cec3a.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_730189516_2b81ce505bde7dec3279f0985b6cec3a.jpg', null, 'pending', '10', '2012-02-23 10:51:43', '1329969103', '730189516_2b81ce505bde7dec3279f0985b6cec3a.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('43', null, 'Lorem ipsum dolor sit ames watauuuuu', 'image', 'file', 'http://guinnessapp.dev/image?gid=10&src=730189516_4f377e4b7e0e4dd1af8496f965fae50c.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_730189516_4f377e4b7e0e4dd1af8496f965fae50c.jpg', null, 'pending', '10', '2012-02-23 11:50:35', '1329972635', '730189516_4f377e4b7e0e4dd1af8496f965fae50c.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('44', null, 'lorem ipsum bla bla', 'image', 'file', 'http://guinnessapp.dev/image?gid=10&src=730189516_1df08afb0962d849632103a355f709ff.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_730189516_1df08afb0962d849632103a355f709ff.jpg', null, 'pending', '10', '2012-02-23 11:52:49', '1329972769', '730189516_1df08afb0962d849632103a355f709ff.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('45', null, 'asdfasdgasdgs asdga', 'image', 'file', 'http://guinnessapp.dev/image?gid=10&src=730189516_9df0256684e0fdb64479f844f549a12d.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_730189516_9df0256684e0fdb64479f844f549a12d.jpg', null, 'pending', '10', '2012-02-23 11:54:44', '1329972884', '730189516_9df0256684e0fdb64479f844f549a12d.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('46', null, 'lorem ipsum ababa ababasbab asbasb asbas', 'image', 'file', 'http://guinnessapp.dev/image?gid=10&src=730189516_517b1b587c5fea6279b654055c0045b5.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_730189516_517b1b587c5fea6279b654055c0045b5.jpg', null, 'pending', '10', '2012-02-23 11:56:05', '1329972965', '730189516_517b1b587c5fea6279b654055c0045b5.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('47', null, 'asdf asdf asd asdf asdfasdf saf asdfasdf', 'image', 'file', 'http://guinnessapp.dev/image?gid=10&src=730189516_b21518cd21edf2200801a3e123beb33b.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_730189516_b21518cd21edf2200801a3e123beb33b.jpg', null, 'pending', '10', '2012-02-23 11:57:26', '1329973046', '730189516_b21518cd21edf2200801a3e123beb33b.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('48', null, 'asdvasvas asdasd asd asd asd asd asdasasd as ad ', 'image', 'file', 'http://guinnessapp.dev/image?gid=10&src=730189516_364fe6cf5ab433568a14a6e3db0ea278.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_730189516_364fe6cf5ab433568a14a6e3db0ea278.jpg', null, 'pending', '10', '2012-02-23 11:59:35', '1329973175', '730189516_364fe6cf5ab433568a14a6e3db0ea278.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('49', null, 'kjhk kh', 'image', 'file', 'http://guinnessapp.dev/image?gid=10&src=730189516_72fba5d0b6a1e3ea25e13df0bdf55c23.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_730189516_72fba5d0b6a1e3ea25e13df0bdf55c23.jpg', null, 'pending', '10', '2012-02-23 12:01:41', '1329973301', '730189516_72fba5d0b6a1e3ea25e13df0bdf55c23.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('50', null, 'dgsdgsdf', 'image', 'file', 'http://guinnessapp.dev/image?gid=10&src=730189516_b3d0161c4001e2f7adc5677e00e12c3d.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_730189516_b3d0161c4001e2f7adc5677e00e12c3d.jpg', null, 'pending', '10', '2012-02-23 12:02:53', '1329973373', '730189516_b3d0161c4001e2f7adc5677e00e12c3d.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('51', null, 'asdfasdf', 'image', 'file', 'http://guinnessapp.dev/image?gid=10&src=730189516_9300e97107f467b418667c907e0f8aaf.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_730189516_9300e97107f467b418667c907e0f8aaf.jpg', null, 'pending', '10', '2012-02-23 12:04:30', '1329973470', '730189516_9300e97107f467b418667c907e0f8aaf.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('52', null, 'afasdfs asfas asdfasdf', 'image', 'file', 'http://guinnessapp.dev/image?gid=10&src=730189516_827bbc553396f71e912e2a709f77ea94.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_730189516_827bbc553396f71e912e2a709f77ea94.jpg', null, 'pending', '10', '2012-02-23 12:06:50', '1329973610', '730189516_827bbc553396f71e912e2a709f77ea94.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('53', null, 'sdfsdfsd sdfsdf sdf sdfsdf', 'image', 'file', 'http://guinnessapp.dev/image?gid=10&src=730189516_6e886e39e1b38ad5448e1ce7d4e96850.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_730189516_6e886e39e1b38ad5448e1ce7d4e96850.jpg', null, 'pending', '10', '2012-02-23 14:12:47', '1329981167', '730189516_6e886e39e1b38ad5448e1ce7d4e96850.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('54', null, 'sdfsd sdfsdf sdf sdfsdf sdf sdf', 'image', 'file', 'http://guinnessapp.dev/image?gid=10&src=730189516_195670e0c99f3bf99b29832ff7891bdc.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_730189516_195670e0c99f3bf99b29832ff7891bdc.jpg', null, 'pending', '10', '2012-02-23 14:14:09', '1329981249', '730189516_195670e0c99f3bf99b29832ff7891bdc.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('55', null, 'sdfsdfsdfsdfsdf', 'image', 'file', 'http://guinnessapp.dev/image?gid=10&src=730189516_033568fd64fc9b41b1c89864272cc77f.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_730189516_033568fd64fc9b41b1c89864272cc77f.jpg', null, 'pending', '10', '2012-02-23 14:20:08', '1329981608', '730189516_033568fd64fc9b41b1c89864272cc77f.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('56', null, 'adfa asfasf asdfasdfa asdf asdfasdfas', 'image', 'file', 'http://guinnessapp.dev/image?gid=10&src=730189516_6085caec3593c44bd34f75440929ceee.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_730189516_6085caec3593c44bd34f75440929ceee.jpg', null, 'pending', '10', '2012-02-23 14:33:06', '1329982386', '730189516_6085caec3593c44bd34f75440929ceee.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('57', null, 'asdf asd fasd fasdfasdfas', 'image', 'file', 'http://guinnessapp.dev/image?gid=10&src=730189516_55aa76b36e3e936a9e407f0040f8c909.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_730189516_55aa76b36e3e936a9e407f0040f8c909.jpg', null, 'pending', '10', '2012-02-23 14:46:28', '1329983188', '730189516_55aa76b36e3e936a9e407f0040f8c909.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('58', null, 'fsdfsdfsdf', 'image', 'file', 'http://guinnessapp.dev/image?gid=10&src=730189516_f165c357972026f62ebef247b98e530d.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_730189516_f165c357972026f62ebef247b98e530d.jpg', null, 'pending', '10', '2012-02-23 15:39:07', '1329986347', '730189516_f165c357972026f62ebef247b98e530d.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('59', null, 'sdfsdfsdf', 'image', 'file', 'http://guinnessapp.dev/image?gid=10&src=730189516_b3a5a96ea9bee09d3a1c2205b0565666.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_730189516_b3a5a96ea9bee09d3a1c2205b0565666.jpg', null, 'pending', '10', '2012-02-23 15:41:45', '1329986505', '730189516_b3a5a96ea9bee09d3a1c2205b0565666.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('60', null, 'fsdgs sdg sd gsdg sdfgsdfgsd', 'image', 'file', 'http://guinnessapp.dev/image?gid=10&src=730189516_a9a1c8d9d1dbd8faa4fb25b2744cb55d.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_730189516_a9a1c8d9d1dbd8faa4fb25b2744cb55d.jpg', null, 'pending', '10', '2012-02-23 15:47:20', '1329986840', '730189516_a9a1c8d9d1dbd8faa4fb25b2744cb55d.jpg', '0', '0');
INSERT INTO `campaign_media` VALUES ('61', null, 'asdf asdfasdfas fas asfas a s', 'image', 'file', 'http://guinnessapp.dev/image?gid=10&src=730189516_e1eb20c41408f038fafbb3615ee71996.jpg', 'https://guinnessapp.dev/image?gid=10&src=thumb_730189516_e1eb20c41408f038fafbb3615ee71996.jpg', null, 'active', '10', '2012-02-23 15:48:18', '1329986898', '730189516_e1eb20c41408f038fafbb3615ee71996.jpg', '0', '1');
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
INSERT INTO `campaign_media_owner` VALUES ('22', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('23', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('24', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('25', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('26', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('27', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('28', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('29', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('30', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('31', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('32', '730189516', '1');
INSERT INTO `campaign_media_owner` VALUES ('33', '730189516', '1');
INSERT INTO `campaign_media_owner` VALUES ('34', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('35', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('36', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('37', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('38', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('39', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('40', '811922327', '10');
INSERT INTO `campaign_media_owner` VALUES ('41', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('42', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('43', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('44', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('45', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('46', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('47', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('48', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('49', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('50', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('51', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('52', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('53', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('54', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('55', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('56', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('57', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('58', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('59', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('60', '730189516', '10');
INSERT INTO `campaign_media_owner` VALUES ('61', '730189516', '10');
INSERT INTO `campaign_media_vote` VALUES ('27', '730189516', '1329908596');
INSERT INTO `campaign_media_vote` VALUES ('28', '730189516', '1329905363');
INSERT INTO `campaign_media_vote` VALUES ('40', '730189516', '1329988079');
INSERT INTO `campaign_media_vote` VALUES ('40', '811922327', '1329913318');
INSERT INTO `campaign_media_vote` VALUES ('40', '100000413942502', '1330056531');
INSERT INTO `campaign_media_vote` VALUES ('61', '100000413942502', '1330056482');
INSERT INTO `campaign_setting` VALUES ('23', 'SITE_URL_SSL', 'https://guinnessapp.dev/', 'SSL Base App Site URL');
INSERT INTO `campaign_setting` VALUES ('9', 'SITE_URL', 'http://guinnessapp.dev/', 'Base App Site URL');
INSERT INTO `campaign_setting` VALUES ('17', 'TRAC_USERID', 'fbdev', 'Traction API UserID');
INSERT INTO `campaign_setting` VALUES ('18', 'TRAC_PASSWORD', 'th1nkw3b', 'Traction API Password');
INSERT INTO `campaign_setting` VALUES ('19', 'TRAC_ENDPOINTID', '17259', 'Traction API EndpointID');
INSERT INTO `campaign_setting` VALUES ('20', 'TRAC_ATTR_FBUID', '3012669', 'Traction Custom Attribute ID for user Facebook ID');
INSERT INTO `campaign_setting` VALUES ('21', 'TRAC_ATTR_GID', '3014098', 'Traction Custom Attribute for GID');
INSERT INTO `campaign_setting` VALUES ('24', 'TRAC_ATTR_MOBILE2', '3031180', 'Traction Custom Attribute for MOBILE 2nd');
INSERT INTO `campaign_user` VALUES ('1', null, 'Admin', 'admin@demo.com', '76a2173be6393254e72ffa4d6df1030a', 'administrator', 'active', null, null, null);
