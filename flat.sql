/*
 Navicat Premium Data Transfer

 Source Server         : 本地
 Source Server Type    : MySQL
 Source Server Version : 80012
 Source Host           : localhost:3306
 Source Schema         : flat

 Target Server Type    : MySQL
 Target Server Version : 80012
 File Encoding         : 65001

 Date: 13/11/2019 11:16:39
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for f_log
-- ----------------------------
DROP TABLE IF EXISTS `f_log`;
CREATE TABLE `f_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NULL DEFAULT NULL COMMENT '操作者id',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '标题',
  `href` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '请求链接',
  `object_info` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '对象信息',
  `operation_ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '操作者ip',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '描述信息',
  `create_time` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '操作时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for f_menu
-- ----------------------------
DROP TABLE IF EXISTS `f_menu`;
CREATE TABLE `f_menu`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NULL DEFAULT 0,
  `type` smallint(1) NULL DEFAULT 0 COMMENT '类型（1目录，2菜单，3按钮）',
  `icon` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `sort` int(3) NULL DEFAULT 999,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `href` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态（1正常，0禁用）',
  `spread` tinyint(1) NULL DEFAULT 0 COMMENT '是否默认展开（1是，0否）',
  `target` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `create_time` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `update_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pid`(`pid`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `type`(`type`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 35 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of f_menu
-- ----------------------------
INSERT INTO `f_menu` VALUES (1, 0, 1, 'layui-icon layui-icon-face-smile-fine', 999, '管理中心', '', 1, 0, '_self', '2019-09-19 17:05:50', '2019-10-26 16:30:15');
INSERT INTO `f_menu` VALUES (2, 0, 1, 'layui-icon layui-icon-set', 9999, '系统管理', '', 1, 0, '_self', '2019-09-19 17:14:33', '2019-10-14 17:24:26');
INSERT INTO `f_menu` VALUES (3, 2, 2, 'seraph icon-mokuai', 999, '系统菜单', 'admin/menu/index', 1, 0, '_self', '2019-09-20 09:36:13', '2019-09-29 16:06:49');
INSERT INTO `f_menu` VALUES (4, 2, 2, 'layui-icon layui-icon-username', 999, '系统用户', 'admin/user/index', 1, 0, '_self', '2019-09-24 17:09:12', '2019-09-24 17:09:12');
INSERT INTO `f_menu` VALUES (5, 2, 2, 'layui-icon layui-icon-group', 999, '系统角色', 'admin/role/index', 1, 0, '_self', '2019-09-24 18:04:44', '2019-09-29 16:05:05');
INSERT INTO `f_menu` VALUES (6, 1, 2, 'layui-icon layui-icon-list', 999, '文章列表', 'admin/article/index', 1, 0, '_self', '2019-09-24 18:07:26', '2019-09-24 18:07:26');
INSERT INTO `f_menu` VALUES (7, 6, 3, '', 999, '添加文章', 'admin/article/addition', 1, 0, '_self', '2019-09-24 18:11:40', '2019-09-24 18:11:40');
INSERT INTO `f_menu` VALUES (8, 6, 3, '', 999, '修改文章', 'admin/article/modify', 1, 0, '_self', '2019-09-24 18:21:03', '2019-10-28 16:28:31');
INSERT INTO `f_menu` VALUES (9, 1, 2, 'layui-icon layui-icon-app', 999, '其他管理', '', 1, 1, '_self', '2019-09-29 14:27:56', '2019-10-26 16:30:17');
INSERT INTO `f_menu` VALUES (10, 9, 2, '', 999, '404页面', '', 1, 1, '_self', '2019-09-29 14:30:04', '2019-09-29 14:30:04');
INSERT INTO `f_menu` VALUES (11, 9, 2, 'layui-icon layui-icon-release', 999, '登录页面', '', 1, 0, '_self', '2019-09-29 14:30:56', '2019-10-26 10:40:03');
INSERT INTO `f_menu` VALUES (12, 11, 3, '', 999, '添加', '', 1, 0, '_self', '2019-09-29 14:32:58', '2019-09-29 14:32:58');
INSERT INTO `f_menu` VALUES (13, 11, 3, '', 999, '修改', '', 1, 0, '_self', '2019-09-29 14:33:23', '2019-09-29 14:33:23');
INSERT INTO `f_menu` VALUES (14, 11, 3, '', 999, '删除', '', 1, 0, '_self', '2019-09-29 14:33:35', '2019-10-28 16:28:19');
INSERT INTO `f_menu` VALUES (19, 6, 3, '', 999, '删除文章', 'admin/article/deleteData', 1, 0, '', '2019-09-30 11:50:26', '2019-09-30 11:50:26');
INSERT INTO `f_menu` VALUES (20, 3, 3, '', 999, '删除菜单', 'admin/menu/deleteData', 1, 0, '', '2019-09-30 11:51:11', '2019-10-14 17:23:12');
INSERT INTO `f_menu` VALUES (21, 3, 3, '', 999, '修改菜单', 'admin/menu/form?type=edit', 1, 0, '', '2019-09-30 11:51:35', '2019-10-14 17:22:38');
INSERT INTO `f_menu` VALUES (22, 3, 3, '', 999, '添加菜单', 'admin/menu/form?type=add', 1, 0, '', '2019-09-30 11:52:05', '2019-10-14 18:29:35');
INSERT INTO `f_menu` VALUES (23, 3, 3, '', 999, '菜单列表数据更新', 'admin/menu/listUpdate', 1, 0, '', '2019-10-15 10:07:31', '2019-10-29 17:33:57');
INSERT INTO `f_menu` VALUES (26, 5, 3, NULL, 999, '删除角色', 'admin/role/deletes', 1, 0, NULL, '2019-10-29 17:31:41', '2019-10-29 17:31:41');
INSERT INTO `f_menu` VALUES (27, 5, 3, NULL, 999, '添加角色', 'admin/role/form?type=add', 1, 0, NULL, '2019-10-29 17:32:09', '2019-10-29 17:32:27');
INSERT INTO `f_menu` VALUES (28, 5, 3, NULL, 999, '修改角色', 'admin/role/form?type=edit', 1, 0, NULL, '2019-10-29 17:33:07', '2019-10-29 17:33:07');
INSERT INTO `f_menu` VALUES (29, 5, 3, NULL, 999, '角色列表数据更新', 'admin/role/listUpdate', 1, 0, NULL, '2019-10-29 17:33:44', '2019-10-29 17:33:44');
INSERT INTO `f_menu` VALUES (30, 4, 3, NULL, 999, '删除用户', 'admin/user/deletes', 1, 0, NULL, '2019-11-07 14:10:46', '2019-11-07 14:10:46');
INSERT INTO `f_menu` VALUES (31, 4, 3, NULL, 999, '修改用户', 'admin/user/form?type=edit', 1, 0, NULL, '2019-11-07 14:11:47', '2019-11-07 14:12:17');
INSERT INTO `f_menu` VALUES (32, 4, 3, NULL, 999, '添加用户', 'admin/user/form?type=add', 1, 0, NULL, '2019-11-07 14:13:07', '2019-11-07 14:13:07');
INSERT INTO `f_menu` VALUES (33, 4, 3, NULL, 999, '用户列表数据更新', 'admin/user/listUpdate', 1, 0, NULL, '2019-11-07 14:16:15', '2019-11-07 14:23:04');
INSERT INTO `f_menu` VALUES (34, 4, 3, NULL, 999, '重置密码', 'admin/user/resetPwd', 1, 0, NULL, '2019-11-07 14:21:36', '2019-11-07 15:08:47');

-- ----------------------------
-- Table structure for f_role
-- ----------------------------
DROP TABLE IF EXISTS `f_role`;
CREATE TABLE `f_role`  (
  `id` smallint(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态（0禁用，1正常）',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `permission` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `create_time` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of f_role
-- ----------------------------
INSERT INTO `f_role` VALUES (1, '超级管理员', 1, '拥有至高无上的权利', NULL, '2019-10-28 14:56:18');
INSERT INTO `f_role` VALUES (2, '系统管理员', 1, '拥有的权利仅次于超级管理员', '2,5,29,28,27,26,4,34', '2019-10-28 14:57:10');
INSERT INTO `f_role` VALUES (3, '啊哈哈', 1, '', '2,5,29,28,27,26,4,3,23,22,21,20', '2019-10-29 17:36:46');

-- ----------------------------
-- Table structure for f_user
-- ----------------------------
DROP TABLE IF EXISTS `f_user`;
CREATE TABLE `f_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NULL DEFAULT NULL COMMENT '角色ID',
  `sex` tinyint(1) NULL DEFAULT 0 COMMENT '0保密 1男 2女',
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '姓名',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态（1正常，0禁用）',
  `head_img` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '头像',
  `account` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '登录账号',
  `password` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '密码',
  `email` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '邮箱',
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '手机号码',
  `login_ip` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `login_num` int(11) NULL DEFAULT 0,
  `login_time` datetime(0) NULL DEFAULT NULL,
  `create_time` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP(0),
  `update_time` datetime(0) NULL DEFAULT NULL,
  `delete_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `account`(`account`) USING BTREE,
  INDEX `role_id`(`role_id`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10005 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of f_user
-- ----------------------------
INSERT INTO `f_user` VALUES (10000, 1, 1, '青衫无忧', 1, 'http://t.cn/EadC4gt', 'admin', '7c4a8d09ca3762af61e59520943dc26494f8941b', '905821135@qq.com', '18316074517', '127.0.0.1', 17, '2019-11-12 16:59:14', '2019-11-05 14:43:18', '2019-11-12 17:46:57', NULL);
INSERT INTO `f_user` VALUES (10004, 2, 0, 'testsd', 1, 'http://t.cn/EadC4gt', 'test', '7c4a8d09ca3762af61e59520943dc26494f8941b', '', '', '127.0.0.1', 6, '2019-11-12 17:51:45', '2019-11-05 16:24:10', '2019-11-12 17:51:46', NULL);

SET FOREIGN_KEY_CHECKS = 1;
