/*
 Navicat Premium Data Transfer

 Source Server         : php
 Source Server Type    : MySQL
 Source Server Version : 80011
 Source Host           : localhost:3306
 Source Schema         : rbac

 Target Server Type    : MySQL
 Target Server Version : 80011
 File Encoding         : 65001

 Date: 03/11/2018 16:54:55
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tb_admin
-- ----------------------------
DROP TABLE IF EXISTS `tb_admin`;
CREATE TABLE `tb_admin`  (
  `id` int(32) NOT NULL AUTO_INCREMENT COMMENT '序列',
  `name` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '姓名',
  `username` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '账号',
  `password` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '密码',
  `telphone` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '电话',
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '邮箱',
  `status` int(10) NULL DEFAULT 1 COMMENT '状态(0未激活，1正常，-1删除)',
  `last_login_time` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `create_time` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `update_time` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `ip` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `last_ip` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `code` varchar(4) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0000' COMMENT '安全码',
  `role_id` int(3) NULL DEFAULT NULL COMMENT '角色组',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_admin
-- ----------------------------
INSERT INTO `tb_admin` VALUES (1, '超级管理员', 'admin', '74d839d98630e280df752e8939454a6b', '0', '', 1, '2018-10-24 17:07:31', '2018-10-24 17:07:31', '2018-10-24 17:07:31', '0.0.0.0', NULL, '0000', NULL);
INSERT INTO `tb_admin` VALUES (2, 'gcyufy', '顺德人一个天然', NULL, '123', '色弱图色rt', -1, '2018-10-24 13:25:07', '2018-10-24 13:25:07', '2018-10-24 13:25:07', ' sret', NULL, '0000', NULL);
INSERT INTO `tb_admin` VALUES (3, '平台网址', NULL, NULL, NULL, NULL, -1, '2018-11-02 13:58:07', '2018-11-02 13:58:07', '2018-11-02 13:58:07', NULL, NULL, '0000', NULL);
INSERT INTO `tb_admin` VALUES (4, '', NULL, NULL, NULL, NULL, -1, '2018-10-24 13:25:14', '2018-10-24 13:25:14', '2018-10-24 13:25:14', NULL, NULL, '0000', NULL);
INSERT INTO `tb_admin` VALUES (5, '', NULL, NULL, NULL, NULL, -1, '2018-10-24 13:25:16', '2018-10-24 13:25:16', '2018-10-24 13:25:16', NULL, NULL, '0000', NULL);
INSERT INTO `tb_admin` VALUES (6, '', NULL, NULL, NULL, NULL, -1, '2018-10-24 13:25:18', '2018-10-24 13:25:18', '2018-10-24 13:25:18', NULL, NULL, '0000', NULL);
INSERT INTO `tb_admin` VALUES (7, '风控一', 'danny', 'd187a2cf348b668ffbbf8a4731250ef7', '09123456789', 'danny94581@gmail.com', 1, '2018-10-24 17:32:41', '2018-10-24 17:32:41', '2018-10-24 17:32:41', '0.0.0.0', NULL, '0000', 1);
INSERT INTO `tb_admin` VALUES (8, '财务', 'caiwu', 'f6c1c271636e65e12956739fab5b170b', '', 'danny94581@gmail.com', 1, '2018-10-25 15:40:20', '2018-10-25 15:40:20', '2018-10-25 15:40:20', '', NULL, '0000', 2);

-- ----------------------------
-- Table structure for tb_admin_group
-- ----------------------------
DROP TABLE IF EXISTS `tb_admin_group`;
CREATE TABLE `tb_admin_group`  (
  `admin_id` int(10) NULL DEFAULT NULL,
  `group_id` int(10) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tb_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `tb_admin_role`;
CREATE TABLE `tb_admin_role`  (
  `id` int(10) NOT NULL,
  `admin_id` int(11) NULL DEFAULT NULL,
  `role_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tb_aisle
-- ----------------------------
DROP TABLE IF EXISTS `tb_aisle`;
CREATE TABLE `tb_aisle`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '支付平台',
  `name` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '支付名称',
  `jump_domain` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' COMMENT '跳转域名（带http://)',
  `gateway` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' COMMENT '提交网关',
  `notify_url` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' COMMENT '同步地址',
  `return_url` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' COMMENT '返回地址（浏览器跳转)',
  `account` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' COMMENT '交易帐号',
  `agentnum` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' COMMENT '代理商编号',
  `merchant_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' COMMENT '商户编码',
  `publickey` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' COMMENT '公钥',
  `secretkey` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '密钥',
  `status` int(12) NULL DEFAULT 0 COMMENT '接口状态，1:启用 0:禁用',
  `memo` datetime(6) NULL DEFAULT NULL COMMENT '备注',
  `amount` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' COMMENT '总支付金额(成功)',
  `white_ip_list` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0' COMMENT 'IP白名单',
  `addtime` datetime(6) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(6),
  `ht` int(2) NULL DEFAULT 0,
  `min` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '单笔最低(0为不限制)',
  `max` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '单笔最高(0为不限制)',
  `create_time` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT NULL COMMENT '更新时间',
  `query_url` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '查询网关',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_aisle
-- ----------------------------
INSERT INTO `tb_aisle` VALUES (14, 'sufupay', 'rtyrtd', '0', 'gi', '0', '0', '0', '4845456', '0', '0', '0', -1, '2018-10-16 14:42:26.000000', '0', '0', '2018-10-18 17:34:47.917600', 0, 0.00, 0.00, NULL, NULL, NULL);
INSERT INTO `tb_aisle` VALUES (15, 'sufupay', '速付1', '0', 'http://pay.sufupay.vip/remit.html', '0', '0', '0', '', '1475113', '0', '6ee2581b7ade34cf1d624de89b594938', 0, '2018-10-31 14:42:30.000000', '0', '0', '2018-10-20 15:38:30.247600', 0, 0.00, 49999.00, NULL, NULL, 'http://pay.sufupay.vip/remit_query.html');
INSERT INTO `tb_aisle` VALUES (16, 'sufupay', 'dfghfh', '0', '0', '0', '0', '0', '0', '0', '0', '0', 0, '2018-10-19 14:42:35.000000', '0', '0', '2018-10-18 17:35:03.164600', 0, 0.00, 0.00, NULL, NULL, NULL);
INSERT INTO `tb_aisle` VALUES (17, 'sufupay', 'dgdfg', '0', '0', '0', '0', '0', '0', '0', '0', '0', 1, '2018-10-18 14:42:39.000000', '0', '0', '2018-10-18 17:34:49.891600', 0, 0.00, 0.00, NULL, NULL, NULL);
INSERT INTO `tb_aisle` VALUES (18, 'sufupay', '的一条鱼', '0', '0', '0', '0', '0', '15112', '445656', '0', 'dtrydtryrt', 1, '2018-10-13 14:42:43.000000', '0', '0', '2018-10-18 17:34:51.556600', 0, 0.00, 0.00, NULL, NULL, NULL);
INSERT INTO `tb_aisle` VALUES (19, 'sufupay', '的一条鱼', '0', '0', '0', '0', '0', '15112', '445656', '0', 'dtrydtryrt', 0, '2018-10-18 14:42:51.000000', '0', '0', '2018-10-18 17:34:53.197600', 0, 0.00, 0.00, NULL, NULL, NULL);
INSERT INTO `tb_aisle` VALUES (20, 'sufupay', '的一条鱼', '0', '0', '0', '0', '0', '15112', '445656', '0', 'dtrydtryrt', 0, '2018-10-26 14:42:55.000000', '0', '0', '2018-10-18 17:34:55.070600', 0, 0.00, 0.00, NULL, NULL, NULL);
INSERT INTO `tb_aisle` VALUES (21, 'sufupay', '的一条鱼', '0', '0', '0', '0', '0', '15112', '445656', '0', 'dtrydtryrt', 0, '2018-10-27 14:43:00.000000', '0', '0', '2018-10-18 17:34:56.917600', 0, 0.00, 0.00, NULL, NULL, NULL);
INSERT INTO `tb_aisle` VALUES (22, 'sufupay', '的一条鱼', '0', '0', '0', '0', '0', '15112', '445656', '0', 'dtrydtryrt', 0, '2018-10-19 14:43:18.000000', '0', '0', '2018-10-18 17:34:58.716600', 0, 0.00, 0.00, NULL, NULL, NULL);
INSERT INTO `tb_aisle` VALUES (23, 'sufupay', '的一条鱼', '0', '0', '0', '0', '0', '15112', '445656', '0', 'dtrydtryrt', 0, '2018-10-13 14:43:34.000000', '0', '0', '2018-10-18 17:35:00.951600', 0, 0.00, 0.00, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for tb_aisles
-- ----------------------------
DROP TABLE IF EXISTS `tb_aisles`;
CREATE TABLE `tb_aisles`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '支付平台',
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '支付名称',
  `jump_domain` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '跳转域名（带http://)',
  `gateway` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '提交网关',
  `notify_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '同步地址',
  `return_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '返回地址（浏览器跳转)',
  `account` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '交易帐号（ips新版需要)',
  `agentnum` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '代理商编号',
  `merchant_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商户编码',
  `publickey` varchar(3000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '公钥',
  `secretkey` varchar(2000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '密钥',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '接口状态，1:启用 0:禁用',
  `memo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `amount` decimal(10, 0) NOT NULL DEFAULT 0 COMMENT '总支付金额(成功)',
  `white_ip_list` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'IP白名单',
  `addtime` int(11) NULL DEFAULT 0 COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `id`(`id`) USING BTREE,
  INDEX `type_status`(`type`, `status`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 286 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_aisles
-- ----------------------------
INSERT INTO `tb_aisles` VALUES (284, 'xypay', '新一支付', '', '/api/xypay/pay.php', '/api/xypay/notify.php', '/api/xypay/return.php', '', '087reer', '1035547', '', 'cQdxiyIxhHLdILSFqFnkvMROZBRvfFzu', -1, '', 415671, '', 1537410966);
INSERT INTO `tb_aisles` VALUES (282, 'wkpay', '悟空支付', '', '/api/wkpay/pay.php', '/api/wkpay/notify.php', '/api/wkpay/return.php', '', '', 'xpj85224556056310', '', '3d3b9bc4b74e44bbb2f13bdc85107f34', 0, '', 16067, '', 1535796018);
INSERT INTO `tb_aisles` VALUES (283, 'ffpay', '非凡支付', '', '/api/ffpay/pay.php', '/api/ffpay/notify.php', '/api/ffpay/return.php', '', '', '100058', '', 'baeebd8c546a72fab28e25ac67740e61d7111f66', 0, '', 0, '', 1536658091);
INSERT INTO `tb_aisles` VALUES (281, 'wechatpay', '梅州市润雅凯科技有限公司', '', '/api/wechatpay/pay.php', '/api/wechatpay/notify.php', '/api/wechatpay/return.php', 'wx4d4f445c02b03958', '', '1512160491', 'c8df89f95d48384959f6e2078878fd82', 'danny8661danny8661danny8661danny', 1, '', 1744480, '', 1534249519);
INSERT INTO `tb_aisles` VALUES (280, 'wechatpay', '福清昊鑫建筑工程有限公司', '', '/api/wechatpay/pay.php', '/api/wechatpay/notify.php', '/api/wechatpay/return.php', 'wx787d95f338ac5e4a', '', '1511365201', '138eb700b2cfefdc2ce5d32566ae3bc5', 'danny8661danny8661danny8661danny', 1, '', 1812557, '', 1533352936);
INSERT INTO `tb_aisles` VALUES (275, '500cpwxmp', '1499688782', '', '/api/500cpwxmp/pay.php', '/api/500cpwxmp/notify.php', '/api/500cpwxmp/return.php', '1499688782', '', '1499688782', '', '2IxEvC', 0, '', 0, '', 1528617076);
INSERT INTO `tb_aisles` VALUES (277, 'boshi', 'boshitest', '', '/api/boshi/pay.php', '/api/boshi/notify.php', '/api/boshi/return.php', '', '', '100886', '', '9313ea059516d6a06d328336787fe805', 1, '', 0, '', 1531900735);
INSERT INTO `tb_aisles` VALUES (278, 'yinzhilian', '银直联', '', '/api/yinzhilian/pay.php', '/api/yinzhilian/notify.php', '/api/yinzhilian/return.php', '', '', 'M00000094', '', '0b4eba0a629747e7907409c5242d6f5e', 1, '', 197892, '', 1532345420);
INSERT INTO `tb_aisles` VALUES (279, 'i3juhepay', 'i3聚合支付', '', '/api/i3juhepay/pay.php', '/api/i3juhepay/notify.php', '/api/i3juhepay/return.php', '', '', '10156', '', 'qw0yfxi9dwz2ozhvcw88ggcq06pcup56', 1, '', 7396151, '', 1532504206);
INSERT INTO `tb_aisles` VALUES (285, 'dty', 'dryrty', '', '', '', '', NULL, NULL, 'serts', NULL, 'drty', 0, NULL, 0, NULL, 0);

-- ----------------------------
-- Table structure for tb_build_order
-- ----------------------------
DROP TABLE IF EXISTS `tb_build_order`;
CREATE TABLE `tb_build_order`  (
  `auto_Id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增长Id',
  `Id` int(11) NULL DEFAULT NULL COMMENT '获取Id',
  `MemberAccount` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '会员账号',
  `MemberIsLock` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `MemberLevelId` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '会员等级',
  `MemberLevelName` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '会员等级名称',
  `ApplyTime` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `Amount` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '金额',
  `Fee` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '手续费',
  `DiscountCancel` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '优惠扣除',
  `Administration` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '行政费用',
  `State` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '出款状态（Apply申请中 Success已出款 Cancel已退回）',
  `StateString` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `IsProcessed` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `ProcessTime` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '处理时间',
  `ProcessName` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '处理人员',
  `PortalMemo` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '前台备注',
  `Memo` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '备注',
  `CancelMemberTransactionIds` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `BankName` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '银行名称',
  `Province` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '省',
  `City` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '县市',
  `Account` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '银行账户',
  `BankMemo` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '银行备注',
  `Name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '真实姓名',
  `IsDangerous` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `IsMaybeDangerous` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `CurrentMember` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `DangerousMember` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `Viewers` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `AgentId` int(11) NULL DEFAULT NULL,
  `AgentAccount` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `status` int(2) NULL DEFAULT 0,
  `is_build` int(11) NULL DEFAULT 0 COMMENT '是否创建订单（1.已创建，0.未创建）',
  `MemberMemo` blob NULL COMMENT '会员备注',
  PRIMARY KEY (`auto_Id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2761 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tb_catch_lists
-- ----------------------------
DROP TABLE IF EXISTS `tb_catch_lists`;
CREATE TABLE `tb_catch_lists`  (
  `auto_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `Id` int(11) NOT NULL COMMENT '获取的id',
  `MemberAccount` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '会员账号',
  `MemberLevelId` int(11) NULL DEFAULT NULL COMMENT '会员等级',
  `MemberLevelName` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '会员等级名称',
  `ApplyTime` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '申请时间',
  `Amount` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '申请金额',
  `Fee` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `Administration` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `DiscountCancel` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `State` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `StateString` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `IsProcessed` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `ProcessTime` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `ProcessName` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '完成时间',
  `Memo` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `IsDangerous` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `Viewers` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `is_check` int(11) NULL DEFAULT 0 COMMENT '检查订单状态（1.已检查，0.未检查）',
  `is_build` int(11) NULL DEFAULT 0 COMMENT '是否创建订单（1.已创建，0.未创建）',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`auto_id`, `Id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2931 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tb_config
-- ----------------------------
DROP TABLE IF EXISTS `tb_config`;
CREATE TABLE `tb_config`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '名称',
  `key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '键',
  `value` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '值',
  `memo` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '备注',
  `status` int(255) NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_config
-- ----------------------------
INSERT INTO `tb_config` VALUES (1, '自动扫描', 'is_auto', '1', '(1启动，0禁用)', 1);
INSERT INTO `tb_config` VALUES (2, 'gpk网址', 'scan_url', 'http://hjd.tnywt.com/', NULL, 1);
INSERT INTO `tb_config` VALUES (3, '平台网址', 'platform', '8722.com', '', 1);
INSERT INTO `tb_config` VALUES (4, '测', NULL, NULL, NULL, -1);

-- ----------------------------
-- Table structure for tb_group_role
-- ----------------------------
DROP TABLE IF EXISTS `tb_group_role`;
CREATE TABLE `tb_group_role`  (
  `group_id` int(10) NULL DEFAULT NULL,
  `role_id` int(10) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tb_menu
-- ----------------------------
DROP TABLE IF EXISTS `tb_menu`;
CREATE TABLE `tb_menu`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pid` int(10) NULL DEFAULT 0 COMMENT '父id(0是顶级菜单)',
  `name` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '菜单名称',
  `class` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '菜单图标',
  `icon` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '菜单样式',
  `is_menu` int(1) NULL DEFAULT 1 COMMENT '状态（0，按钮；1，菜单；）',
  `create_time` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `update_time` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `status` int(1) NULL DEFAULT 1 COMMENT '状态（0，关闭；1，正常；-1删除）',
  `list_order` int(2) NULL DEFAULT 0 COMMENT '排序',
  `title` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'title',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'URL',
  `param` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '参数',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 31 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_menu
-- ----------------------------
INSERT INTO `tb_menu` VALUES (1, 0, '系统管理', 'Hui-iconfont', 'Hui-iconfont menu_dropdown-arrow', 1, '2018-09-18 05:49:24', '2018-09-18 05:49:24', 1, 0, NULL, 'menu/index', NULL);
INSERT INTO `tb_menu` VALUES (2, 1, '菜单设置', NULL, NULL, 1, '2018-08-28 06:07:15', '2018-08-28 06:07:15', 1, 0, '菜单设置', 'menu/index', NULL);
INSERT INTO `tb_menu` VALUES (3, 0, '用户管理', 'Hui-iconfont', 'Hui-iconfont menu_dropdown-arrow', 1, '2018-09-14 09:59:55', '2018-09-14 09:59:55', 1, 0, '', 'menu/index', NULL);
INSERT INTO `tb_menu` VALUES (4, 3, '用户列表', NULL, NULL, 1, '2018-09-13 02:27:29', '2018-09-13 02:27:29', 1, 0, '用户列表', 'admin/index', NULL);
INSERT INTO `tb_menu` VALUES (5, 1, '修改密码', NULL, NULL, 1, '2018-11-02 14:05:19', '2018-11-02 14:05:19', 0, 0, '修改密码', 'menu/index', NULL);
INSERT INTO `tb_menu` VALUES (6, 0, '订单管理', 'Hui-iconfont', 'Hui-iconfont menu_dropdown-arrow', 1, '2018-09-14 09:59:53', '2018-09-14 09:59:53', 1, 0, '', 'menu/index', NULL);
INSERT INTO `tb_menu` VALUES (7, 6, '出款成功', 'Hui-iconfont', 'Hui-iconfont menu_dropdown-arrow', 1, '2018-10-10 05:37:24', '2018-10-10 05:37:24', 1, 2, '出款成功', 'order/succ_list', '');
INSERT INTO `tb_menu` VALUES (8, 6, '已回退', 'Hui-iconfont', 'Hui-iconfont menu_dropdown-arrow', 1, '2018-10-03 10:30:44', '2018-10-03 10:30:44', -1, 2, '已回退', 'order/exitindex', NULL);
INSERT INTO `tb_menu` VALUES (9, 6, '出款失败', 'Hui-iconfont', 'Hui-iconfont menu_dropdown-arrow', 1, '2018-10-10 05:37:37', '2018-10-10 05:37:37', 1, 1, '出款失败', 'order/fail_list', '');
INSERT INTO `tb_menu` VALUES (10, 6, '出款申请', 'Hui-iconfont', 'Hui-iconfont menu_dropdown-arrow', 1, '2018-10-10 02:26:03', '2018-10-10 02:26:03', 1, 5, '出款申请', 'order/apply', '');
INSERT INTO `tb_menu` VALUES (11, 0, '水电费水电费是', 'Hui-iconfont', 'Hui-iconfont menu_dropdown-arrow', 1, '2018-10-02 05:29:49', '2018-10-02 05:29:49', -1, 0, '132132', 'menu/index', '');
INSERT INTO `tb_menu` VALUES (12, 11, 'asdasds', 'Hui-iconfont', 'Hui-iconfont menu_dropdown-arrow', 1, '2018-09-17 09:52:31', '2018-09-17 09:52:31', -1, 0, '132132', 'menu/index', NULL);
INSERT INTO `tb_menu` VALUES (13, 6, '退回订单', 'Hui-iconfont', 'Hui-iconfont menu_dropdown-arrow', 1, '2018-10-11 12:24:26', '2018-10-11 12:24:26', 1, 4, '退回订单', 'order/check', '');
INSERT INTO `tb_menu` VALUES (14, 6, '待出款', 'Hui-iconfont', 'Hui-iconfont menu_dropdown-arrow', 1, '2018-10-10 02:28:46', '2018-10-10 02:28:46', 1, 3, '待出款', 'order/outmoney', '');
INSERT INTO `tb_menu` VALUES (19, 0, '菜', '阿萨', '阿萨德', 1, '2018-09-17 09:42:30', '2018-09-17 09:42:30', -1, 0, '阿萨德', '阿萨德', '阿萨德');
INSERT INTO `tb_menu` VALUES (28, 0, '通道管理', 'Hui-iconfont', 'Hui-iconfont menu_dropdown-arrow', 1, '2018-10-04 02:26:26', '2018-10-04 02:26:26', 1, 0, '通道管理', 'menu/index', NULL);
INSERT INTO `tb_menu` VALUES (29, 28, '支付通道', 'Hui-iconfont', 'Hui-iconfont menu_dropdown-arrow', 1, '2018-10-04 02:27:18', '2018-10-04 02:27:18', 1, 0, '通道管理', 'aisle/index', NULL);
INSERT INTO `tb_menu` VALUES (30, 3, '角色管理', '', '', 1, '2018-10-11 06:18:46', '2018-10-11 06:18:46', 1, 0, 'role', 'admin/role', '');
INSERT INTO `tb_menu` VALUES (31, 1, '系统设置', '', '', 1, '2018-11-02 13:41:30', '2018-11-02 13:41:30', 1, 0, '系统设置', 'config/index', '');

-- ----------------------------
-- Table structure for tb_order
-- ----------------------------
DROP TABLE IF EXISTS `tb_order`;
CREATE TABLE `tb_order`  (
  `auto_Id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增长Id',
  `Id` int(255) NULL DEFAULT 0 COMMENT '获取订单Id',
  `payid` int(11) NOT NULL DEFAULT 0 COMMENT '支付通道ID',
  `payname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '支付通道名称',
  `MemberAccount` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '1' COMMENT '会员账号',
  `status` int(11) NULL DEFAULT 0 COMMENT '出款状态（-1退回，0待处理，1风控审核通过，2未定，3财务审核通过）',
  `bankcode` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '银行代码',
  `ip` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'ip',
  `terminal` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'PC' COMMENT '终端',
  `notify_success` int(2) NULL DEFAULT NULL COMMENT '回调成功',
  `notify_count` int(2) NULL DEFAULT NULL COMMENT '回调次数',
  `notify_log` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `operaer` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '操作人',
  `check_time` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '审核时间',
  `outmoney_time` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '出款时间',
  `fail_time` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '回退时间',
  `notify_time` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '回调时间',
  `com_time` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '提交时间',
  `notify_status` varchar(6) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '回调状态',
  `MemberLevelName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '等级名称',
  `MemberLevelId` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '等级id',
  `ApplyTime` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '申请时间',
  `Amount` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '金额',
  `Fee` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '手续费',
  `Administration` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '行政费用',
  `DiscountCancel` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '优惠扣除',
  `State` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '状态',
  `StateString` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '字段',
  `IsProcessed` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `ProcessTime` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `ProcessName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `PortalMemo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `Memo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `CancelMemberTransactionIds` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `BankName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '银行名称',
  `Province` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '省',
  `orderno` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '订单号',
  `City` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '县市',
  `Account` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '银行卡号',
  `BankMemo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `Name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `Mobile` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `IsDangerous` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `IsMaybeDangerous` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `CurrentMember` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `DangerousMember` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `Viewers` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `AgentId` int(11) NULL DEFAULT NULL,
  `AgentAccount` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '上层代理',
  `is_auto` int(1) NOT NULL DEFAULT 1 COMMENT '是否自动创建（1.是，0.否）',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '修改时间',
  `out_money_status` int(1) NULL DEFAULT 0 COMMENT '第三方出款状态（0未处理，1出款成功，2出款失败，3第三方处理中）',
  `out_money_memo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '第三方出款备注',
  `gpk_back` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'gpk返回值',
  `is_auto_out_money` int(255) NULL DEFAULT 1 COMMENT '是否自动出款（1是，0否）',
  `channel` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '支付渠道(ALIPAY|WEIXIN|NETPAY|NETPAY_WAP)',
  `is_query` int(1) NOT NULL DEFAULT 0 COMMENT '是否需要轮询,不需要就是第三方支持回调(0不需要，1要)',
  `trade_no` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商户订单号',
  `order_no` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '第三方平台订单号',
  `MemberMemo` blob NULL COMMENT '会员备注',
  `FinanceReview` datetime(0) NULL DEFAULT NULL COMMENT '财务审核时间',
  `manual_processing` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '人工干涉(-1退回，1出款)',
  `manual_processing_date` datetime(0) NULL DEFAULT NULL COMMENT '人工干涉时间\r\n',
  `is_black` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '是否黑名单（0否，1是）',
  `platform` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '平台',
  `black_memo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '黑名单备注信息',
  PRIMARY KEY (`auto_Id`) USING BTREE,
  INDEX `idx_status_dealwith_status`(`status`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 33068 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tb_role
-- ----------------------------
DROP TABLE IF EXISTS `tb_role`;
CREATE TABLE `tb_role`  (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `pid` int(10) NULL DEFAULT NULL COMMENT '父类ID',
  `name` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '角色名称',
  `description` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '角色描述',
  `create_time` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `update_time` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `status` int(1) NULL DEFAULT 1 COMMENT '状态（0，关闭；1，正常；-1删除）',
  `menu_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '菜单Id(-1开始，以英文逗号‘,’分割)',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_role
-- ----------------------------
INSERT INTO `tb_role` VALUES (1, NULL, '风控', '风控审核申请列表', '2018-10-24 17:48:55', '2018-10-24 17:48:55', 1, '-1,6,10');
INSERT INTO `tb_role` VALUES (2, NULL, '财务', '财务', '2018-10-25 15:33:51', '2018-10-25 15:33:51', 1, '-1,2,6,7,9,13,14');
INSERT INTO `tb_role` VALUES (3, NULL, '财务', '财务', '2018-10-25 15:36:18', '2018-10-25 15:36:18', -1, '-1,订单管理,出款成功,出款失败,退回订单,待出款');
INSERT INTO `tb_role` VALUES (4, NULL, '财务', '财务', '2018-10-25 15:34:57', '2018-10-25 15:34:57', -1, '-1,6,7,9,13,14');

SET FOREIGN_KEY_CHECKS = 1;
