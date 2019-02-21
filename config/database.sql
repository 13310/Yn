-- cteate yn databse
CREATE DATABASE IF NOT EXISTS yn default charset utf8mb4 COLLATE utf8mb4_general_ci;
-- switch database
use yn;

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for yn_cooperation
-- ----------------------------
DROP TABLE IF EXISTS `yn_cooperation`;
CREATE TABLE `yn_cooperation` (
  `user_id` int(11) DEFAULT NULL,
  `cooperation_id` int(11) DEFAULT NULL COMMENT '合作者id',
  `cooperation_time` datetime DEFAULT NULL ON UPDATE current_timestamp() COMMENT '合作时间',
  `cooperation_path` varchar(100) DEFAULT NULL COMMENT '合作文件地址',
  `cooperation_remark` varchar(1000) DEFAULT NULL COMMENT '合作备注',
  `cooperation_keys` varchar(255) DEFAULT NULL COMMENT '合作key',
  KEY `cooperation_user` (`user_id`),
  CONSTRAINT `cooperation_user` FOREIGN KEY (`user_id`) REFERENCES `yn_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for yn_share
-- ----------------------------
DROP TABLE IF EXISTS `yn_share`;
CREATE TABLE `yn_share` (
  `user_id` int(11) NOT NULL COMMENT '分享者',
  `share_key` varchar(255) NOT NULL DEFAULT '' COMMENT '分享key',
  `share_path` varchar(255) NOT NULL DEFAULT '' COMMENT '相对地址',
  `share_time` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `share_passwd` varchar(255) DEFAULT '' COMMENT '分享密码',
  `share_file_name` varchar(255) NOT NULL DEFAULT '' COMMENT '文件名',
  `share_file_mime` varchar(255) NOT NULL DEFAULT '' COMMENT '文件mime',
  `share_file_size` varchar(30) NOT NULL DEFAULT '' COMMENT '文件大小',
  `share_validity` int(11) NOT NULL DEFAULT 0 COMMENT '分享邮箱器(0:永久)',
  `share_view_count` int(11) DEFAULT 0 COMMENT '查看次数',
  `share_download_count` int(11) DEFAULT 0 COMMENT '下载次数',
  `share_save_count` int(11) DEFAULT 0 COMMENT '保存次数',
  PRIMARY KEY (`share_key`),
  KEY `share_user` (`user_id`),
  CONSTRAINT `share_user` FOREIGN KEY (`user_id`) REFERENCES `yn_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for yn_trash
-- ----------------------------
DROP TABLE IF EXISTS `yn_trash`;
CREATE TABLE `yn_trash` (
  `user_id` int(255) NOT NULL,
  `trash_id` int(11) NOT NULL,
  `original_size` double(25,2) NOT NULL,
  `original_name` varchar(500) NOT NULL,
  `original_path` varchar(100) NOT NULL COMMENT '原来路径',
  `original_type` varchar(10) DEFAULT '' COMMENT '类型',
  `trash_path` varchar(500) NOT NULL,
  `trash_time` datetime NOT NULL COMMENT '删除时间',
  `original_include_dir` int(11) DEFAULT NULL,
  `original_include_file` int(11) DEFAULT NULL,
  KEY `trash_user` (`user_id`),
  CONSTRAINT `trash_user` FOREIGN KEY (`user_id`) REFERENCES `yn_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for yn_user
-- ----------------------------
DROP TABLE IF EXISTS `yn_user`;
CREATE TABLE `yn_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `user_name` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名称',
  `user_email` varchar(255) NOT NULL COMMENT '用户邮箱',
  `user_logo` varchar(200) DEFAULT NULL,
  `user_passwd` varchar(200) NOT NULL COMMENT '用户密码',
  `auto_login_check` varchar(255) DEFAULT '' COMMENT '用于检查维持长时间登录的值',
  `total_storage_size` double(11,2) DEFAULT 1024.00 COMMENT '总共大小',
  `recycled_size` double(11,2) DEFAULT 0.00 COMMENT '回收站大小',
  `file_size` double(11,2) DEFAULT 0.00 COMMENT '已使用大小',
  `user_rights` int(11) NOT NULL DEFAULT 88 COMMENT '操作权限',
  `user_registered_time` varchar(25) DEFAULT '',
  `user_registered_ip` varchar(20) DEFAULT '',
  `user_drive_id` varchar(255) DEFAULT '' COMMENT '云盘对应值',
  `user_validated` int(255) NOT NULL DEFAULT 0 COMMENT '邮箱验证',
  `user_next_login_ip` varchar(20) DEFAULT NULL,
  `user_next_login_time` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `user_locked` int(255) DEFAULT 0 COMMENT '是否禁用此用户',
  PRIMARY KEY (`user_id`),
  KEY `UserName` (`user_name`),
  KEY `UserID` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=78999 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for yn_userlog
-- ----------------------------
DROP TABLE IF EXISTS `yn_userlog`;
CREATE TABLE `yn_userlog` (
  `user_id` int(11) DEFAULT NULL,
  `log_time` datetime DEFAULT NULL ON UPDATE current_timestamp() COMMENT '日志-时间',
  `log_ip` varchar(100) DEFAULT NULL COMMENT '日志-ip',
  `log_browser` varchar(255) DEFAULT NULL COMMENT '日志-浏览器',
  `log_behavior` varchar(255) DEFAULT NULL COMMENT '日志-行为',
  KEY `log_user` (`user_id`),
  CONSTRAINT `log_user` FOREIGN KEY (`user_id`) REFERENCES `yn_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET FOREIGN_KEY_CHECKS=1;
