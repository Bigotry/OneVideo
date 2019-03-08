/*
Navicat MySQL Data Transfer

Source Server         : localhost_link
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : onevideo

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2019-03-08 17:52:28
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ob_action_log`
-- ----------------------------
DROP TABLE IF EXISTS `ob_action_log`;
CREATE TABLE `ob_action_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行会员id',
  `username` char(30) NOT NULL DEFAULT '' COMMENT '用户名',
  `ip` char(30) NOT NULL DEFAULT '' COMMENT '执行行为者ip',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '行为名称',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '执行的URL',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '执行行为的时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1436 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='行为日志表';

-- ----------------------------
-- Records of ob_action_log
-- ----------------------------

-- ----------------------------
-- Table structure for `ob_addon`
-- ----------------------------
DROP TABLE IF EXISTS `ob_addon`;
CREATE TABLE `ob_addon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '插件名或标识',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '中文名称',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT '插件描述',
  `config` text NOT NULL COMMENT '配置',
  `author` varchar(40) NOT NULL DEFAULT '' COMMENT '作者',
  `version` varchar(20) NOT NULL DEFAULT '' COMMENT '版本号',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='插件表';

-- ----------------------------
-- Records of ob_addon
-- ----------------------------
INSERT INTO `ob_addon` VALUES ('3', 'File', '文件上传', '文件上传插件', '', 'Jack', '1.0', '1', '0', '0');
INSERT INTO `ob_addon` VALUES ('4', 'Icon', '图标选择', '图标选择插件', '', 'Bigotry', '1.0', '1', '0', '0');
INSERT INTO `ob_addon` VALUES ('5', 'Editor', '文本编辑器', '富文本编辑器', '', 'Bigotry', '1.0', '1', '0', '0');

-- ----------------------------
-- Table structure for `ob_api`
-- ----------------------------
DROP TABLE IF EXISTS `ob_api`;
CREATE TABLE `ob_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(150) NOT NULL DEFAULT '' COMMENT '接口名称',
  `group_id` int(6) unsigned NOT NULL DEFAULT '0' COMMENT '接口分组',
  `request_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '请求类型 0:POST  1:GET',
  `api_url` char(50) NOT NULL DEFAULT '' COMMENT '请求路径',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT '接口描述',
  `describe_text` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '接口富文本描述',
  `is_request_data` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要请求数据',
  `request_data` text NOT NULL COMMENT '请求数据',
  `response_data` text NOT NULL COMMENT '响应数据',
  `is_response_data` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要响应数据',
  `is_user_token` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要用户token',
  `is_response_sign` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否返回数据签名',
  `is_request_sign` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否验证请求数据签名',
  `response_examples` text NOT NULL COMMENT '响应栗子',
  `developer` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '研发者',
  `api_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '接口状态（0:待研发，1:研发中，2:测试中，3:已完成）',
  `is_page` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为分页接口 0：否  1：是',
  `sort` tinyint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据状态',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=196 DEFAULT CHARSET=utf8 COMMENT='API表';

-- ----------------------------
-- Records of ob_api
-- ----------------------------
INSERT INTO `ob_api` VALUES ('186', '登录', '34', '0', 'common/login', '系统登录接口', '', '1', '[{\"field_name\":\"username\",\"data_type\":\"0\",\"is_require\":\"1\",\"field_describe\":\"\\u7528\\u6237\\u540d\"},{\"field_name\":\"password\",\"data_type\":\"0\",\"is_require\":\"1\",\"field_describe\":\"\\u5bc6\\u7801\"}]', '[{\"field_name\":\"data\",\"data_type\":\"2\",\"field_describe\":\"\\u4f1a\\u5458\\u6570\\u636e\\u53causer_token\"}]', '1', '0', '1', '0', '{\r\n    &quot;code&quot;: 0,\r\n    &quot;msg&quot;: &quot;操作成功&quot;,\r\n    &quot;data&quot;: {\r\n        &quot;member_id&quot;: 51,\r\n        &quot;nickname&quot;: &quot;sadasdas&quot;,\r\n        &quot;username&quot;: &quot;sadasdas&quot;,\r\n        &quot;create_time&quot;: &quot;2017-09-09 13:40:17&quot;,\r\n        &quot;user_token&quot;: &quot;eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJPbmVCYXNlIEpXVCIsImlhdCI6MTUwNDkzNTYxNywiZXhwIjoxNTA0OTM2NjE3LCJhdWQiOiJPbmVCYXNlIiwic3ViIjoiT25lQmFzZSIsImRhdGEiOnsibWVtYmVyX2lkIjo1MSwibmlja25hbWUiOiJzYWRhc2RhcyIsInVzZXJuYW1lIjoic2FkYXNkYXMiLCJjcmVhdGVfdGltZSI6IjIwMTctMDktMDkgMTM6NDA6MTcifX0.6PEShODuifNsa-x1TumLoEaR2TCXpUEYgjpD3Mz3GRM&quot;\r\n    }\r\n}', '0', '1', '0', '0', '1', '1504501410', '1520504982');
INSERT INTO `ob_api` VALUES ('187', '视频分类列表', '44', '0', 'video/categorylist', '视频分类列表', '', '0', '', '[{\"field_name\":\"id\",\"data_type\":\"0\",\"field_describe\":\"\\u6587\\u7ae0\\u5206\\u7c7bID\"},{\"field_name\":\"name\",\"data_type\":\"0\",\"field_describe\":\"\\u6587\\u7ae0\\u5206\\u7c7b\\u540d\\u79f0\"}]', '1', '1', '0', '0', '{\r\n    &quot;code&quot;: 0,\r\n    &quot;msg&quot;: &quot;操作成功&quot;,\r\n    &quot;data&quot;: [\r\n        {\r\n            &quot;id&quot;: 2,\r\n            &quot;name&quot;: &quot;测试文章分类2&quot;\r\n        },\r\n        {\r\n            &quot;id&quot;: 1,\r\n            &quot;name&quot;: &quot;测试文章分类1&quot;\r\n        }\r\n    ]\r\n}', '0', '0', '0', '2', '1', '1504765581', '1552020003');
INSERT INTO `ob_api` VALUES ('188', '最新视频列表', '44', '0', 'video/newvideolist', '最新视频列表', '', '0', '', '', '0', '1', '0', '0', '{\r\n    &quot;code&quot;: 0,\r\n    &quot;msg&quot;: &quot;操作成功&quot;,\r\n    &quot;data&quot;: {\r\n        &quot;total&quot;: 9,\r\n        &quot;per_page&quot;: &quot;10&quot;,\r\n        &quot;current_page&quot;: 1,\r\n        &quot;last_page&quot;: 1,\r\n        &quot;data&quot;: [\r\n            {\r\n                &quot;id&quot;: 16,\r\n                &quot;name&quot;: &quot;11111111&quot;,\r\n                &quot;category_id&quot;: 2,\r\n                &quot;describe&quot;: &quot;22222222&quot;,\r\n                &quot;create_time&quot;: &quot;2017-08-07 13:58:37&quot;\r\n            },\r\n            {\r\n                &quot;id&quot;: 15,\r\n                &quot;name&quot;: &quot;tttttt&quot;,\r\n                &quot;category_id&quot;: 1,\r\n                &quot;describe&quot;: &quot;sddd&quot;,\r\n                &quot;create_time&quot;: &quot;2017-08-07 13:24:46&quot;\r\n            }\r\n        ]\r\n    }\r\n}', '0', '0', '1', '1', '1', '1504779780', '1551951782');
INSERT INTO `ob_api` VALUES ('192', '推荐视频列表', '44', '0', 'video/recommendVideoList', '', '', '0', '', '', '0', '1', '0', '0', '', '0', '0', '1', '0', '1', '1551955104', '1551955104');
INSERT INTO `ob_api` VALUES ('193', '视频列表', '44', '0', 'video/videolist', '', '', '1', '[{\"field_name\":\"cid\",\"data_type\":\"0\",\"is_require\":\"1\",\"field_describe\":\"\\u5206\\u7c7bid\"}]', '', '0', '1', '0', '0', '视频列表', '0', '0', '1', '0', '1', '1552020913', '1552020913');
INSERT INTO `ob_api` VALUES ('189', '首页接口', '45', '0', 'combination/index', '首页聚合接口', '', '1', '[{\"field_name\":\"category_id\",\"data_type\":\"0\",\"is_require\":\"0\",\"field_describe\":\"\\u6587\\u7ae0\\u5206\\u7c7bID\"}]', '[{\"field_name\":\"article_category_list\",\"data_type\":\"2\",\"field_describe\":\"\\u6587\\u7ae0\\u5206\\u7c7b\\u6570\\u636e\"},{\"field_name\":\"article_list\",\"data_type\":\"2\",\"field_describe\":\"\\u6587\\u7ae0\\u6570\\u636e\"}]', '1', '0', '1', '0', '{\r\n    &quot;code&quot;: 0,\r\n    &quot;msg&quot;: &quot;操作成功&quot;,\r\n    &quot;data&quot;: {\r\n        &quot;article_category_list&quot;: [\r\n            {\r\n                &quot;id&quot;: 2,\r\n                &quot;name&quot;: &quot;测试文章分类2&quot;\r\n            },\r\n            {\r\n                &quot;id&quot;: 1,\r\n                &quot;name&quot;: &quot;测试文章分类1&quot;\r\n            }\r\n        ],\r\n        &quot;article_list&quot;: {\r\n            &quot;total&quot;: 8,\r\n            &quot;per_page&quot;: &quot;2&quot;,\r\n            &quot;current_page&quot;: &quot;1&quot;,\r\n            &quot;last_page&quot;: 4,\r\n            &quot;data&quot;: [\r\n                {\r\n                    &quot;id&quot;: 15,\r\n                    &quot;name&quot;: &quot;tttttt&quot;,\r\n                    &quot;category_id&quot;: 1,\r\n                    &quot;describe&quot;: &quot;sddd&quot;,\r\n                    &quot;create_time&quot;: &quot;2017-08-07 13:24:46&quot;\r\n                },\r\n                {\r\n                    &quot;id&quot;: 14,\r\n                    &quot;name&quot;: &quot;1111111111111111111&quot;,\r\n                    &quot;category_id&quot;: 1,\r\n                    &quot;describe&quot;: &quot;123123&quot;,\r\n                    &quot;create_time&quot;: &quot;2017-08-04 15:37:20&quot;\r\n                }\r\n            ]\r\n        }\r\n    }\r\n}', '0', '0', '1', '0', '-1', '1504785072', '1551951146');
INSERT INTO `ob_api` VALUES ('190', '详情页接口', '45', '0', 'combination/details', '详情页接口', '', '1', '[{\"field_name\":\"article_id\",\"data_type\":\"0\",\"is_require\":\"1\",\"field_describe\":\"\\u6587\\u7ae0ID\"}]', '[{\"field_name\":\"article_category_list\",\"data_type\":\"2\",\"field_describe\":\"\\u6587\\u7ae0\\u5206\\u7c7b\\u6570\\u636e\"},{\"field_name\":\"article_details\",\"data_type\":\"2\",\"field_describe\":\"\\u6587\\u7ae0\\u8be6\\u60c5\\u6570\\u636e\"}]', '1', '0', '0', '0', '{\r\n    &quot;code&quot;: 0,\r\n    &quot;msg&quot;: &quot;操作成功&quot;,\r\n    &quot;data&quot;: {\r\n        &quot;article_category_list&quot;: [\r\n            {\r\n                &quot;id&quot;: 2,\r\n                &quot;name&quot;: &quot;测试文章分类2&quot;\r\n            },\r\n            {\r\n                &quot;id&quot;: 1,\r\n                &quot;name&quot;: &quot;测试文章分类1&quot;\r\n            }\r\n        ],\r\n        &quot;article_details&quot;: {\r\n            &quot;id&quot;: 1,\r\n            &quot;name&quot;: &quot;213&quot;,\r\n            &quot;category_id&quot;: 1,\r\n            &quot;describe&quot;: &quot;test001&quot;,\r\n            &quot;content&quot;: &quot;第三方发送到&quot;&quot;&quot;,\r\n            &quot;create_time&quot;: &quot;2014-07-22 11:56:53&quot;\r\n        }\r\n    }\r\n}', '0', '0', '0', '0', '-1', '1504922092', '1551951149');
INSERT INTO `ob_api` VALUES ('191', '修改密码', '34', '0', 'common/changepassword', '修改密码接口', '', '1', '[{\"field_name\":\"old_password\",\"data_type\":\"0\",\"is_require\":\"1\",\"field_describe\":\"\\u65e7\\u5bc6\\u7801\"},{\"field_name\":\"new_password\",\"data_type\":\"0\",\"is_require\":\"1\",\"field_describe\":\"\\u65b0\\u5bc6\\u7801\"}]', '', '0', '1', '0', '0', '{\r\n    &quot;code&quot;: 0,\r\n    &quot;msg&quot;: &quot;操作成功&quot;,\r\n    &quot;exe_time&quot;: &quot;0.037002&quot;\r\n}', '0', '0', '0', '0', '-1', '1504941496', '1551951152');
INSERT INTO `ob_api` VALUES ('194', '设置视频播放信息', '44', '0', 'video/setPlayLog', '', '', '1', '[{\"field_name\":\"vid\",\"data_type\":\"0\",\"is_require\":\"1\",\"field_describe\":\"\\u89c6\\u9891ID\"}]', '', '0', '1', '0', '0', '', '0', '0', '0', '0', '1', '1552025636', '1552025636');
INSERT INTO `ob_api` VALUES ('195', '播放记录列表', '44', '0', 'video/playloglist', '', '', '0', '', '', '0', '1', '0', '0', '', '0', '0', '1', '0', '1', '1552029960', '1552029960');

-- ----------------------------
-- Table structure for `ob_api_group`
-- ----------------------------
DROP TABLE IF EXISTS `ob_api_group`;
CREATE TABLE `ob_api_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(120) NOT NULL DEFAULT '' COMMENT 'aip分组名称',
  `sort` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=72 DEFAULT CHARSET=utf8 COMMENT='api分组表';

-- ----------------------------
-- Records of ob_api_group
-- ----------------------------
INSERT INTO `ob_api_group` VALUES ('34', '基础接口', '0', '1504501195', '0', '1');
INSERT INTO `ob_api_group` VALUES ('44', '视频接口', '1', '1551951124', '1504765319', '1');
INSERT INTO `ob_api_group` VALUES ('45', '聚合接口', '0', '1551951110', '1504784149', '-1');

-- ----------------------------
-- Table structure for `ob_auth_group`
-- ----------------------------
DROP TABLE IF EXISTS `ob_auth_group`;
CREATE TABLE `ob_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `module` varchar(20) NOT NULL DEFAULT '' COMMENT '用户组所属模块',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '用户组名称',
  `describe` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` varchar(1000) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  `member_id` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限组表';

-- ----------------------------
-- Records of ob_auth_group
-- ----------------------------

-- ----------------------------
-- Table structure for `ob_auth_group_access`
-- ----------------------------
DROP TABLE IF EXISTS `ob_auth_group_access`;
CREATE TABLE `ob_auth_group_access` (
  `member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户组id',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户组授权表';

-- ----------------------------
-- Records of ob_auth_group_access
-- ----------------------------

-- ----------------------------
-- Table structure for `ob_config`
-- ----------------------------
DROP TABLE IF EXISTS `ob_config`;
CREATE TABLE `ob_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置标题',
  `group` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '配置选项',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT '配置说明',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `value` text NOT NULL COMMENT '配置值',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `type` (`type`),
  KEY `group` (`group`)
) ENGINE=MyISAM AUTO_INCREMENT=67 DEFAULT CHARSET=utf8 COMMENT='配置表';

-- ----------------------------
-- Records of ob_config
-- ----------------------------
INSERT INTO `ob_config` VALUES ('1', 'seo_title', '1', '网站标题', '1', '', '网站标题前台显示标题，优先级低于SEO模块', '1378898976', '1551938306', '1', 'OneVideo', '3');
INSERT INTO `ob_config` VALUES ('2', 'seo_description', '2', '网站描述', '1', '', '网站搜索引擎描述，优先级低于SEO模块', '1378898976', '1512555314', '1', 'OneBase|ThinkPHP5', '100');
INSERT INTO `ob_config` VALUES ('3', 'seo_keywords', '2', '网站关键字', '1', '', '网站搜索引擎关键字，优先级低于SEO模块', '1378898976', '1551938306', '1', 'OneVideo', '99');
INSERT INTO `ob_config` VALUES ('9', 'config_type_list', '3', '配置类型列表', '3', '', '主要用于数据解析和页面表单的生成', '1378898976', '1512982406', '1', '0:数字\r\n1:字符\r\n2:文本\r\n3:数组\r\n4:枚举\r\n5:图片\r\n6:文件\r\n7:富文本\r\n8:单选\r\n9:多选\r\n10:日期\r\n11:时间\r\n12:颜色', '100');
INSERT INTO `ob_config` VALUES ('20', 'config_group_list', '3', '配置分组', '3', '', '配置分组', '1379228036', '1512982406', '1', '1:基础\r\n2:数据\r\n3:系统\r\n4:API', '100');
INSERT INTO `ob_config` VALUES ('25', 'list_rows', '0', '每页数据记录数', '2', '', '数据每页显示记录数', '1379503896', '1507197630', '1', '10', '10');
INSERT INTO `ob_config` VALUES ('29', 'data_backup_part_size', '0', '数据库备份卷大小', '2', '', '该值用于限制压缩后的分卷最大长度。单位：B', '1381482488', '1507197630', '1', '52428800', '7');
INSERT INTO `ob_config` VALUES ('30', 'data_backup_compress', '4', '数据库备份文件是否启用压缩', '2', '0:不压缩\r\n1:启用压缩', '压缩备份文件需要PHP环境支持gzopen,gzwrite函数', '1381713345', '1507197630', '1', '1', '9');
INSERT INTO `ob_config` VALUES ('31', 'data_backup_compress_level', '4', '数据库备份文件压缩级别', '2', '1:普通\r\n4:一般\r\n9:最高', '数据库备份文件的压缩级别，该配置在开启压缩时生效', '1381713408', '1507197630', '1', '9', '10');
INSERT INTO `ob_config` VALUES ('33', 'allow_url', '3', '不受权限验证的url', '3', '', '', '1386644047', '1512982406', '1', '0:file/pictureupload\r\n1:addon/execute', '100');
INSERT INTO `ob_config` VALUES ('43', 'empty_list_describe', '1', '数据列表为空时的描述信息', '2', '', '', '1492278127', '1507197630', '1', 'aOh! 暂时还没有数据~', '0');
INSERT INTO `ob_config` VALUES ('44', 'trash_config', '3', '回收站配置', '3', '', 'key为模型名称，值为显示列。', '1492312698', '1512982406', '1', 'Config:name\r\nAuthGroup:name\r\nMember:nickname\r\nMenu:name\r\nArticle:name\r\nArticleCategory:name\r\nAddon:name\r\nPicture:name\r\nFile:name\r\nActionLog:describe\r\nApi:name\r\nApiGroup:name\r\nBlogroll:name', '0');
INSERT INTO `ob_config` VALUES ('49', 'static_domain', '1', '静态资源域名', '1', '', '若静态资源为本地资源则此项为空，若为外部资源则为存放静态资源的域名', '1502430387', '1551938306', '1', '', '0');
INSERT INTO `ob_config` VALUES ('52', 'team_developer', '3', '研发团队人员', '4', '', '', '1504236453', '1510894595', '1', '0:Bigotry\r\n1:扫地僧', '0');
INSERT INTO `ob_config` VALUES ('53', 'api_status_option', '3', 'API接口状态', '4', '', '', '1504242433', '1510894595', '1', '0:待研发\r\n1:研发中\r\n2:测试中\r\n3:已完成', '0');
INSERT INTO `ob_config` VALUES ('54', 'api_data_type_option', '3', 'API数据类型', '4', '', '', '1504328208', '1510894595', '1', '0:字符\r\n1:文本\r\n2:数组\r\n3:文件', '0');
INSERT INTO `ob_config` VALUES ('55', 'frontend_theme', '1', '前端主题', '1', '', '', '1504762360', '1551938306', '1', 'default', '0');
INSERT INTO `ob_config` VALUES ('56', 'api_domain', '1', 'API部署域名', '4', '', '', '1504779094', '1510894595', '1', 'https://demo.onebase.org', '0');
INSERT INTO `ob_config` VALUES ('57', 'api_key', '1', 'API加密KEY', '4', '', '泄露后API将存在安全隐患', '1505302112', '1510894595', '1', 'l2V|gfZp{8`;jzR~6Y1_', '0');
INSERT INTO `ob_config` VALUES ('58', 'loading_icon', '4', '页面Loading图标设置', '1', '1:图标1\r\n2:图标2\r\n3:图标3\r\n4:图标4\r\n5:图标5\r\n6:图标6\r\n7:图标7', '页面Loading图标支持7种图标切换', '1505377202', '1551938306', '1', '7', '80');
INSERT INTO `ob_config` VALUES ('59', 'sys_file_field', '3', '文件字段配置', '3', '', 'key为模型名，值为文件列名。', '1505799386', '1512982406', '1', '0_article:file_id', '0');
INSERT INTO `ob_config` VALUES ('60', 'sys_picture_field', '3', '图片字段配置', '3', '', 'key为模型名，值为图片列名。', '1506315422', '1512982406', '1', '0_article:cover_id\r\n1_article:img_ids', '0');
INSERT INTO `ob_config` VALUES ('61', 'jwt_key', '1', 'JWT加密KEY', '4', '', '', '1506748805', '1510894595', '1', 'l2V|DSFXXXgfZp{8`;FjzR~6Y1_', '0');
INSERT INTO `ob_config` VALUES ('65', 'admin_allow_ip', '3', '超级管理员登录IP', '3', '', '后台超级管理员登录IP限制，其他角色不受限。', '1510995580', '1512982406', '1', '0:27.22.112.250', '0');
INSERT INTO `ob_config` VALUES ('66', 'pjax_mode', '8', 'PJAX模式', '3', '0:否\r\n1:是', '若为PJAX模式则浏览器不会刷新，若为常规模式则为AJAX+刷新', '1512370397', '1512982406', '1', '1', '120');

-- ----------------------------
-- Table structure for `ob_driver`
-- ----------------------------
DROP TABLE IF EXISTS `ob_driver`;
CREATE TABLE `ob_driver` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `service_name` varchar(40) NOT NULL DEFAULT '' COMMENT '服务标识',
  `driver_name` varchar(20) NOT NULL DEFAULT '' COMMENT '驱动标识',
  `config` text NOT NULL COMMENT '配置',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='插件表';

-- ----------------------------
-- Records of ob_driver
-- ----------------------------

-- ----------------------------
-- Table structure for `ob_file`
-- ----------------------------
DROP TABLE IF EXISTS `ob_file`;
CREATE TABLE `ob_file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '原始文件名',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '保存名称',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '远程地址',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上传时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='文件表';

-- ----------------------------
-- Records of ob_file
-- ----------------------------
INSERT INTO `ob_file` VALUES ('2', '468107a388a4666c5d42fdf43dd665cb.mp4', '20190308/468107a388a4666c5d42fdf43dd665cb.mp4', '', '43a8f0a8aa83eeaa4f7a926fd975ab3e492a7046', '1552033540', '1552033540', '1');

-- ----------------------------
-- Table structure for `ob_hook`
-- ----------------------------
DROP TABLE IF EXISTS `ob_hook`;
CREATE TABLE `ob_hook` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `describe` varchar(255) NOT NULL COMMENT '描述',
  `addon_list` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子挂载的插件 ''，''分割',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='钩子表';

-- ----------------------------
-- Records of ob_hook
-- ----------------------------
INSERT INTO `ob_hook` VALUES ('36', 'File', '文件上传钩子', 'File', '1', '0', '0');
INSERT INTO `ob_hook` VALUES ('37', 'Icon', '图标选择钩子', 'Icon', '1', '0', '0');
INSERT INTO `ob_hook` VALUES ('38', 'ArticleEditor', '富文本编辑器', 'Editor', '1', '0', '0');

-- ----------------------------
-- Table structure for `ob_member`
-- ----------------------------
DROP TABLE IF EXISTS `ob_member`;
CREATE TABLE `ob_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `nickname` char(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `username` char(16) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `email` char(32) NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `mobile` char(15) NOT NULL DEFAULT '' COMMENT '用户手机',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户状态',
  `leader_id` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '上级会员ID',
  `is_share_member` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否共享会员',
  `is_inside` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为后台使用者',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='会员表';

-- ----------------------------
-- Records of ob_member
-- ----------------------------
INSERT INTO `ob_member` VALUES ('1', 'admin', 'admin', 'bff07f50392601363c35f8bcca1447a9', '3162875@qq.com', '18555550710', '1552018967', '1551937819', '1', '0', '0', '1');

-- ----------------------------
-- Table structure for `ob_menu`
-- ----------------------------
DROP TABLE IF EXISTS `ob_menu`;
CREATE TABLE `ob_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `module` char(20) NOT NULL DEFAULT '' COMMENT '模块',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `is_hide` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `is_shortcut` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否快捷操作',
  `icon` char(30) NOT NULL DEFAULT '' COMMENT '图标',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=211 DEFAULT CHARSET=utf8 COMMENT='菜单表';

-- ----------------------------
-- Records of ob_menu
-- ----------------------------
INSERT INTO `ob_menu` VALUES ('1', '系统首页', '0', '1', 'admin', 'index/index', '0', '0', 'fa-home', '1', '1520506753', '0');
INSERT INTO `ob_menu` VALUES ('16', '会员管理', '0', '3', 'admin', 'member/index', '0', '0', 'fa-users', '1', '1520506753', '0');
INSERT INTO `ob_menu` VALUES ('17', '会员列表', '16', '1', 'admin', 'member/memberlist', '0', '1', 'fa-list', '1', '1495272875', '0');
INSERT INTO `ob_menu` VALUES ('18', '会员添加', '16', '2', 'admin', 'member/memberadd', '0', '0', 'fa-user-plus', '1', '1520505510', '0');
INSERT INTO `ob_menu` VALUES ('27', '权限管理', '16', '3', 'admin', 'auth/grouplist', '0', '0', 'fa-key', '1', '1520505512', '0');
INSERT INTO `ob_menu` VALUES ('32', '权限组编辑', '27', '0', 'admin', 'auth/groupedit', '1', '0', '', '1', '1492002620', '0');
INSERT INTO `ob_menu` VALUES ('34', '授权', '27', '0', 'admin', 'auth_manager/group', '1', '0', '', '1', '0', '0');
INSERT INTO `ob_menu` VALUES ('35', '菜单授权', '27', '0', 'admin', 'auth/menuauth', '1', '0', '', '1', '1492095653', '0');
INSERT INTO `ob_menu` VALUES ('36', '会员授权', '27', '0', 'admin', 'auth_manager/memberaccess', '1', '0', '', '1', '0', '0');
INSERT INTO `ob_menu` VALUES ('68', '系统管理', '0', '2', 'admin', 'config/group', '0', '0', 'fa-wrench', '1', '1520506753', '0');
INSERT INTO `ob_menu` VALUES ('69', '系统设置', '68', '3', 'admin', 'config/setting', '0', '0', 'fa-cogs', '1', '1520505460', '0');
INSERT INTO `ob_menu` VALUES ('70', '配置管理', '68', '2', 'admin', 'config/index', '0', '0', 'fa-cog', '1', '1520505457', '0');
INSERT INTO `ob_menu` VALUES ('71', '配置编辑', '70', '0', 'admin', 'config/configedit', '1', '0', '', '1', '1491674180', '0');
INSERT INTO `ob_menu` VALUES ('72', '配置删除', '70', '0', 'admin', 'config/configDel', '1', '0', '', '1', '1491674201', '0');
INSERT INTO `ob_menu` VALUES ('73', '配置添加', '70', '0', 'admin', 'config/configadd', '0', '0', 'fa-plus', '1', '1491666947', '0');
INSERT INTO `ob_menu` VALUES ('75', '菜单管理', '68', '1', 'admin', 'menu/index', '0', '0', 'fa-th-large', '1', '1520505453', '0');
INSERT INTO `ob_menu` VALUES ('98', '菜单编辑', '75', '0', 'admin', 'menu/menuedit', '1', '0', '', '1', '1512459021', '0');
INSERT INTO `ob_menu` VALUES ('124', '菜单列表', '75', '0', 'admin', 'menu/menulist', '0', '1', 'fa-list', '1', '1491318271', '0');
INSERT INTO `ob_menu` VALUES ('125', '菜单添加', '75', '0', 'admin', 'menu/menuadd', '0', '0', 'fa-plus', '1', '1491318307', '0');
INSERT INTO `ob_menu` VALUES ('126', '配置列表', '70', '0', 'admin', 'config/configlist', '0', '1', 'fa-list', '1', '1491666890', '1491666890');
INSERT INTO `ob_menu` VALUES ('127', '菜单状态', '75', '0', 'admin', 'menu/setstatus', '1', '0', '', '1', '1520506673', '1491674128');
INSERT INTO `ob_menu` VALUES ('128', '权限组添加', '27', '0', 'admin', 'auth/groupadd', '1', '0', '', '1', '1492002635', '1492002635');
INSERT INTO `ob_menu` VALUES ('134', '授权', '17', '0', 'admin', 'member/memberauth', '1', '0', '', '1', '1492238568', '1492101426');
INSERT INTO `ob_menu` VALUES ('135', '回收站', '68', '4', 'admin', 'trash/trashlist', '0', '0', ' fa-recycle', '1', '1520505468', '1492311462');
INSERT INTO `ob_menu` VALUES ('136', '回收站数据', '135', '0', 'admin', 'trash/trashdatalist', '1', '0', 'fa-database', '1', '1492319477', '1492319392');
INSERT INTO `ob_menu` VALUES ('140', '服务管理', '68', '5', 'admin', 'service/servicelist', '0', '0', 'fa-server', '1', '1520505473', '1492352972');
INSERT INTO `ob_menu` VALUES ('141', '插件管理', '68', '6', 'admin', 'addon/index', '0', '0', 'fa-puzzle-piece', '1', '1520505475', '1492427605');
INSERT INTO `ob_menu` VALUES ('142', '钩子列表', '141', '0', 'admin', 'addon/hooklist', '0', '0', 'fa-anchor', '1', '1492427665', '1492427665');
INSERT INTO `ob_menu` VALUES ('143', '插件列表', '141', '0', 'admin', 'addon/addonlist', '0', '0', 'fa-list', '1', '1492428116', '1492427838');
INSERT INTO `ob_menu` VALUES ('144', '视频管理', '0', '4', 'admin', 'video/index', '0', '0', 'fa-video-camera', '1', '1551939620', '1492480187');
INSERT INTO `ob_menu` VALUES ('145', '视频列表', '144', '0', 'admin', 'video/videolist', '0', '1', 'fa-list', '1', '1551939774', '1492480245');
INSERT INTO `ob_menu` VALUES ('146', '视频分类', '144', '0', 'admin', 'video/videocategorylist', '0', '1', 'fa-list', '1', '1551939787', '1492480342');
INSERT INTO `ob_menu` VALUES ('147', '分类编辑', '146', '0', 'admin', 'video/videocategoryedit', '1', '0', '', '1', '1551939733', '1492485294');
INSERT INTO `ob_menu` VALUES ('148', '分类添加', '144', '0', 'admin', 'video/videocategoryadd', '0', '0', 'fa-plus', '1', '1551939671', '1492486576');
INSERT INTO `ob_menu` VALUES ('149', '视频添加', '144', '0', 'admin', 'video/videoadd', '0', '0', 'fa-plus', '1', '1551939684', '1492518453');
INSERT INTO `ob_menu` VALUES ('150', '视频编辑', '145', '0', 'admin', 'video/videoedit', '1', '0', '', '1', '1551939701', '1492879589');
INSERT INTO `ob_menu` VALUES ('151', '插件安装', '143', '0', 'admin', 'addon/addoninstall', '1', '0', '', '1', '1492879763', '1492879763');
INSERT INTO `ob_menu` VALUES ('152', '插件卸载', '143', '0', 'admin', 'addon/addonuninstall', '1', '0', '', '1', '1492879789', '1492879789');
INSERT INTO `ob_menu` VALUES ('153', '视频删除', '145', '0', 'admin', 'video/videodel', '1', '0', '', '1', '1551939711', '1492879960');
INSERT INTO `ob_menu` VALUES ('154', '分类删除', '146', '0', 'admin', 'video/videocategorydel', '1', '0', '', '1', '1551939744', '1492879995');
INSERT INTO `ob_menu` VALUES ('156', '驱动安装', '140', '0', 'admin', 'service/driverinstall', '1', '0', '', '1', '1502267009', '1502267009');
INSERT INTO `ob_menu` VALUES ('157', '接口管理', '0', '5', 'admin', 'api/index', '0', '0', 'fa fa-book', '1', '1520506753', '1504000434');
INSERT INTO `ob_menu` VALUES ('158', '分组管理', '157', '0', 'admin', 'api/apigrouplist', '0', '0', 'fa fa-fw fa-th-list', '1', '1504000977', '1504000723');
INSERT INTO `ob_menu` VALUES ('159', '分组添加', '157', '0', 'admin', 'api/apigroupadd', '0', '0', 'fa fa-fw fa-plus', '1', '1504004646', '1504004646');
INSERT INTO `ob_menu` VALUES ('160', '分组编辑', '157', '0', 'admin', 'api/apigroupedit', '1', '0', '', '1', '1504004710', '1504004710');
INSERT INTO `ob_menu` VALUES ('161', '分组删除', '157', '0', 'admin', 'api/apigroupdel', '1', '0', '', '1', '1504004732', '1504004732');
INSERT INTO `ob_menu` VALUES ('162', '接口列表', '157', '0', 'admin', 'api/apilist', '0', '0', 'fa fa-fw fa-th-list', '1', '1504172326', '1504172326');
INSERT INTO `ob_menu` VALUES ('163', '接口添加', '157', '0', 'admin', 'api/apiadd', '0', '0', 'fa fa-fw fa-plus', '1', '1504172352', '1504172352');
INSERT INTO `ob_menu` VALUES ('164', '接口编辑', '157', '0', 'admin', 'api/apiedit', '1', '0', '', '1', '1504172414', '1504172414');
INSERT INTO `ob_menu` VALUES ('165', '接口删除', '157', '0', 'admin', 'api/apidel', '1', '0', '', '1', '1504172435', '1504172435');
INSERT INTO `ob_menu` VALUES ('166', '优化维护', '0', '6', 'admin', 'maintain/index', '0', '0', 'fa-legal', '1', '1520506753', '1505387256');
INSERT INTO `ob_menu` VALUES ('168', '数据库', '166', '0', 'admin', 'maintain/database', '0', '0', 'fa-database', '1', '1505539670', '1505539394');
INSERT INTO `ob_menu` VALUES ('169', '数据备份', '168', '0', 'admin', 'database/databackup', '0', '0', 'fa-download', '1', '1506309900', '1505539428');
INSERT INTO `ob_menu` VALUES ('170', '数据还原', '168', '0', 'admin', 'database/datarestore', '0', '0', 'fa-exchange', '1', '1506309911', '1505539492');
INSERT INTO `ob_menu` VALUES ('171', '文件清理', '166', '0', 'admin', 'fileclean/cleanlist', '0', '0', 'fa-file', '1', '1506310152', '1505788517');
INSERT INTO `ob_menu` VALUES ('174', '行为日志', '166', '0', 'admin', 'log/loglist', '0', '1', 'fa-street-view', '1', '1507201516', '1507200836');
INSERT INTO `ob_menu` VALUES ('208', '菜单排序', '75', '0', 'admin', 'menu/setsort', '1', '0', '', '1', '1520506696', '1520506696');
INSERT INTO `ob_menu` VALUES ('209', '会员编辑', '16', '2', 'admin', 'member/memberedit', '1', '0', 'fa-edit', '1', '1520505510', '0');
INSERT INTO `ob_menu` VALUES ('210', '修改密码', '1', '2', 'admin', 'member/editpassword', '1', '0', 'fa-edit', '1', '1520505510', '0');

-- ----------------------------
-- Table structure for `ob_picture`
-- ----------------------------
DROP TABLE IF EXISTS `ob_picture`;
CREATE TABLE `ob_picture` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id自增',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '图片名称',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片链接',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=219 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='图片表';

-- ----------------------------
-- Records of ob_picture
-- ----------------------------
INSERT INTO `ob_picture` VALUES ('194', '2ec45577c5881b9ea40a53406dc7d192.jpg', '20190307/2ec45577c5881b9ea40a53406dc7d192.jpg', '', '30f20b365e96623a9acaf0c162f4253c92ddbca5', '1551940027', '1551940027', '1');
INSERT INTO `ob_picture` VALUES ('195', 'a705ff72a3305c6cd4c66461ffd80df8.png', '20190308/a705ff72a3305c6cd4c66461ffd80df8.png', '', 'ed49044121e5858461a39cc9d3dd1cd325353b81', '1552011278', '1552011278', '1');
INSERT INTO `ob_picture` VALUES ('196', '2ea504846d832e1048312c4314e4a66c.png', '20190308/2ea504846d832e1048312c4314e4a66c.png', '', '83014defc730f700a224c0b9217867c85cedf7e3', '1552011284', '1552011284', '1');
INSERT INTO `ob_picture` VALUES ('197', '53cb96fcf9d4a20a144ca4631336e8aa.png', '20190308/53cb96fcf9d4a20a144ca4631336e8aa.png', '', '084665e5c1c8f875020b2f34ac3f4c549b5c24dc', '1552012659', '1552012659', '1');
INSERT INTO `ob_picture` VALUES ('198', '0b5076ea34925215ba0e2510d838d2b8.png', '20190308/0b5076ea34925215ba0e2510d838d2b8.png', '', '42c2c5215a20740ae60c6ef6697bb881b9b5a8c5', '1552012696', '1552012696', '1');
INSERT INTO `ob_picture` VALUES ('199', 'e692ef6200cc3b8ddb524e3f027b07f5.jpg', '20190308/e692ef6200cc3b8ddb524e3f027b07f5.jpg', '', 'b6f122773b7016ff7fc4907a3b891105dfa2d40e', '1552012712', '1552012712', '1');
INSERT INTO `ob_picture` VALUES ('200', 'f225a784486766c8c079d7807ac679ad.jpg', '20190308/f225a784486766c8c079d7807ac679ad.jpg', '', '41e6074d8ed6ada7cf1326716af869f12978be73', '1552012727', '1552012727', '1');
INSERT INTO `ob_picture` VALUES ('201', '8d66a40a539e94bde2e5888174f00262.jpg', '20190308/8d66a40a539e94bde2e5888174f00262.jpg', '', '3be3f4b483cdac4eded835ed3056952c3648bbd8', '1552012749', '1552012749', '1');
INSERT INTO `ob_picture` VALUES ('202', '8c75d0cc6b771568e08598f658d3dbf9.jpg', '20190308/8c75d0cc6b771568e08598f658d3dbf9.jpg', '', '262698a4b884fd9bc4be3cd99e6113d70892f665', '1552012761', '1552012761', '1');
INSERT INTO `ob_picture` VALUES ('203', '13277a43c7bb3a09e97e9927b4ea48d5.jpg', '20190308/13277a43c7bb3a09e97e9927b4ea48d5.jpg', '', '50fb7543b4f011280e7f44de93039e37538c0276', '1552012780', '1552012780', '1');
INSERT INTO `ob_picture` VALUES ('204', '8fa0c48ea850c616533afefce9246edf.jpg', '20190308/8fa0c48ea850c616533afefce9246edf.jpg', '', '25eecc3479724941347075cd58c5a56a00d78ad9', '1552012797', '1552012797', '1');
INSERT INTO `ob_picture` VALUES ('205', '8265a0f9f4ecd1b45ce443854e827f7e.jpg', '20190308/8265a0f9f4ecd1b45ce443854e827f7e.jpg', '', 'b75412a35ec037f93331a1285054eb29176f666a', '1552012816', '1552012816', '1');
INSERT INTO `ob_picture` VALUES ('206', '00f2c1a3f5e91c7ec93d3c1f65d173a2.jpg', '20190308/00f2c1a3f5e91c7ec93d3c1f65d173a2.jpg', '', '7e94434390f77e9a95fa81f2e08e8465ddfd1c34', '1552012829', '1552012829', '1');
INSERT INTO `ob_picture` VALUES ('207', '7ff35a17637ed28a8f7e75fbffa9b591.jpg', '20190308/7ff35a17637ed28a8f7e75fbffa9b591.jpg', '', '4c9f44ad1efc91faf77f8bd68f161574b79d48f0', '1552012849', '1552012849', '1');
INSERT INTO `ob_picture` VALUES ('208', '958ee6fe70381bca8fcc1ac413f055cf.png', '20190308/958ee6fe70381bca8fcc1ac413f055cf.png', '', '1aeec71a881fc9ca7b99ff2dea2bc3e6e59f528c', '1552032304', '1552032304', '1');
INSERT INTO `ob_picture` VALUES ('209', 'ca23d36786bee67c39f1122c0edf37cf.jpg', '20190308/ca23d36786bee67c39f1122c0edf37cf.jpg', '', 'be99226eebb5ac756945f87621820f7ab1cbf144', '1552032378', '1552032378', '1');
INSERT INTO `ob_picture` VALUES ('210', 'e216be1002bfb03e927903dcc290a976.png', '20190308/e216be1002bfb03e927903dcc290a976.png', '', 'a369a8b2604149aefacc437b6822f4e01d7b93e0', '1552032477', '1552032477', '1');
INSERT INTO `ob_picture` VALUES ('211', '1c95bba33c1bf9a49006b958f64e728a.png', '20190308/1c95bba33c1bf9a49006b958f64e728a.png', '', '69d60c33168d0321e54fca0a375505d746d17672', '1552032561', '1552032561', '1');
INSERT INTO `ob_picture` VALUES ('212', 'f5b92b7bffbd45ca3f0723cfa7d7c4b9.png', '20190308/f5b92b7bffbd45ca3f0723cfa7d7c4b9.png', '', 'fc869199a73ac653b7838593d9cd13f66d6d0b93', '1552032620', '1552032620', '1');
INSERT INTO `ob_picture` VALUES ('213', 'a84ae36b7ce3c31f207f7e45e41f38b5.png', '20190308/a84ae36b7ce3c31f207f7e45e41f38b5.png', '', '245d9b25f0065730b989a7c31114fb648ba53e31', '1552032677', '1552032677', '1');
INSERT INTO `ob_picture` VALUES ('214', 'ca261c538da894a4b470c01fe27645e4.png', '20190308/ca261c538da894a4b470c01fe27645e4.png', '', '9d68e6a0f121a99fa8741515e54fa4fce78c2ccc', '1552032758', '1552032758', '1');
INSERT INTO `ob_picture` VALUES ('215', 'b740282e53eab13905a8c6b16a68d6b9.png', '20190308/b740282e53eab13905a8c6b16a68d6b9.png', '', 'b23066580b76e55d8bc84c56ed1dd28220a0f79a', '1552032824', '1552032824', '1');
INSERT INTO `ob_picture` VALUES ('216', 'b787b9bd6c13d99ae823927603013e97.png', '20190308/b787b9bd6c13d99ae823927603013e97.png', '', '62e1f60047cb240c9b418a7ccae56c4d89e04f50', '1552032885', '1552032885', '1');
INSERT INTO `ob_picture` VALUES ('217', '03582d1e80b3bfd6ee54e58144cf69f3.png', '20190308/03582d1e80b3bfd6ee54e58144cf69f3.png', '', '7497bfe65488b71b36348a0fa2575170c0fb84f0', '1552032950', '1552032950', '1');
INSERT INTO `ob_picture` VALUES ('218', '046ca5de8bae423871f276300b93e875.png', '20190308/046ca5de8bae423871f276300b93e875.png', '', 'cb580b3da6058af56e05a355416372690691ca6b', '1552033005', '1552033005', '1');

-- ----------------------------
-- Table structure for `ob_video`
-- ----------------------------
DROP TABLE IF EXISTS `ob_video`;
CREATE TABLE `ob_video` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `name` char(40) NOT NULL DEFAULT '' COMMENT '视频名称',
  `category_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类',
  `cover_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '封面图片id',
  `file_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件id',
  `play_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '播放量',
  `is_recommend` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `describe` varchar(500) NOT NULL DEFAULT '' COMMENT '简介',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='视频表';

-- ----------------------------
-- Records of ob_video
-- ----------------------------
INSERT INTO `ob_video` VALUES ('41', '北海食神', '7', '199', '2', '10002', '1', '从雕塑老师改行而来的冥币雕版师何弼迎来了他被赶出学校后的最大挑战&amp;amp;mdash;&amp;amp;mdash;接济他的&amp;amp;ldquo;千年面摊&amp;amp;rdquo;老板李面摊希望他代替自己参加世界级的拉面大赛。原本不愿趟这趟浑水的何弼见有利可图，且实在难以推辞，一时侠心四起，出头应允，却在诸位隐士高人的特训和比赛的争斗中，悟出了美食与爱的终极奥义。', '1551941188', '1552019026', '1');
INSERT INTO `ob_video` VALUES ('42', '悲伤逆流成河', '7', '198', '2', '1009', '1', '平凡的易遥因为暗恋人气校草齐铭，惹恼了女校霸唐小米。被唐小米带着人欺辱霸凌，顾森西帮助易遥对抗霸凌，并且鼓励易遥勇敢做自己，经过两人的努力，易遥终于从自卑的丑小鸭变成了闪闪发光的自信女孩。', '1551941254', '1552033548', '1');
INSERT INTO `ob_video` VALUES ('43', '白蛇：缘起', '7', '197', '2', '560', '1', '失忆少女小白被捕蛇人阿宣所救，为了解开自己的身份谜团，小白在阿宣的帮助下开始寻找线索。两人历经艰难险阻，年少男女在一路冒险中萌生爱慕之情。同时，小白的蛇妖身份也逐渐揭开，一场围绕人与妖的纠葛爱情故事随之展开。', '1551941284', '1552033542', '1');
INSERT INTO `ob_video` VALUES ('44', '大人物', '7', '200', '2', '107', '1', '无力维权的修车工遭遇非法强拆后，选择跳楼自杀；随着小刑警孙大圣调查的深入，发现这场看似简单的民事纠纷背后其实另有隐情；随着嫌疑目标的锁定，赵泰和崔京民为代表的反派集团被盯上后，公然藐视法律挑衅警察。面对反派集团金钱诱惑、顶头上司的警告劝阻、家人性命遭受威胁，这场力量悬殊的正邪较量将会如何收场&amp;amp;hellip;&amp;amp;hellip;', '0', '1552019032', '1');
INSERT INTO `ob_video` VALUES ('45', '斗法五湖镇', '7', '201', '2', '1', '1', '民国初年，某地五湖镇，涂山门掌门黄风大仙，因私仇而施法，驱动邪异蝗灾，攻打青鸾门及所在城镇。青鸾门祭起法器抵御遭人破坏，为救阖城百姓，青鸾门当代传人被迫自尽以求重伤黄风，留下一子赵四海。 数年后，以私家侦探身份隐居的赵四海，卷入一起少女失踪案。探查中，赵四海找到曾经青梅竹马、大难中失散的女孩锦云，结识为调查少女连环失踪案而来的警官杜宇。 三人堪破重重迷雾，找出幕后黑手。当赵四海直面凶手的一刻，发现被揭开的不仅仅是无辜少女死亡的真相，还有一段本应被时光尘封的爱恨情仇。 当年那场看似简单的仇杀，背后竟有重重隐情，仇人身份也显得错综复杂，机缘巧合之下重得青鸾门功法奥义的赵四海，以自身为鼎炉，请来天庭正神&amp;amp;ldquo;救下&amp;amp;rdquo;仇人。 终究，一切恩仇都要经过善恶的考验，赵四海得以做出无愧自己本心的选择。', '0', '1552019039', '1');
INSERT INTO `ob_video` VALUES ('46', '海王', '7', '202', '2', '1', '1', '许多年前，亚特兰蒂斯女王和人类相知相恋，共同孕育了爱情的结晶&amp;mdash;&amp;mdash;后来被陆地人称为海王的亚瑟&amp;middot;库瑞。在成长的过程中，亚瑟接受海底导师维科的严苛训练，时刻渴望去看望母亲，然而作为混血的私生子这却是奢望。与此同时，亚瑟的同母异父兄弟奥姆成为亚特兰蒂斯的国王，他不满陆地人类对大海的荼毒与污染，遂谋划联合其他海底王国发动对陆地的全面战争。为了阻止他的野心，维科和奥姆的未婚妻湄拉将亚瑟带到海底世界。 宿命推动着亚瑟，去寻找失落已久的三叉戟，建立一个更加开明的海底王国&amp;hellip;&amp;hellip;', '0', '1552016926', '1');
INSERT INTO `ob_video` VALUES ('47', '锦衣之下之绣春刀', '9', '203', '2', '1', '1', '', '0', '1552012787', '1');
INSERT INTO `ob_video` VALUES ('48', '七剑下天山之修罗眼', '9', '204', '2', '4', '1', '明末清初，修罗魔眼现世，为避免江湖生灵涂炭，天山晦明大师派弟子杨云骢下山寻找魔眼。修罗谷之中，杨云骢与神调门幽冥魔姬遭遇，最终遭遇暗算离去。生命垂危的杨云骢，遇见了杀敌救人的穆郎，念穆郎乃是忠义之士，将女儿易兰珠托孤，并让穆郎上天山，寻找晦明大师。十六年后神调门修罗王屠杀江湖，武庄刘郁芳被追杀之时，被凌未风救下，刘郁芳凌未风就是自己的青梅竹马穆郎。修罗王一路派人追杀，凌未风身重剧毒，无力救下易兰珠。最终刘郁芳和凌未风在树林相认，凌未风赶去救徒弟易兰珠。易兰珠从修罗王的口中得知，当年正是因为自己，才导致父亲杨云骢身亡，再加上凌未风并未救他，而走火入魔，变成满头白发。三人纠缠的命运该何去何从.....', '0', '1552016712', '1');
INSERT INTO `ob_video` VALUES ('49', '逃跑计划', '9', '205', '2', '1', '1', '', '0', '1552012817', '1');
INSERT INTO `ob_video` VALUES ('50', '仙鹤戏狐妖', '9', '206', '2', '2', '0', '', '0', '1552012837', '1');
INSERT INTO `ob_video` VALUES ('51', '这个保安有点彪', '9', '207', '2', '2', '0', '耿直保安彪子为了保护二丫，阴差阳错成为富家千金的贴身保镖，以保安的身份进入大学，凭借自己的机智勇敢，彪子巧妙化种种危机，为保护学生、保卫爱情展开了一场与黑恶势力交锋的斗争。', '0', '1552016737', '1');
INSERT INTO `ob_video` VALUES ('53', '仙鹤戏狐妖', '9', '206', '2', '4', '0', '', '0', '0', '1');

-- ----------------------------
-- Table structure for `ob_video_category`
-- ----------------------------
DROP TABLE IF EXISTS `ob_video_category`;
CREATE TABLE `ob_video_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '分类名称',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据状态',
  `cover_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类图标',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='分类表';

-- ----------------------------
-- Records of ob_video_category
-- ----------------------------
INSERT INTO `ob_video_category` VALUES ('7', '热播', '1509620712', '1552032305', '1', '208');
INSERT INTO `ob_video_category` VALUES ('8', '喜剧', '1509792822', '1552032213', '1', '195');
INSERT INTO `ob_video_category` VALUES ('9', '动作', '1551939923', '1552032379', '1', '209');
INSERT INTO `ob_video_category` VALUES ('10', '爱情', '1551939934', '1552032478', '1', '210');
INSERT INTO `ob_video_category` VALUES ('11', '惊悚', '1551939944', '1552032562', '1', '211');
INSERT INTO `ob_video_category` VALUES ('12', '犯罪', '1551939955', '1552032622', '1', '212');
INSERT INTO `ob_video_category` VALUES ('13', '悬疑', '1551939967', '1552032678', '1', '213');
INSERT INTO `ob_video_category` VALUES ('14', '战争', '1552010789', '1552032760', '1', '214');
INSERT INTO `ob_video_category` VALUES ('15', '科幻', '1552010793', '1552032825', '1', '215');
INSERT INTO `ob_video_category` VALUES ('16', '动画', '1552010799', '1552032886', '1', '216');
INSERT INTO `ob_video_category` VALUES ('17', '武侠', '1552010827', '1552032951', '1', '217');
INSERT INTO `ob_video_category` VALUES ('18', '恐怖', '1552010832', '1552033006', '1', '218');

-- ----------------------------
-- Table structure for `ob_video_play_log`
-- ----------------------------
DROP TABLE IF EXISTS `ob_video_play_log`;
CREATE TABLE `ob_video_play_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `video_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '视频id',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='播放记录表';

-- ----------------------------
-- Records of ob_video_play_log
-- ----------------------------
INSERT INTO `ob_video_play_log` VALUES ('19', '1', '43', '1552034875', '1552034875', '1');
INSERT INTO `ob_video_play_log` VALUES ('22', '1', '44', '1552037662', '1552037662', '1');
INSERT INTO `ob_video_play_log` VALUES ('23', '1', '42', '1552037674', '1552037674', '1');
INSERT INTO `ob_video_play_log` VALUES ('24', '1', '41', '1552034251', '1552034250', '1');
INSERT INTO `ob_video_play_log` VALUES ('25', '1', '48', '1552034866', '1552034866', '1');
INSERT INTO `ob_video_play_log` VALUES ('26', '1', '25', '1552031918', '1552031918', '1');
INSERT INTO `ob_video_play_log` VALUES ('27', '1', '24', '1552031920', '1552031920', '1');
INSERT INTO `ob_video_play_log` VALUES ('28', '1', '23', '1552031110', '1552031110', '1');
INSERT INTO `ob_video_play_log` VALUES ('29', '1', '19', '1552033635', '1552033635', '1');
INSERT INTO `ob_video_play_log` VALUES ('30', '1', '53', '1552035798', '1552035798', '1');
INSERT INTO `ob_video_play_log` VALUES ('31', '1', '30', '1552034326', '1552034326', '1');
INSERT INTO `ob_video_play_log` VALUES ('32', '1', '22', '1552033633', '1552033633', '1');
INSERT INTO `ob_video_play_log` VALUES ('33', '1', '45', '1552034253', '1552034253', '1');
INSERT INTO `ob_video_play_log` VALUES ('34', '1', '46', '1552034255', '1552034255', '1');
INSERT INTO `ob_video_play_log` VALUES ('35', '1', '47', '1552034256', '1552034256', '1');
INSERT INTO `ob_video_play_log` VALUES ('36', '1', '49', '1552034259', '1552034259', '1');
INSERT INTO `ob_video_play_log` VALUES ('37', '1', '50', '1552034834', '1552034834', '1');
INSERT INTO `ob_video_play_log` VALUES ('38', '1', '51', '1552034829', '1552034829', '1');
INSERT INTO `ob_video_play_log` VALUES ('39', '1', '37', '1552034300', '1552034300', '1');
