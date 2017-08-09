-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2017-08-09 16:27:49
-- 服务器版本： 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rbac`
--

-- --------------------------------------------------------

--
-- 表的结构 `think_access`
--

CREATE TABLE `think_access` (
  `role_id` smallint(6) UNSIGNED NOT NULL,
  `node_id` smallint(6) UNSIGNED NOT NULL,
  `level` tinyint(1) NOT NULL,
  `module` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `think_access`
--

INSERT INTO `think_access` (`role_id`, `node_id`, `level`, `module`) VALUES
(1, 29, 3, 'userMessageList'),
(1, 27, 2, 'Message'),
(1, 28, 3, 'messageList'),
(1, 26, 3, 'deleteUser'),
(1, 1, 1, 'Admin'),
(1, 25, 3, 'getRoleAccess'),
(1, 20, 3, 'editAccess'),
(1, 19, 3, 'addRole'),
(1, 18, 3, 'editRole'),
(1, 17, 3, 'index'),
(1, 23, 3, 'deleteRole'),
(1, 24, 3, 'getSingleRole'),
(1, 16, 2, 'Role'),
(1, 14, 3, 'addUser'),
(1, 13, 3, 'index'),
(1, 12, 2, 'User'),
(1, 11, 3, 'getSingleNode'),
(1, 10, 3, 'editNode'),
(1, 8, 3, 'addNode'),
(1, 7, 3, 'index'),
(1, 6, 2, 'Node'),
(1, 4, 3, 'getMenu'),
(1, 3, 3, 'index'),
(1, 5, 3, 'getSubMenu'),
(1, 2, 2, 'Index'),
(1, 30, 3, 'sendMessage'),
(1, 31, 2, 'Menu'),
(1, 32, 3, 'index'),
(1, 33, 3, 'editMenu'),
(1, 34, 2, 'WeChatUser'),
(1, 35, 3, 'index'),
(1, 36, 3, 'editUser'),
(1, 21, 3, 'deleteNode');

-- --------------------------------------------------------

--
-- 表的结构 `think_node`
--

CREATE TABLE `think_node` (
  `id` smallint(6) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `display` tinyint(4) NOT NULL DEFAULT '1',
  `remark` varchar(255) DEFAULT NULL,
  `sort` smallint(6) UNSIGNED DEFAULT NULL,
  `pid` smallint(6) UNSIGNED NOT NULL,
  `level` tinyint(1) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `think_node`
--

INSERT INTO `think_node` (`id`, `name`, `title`, `status`, `display`, `remark`, `sort`, `pid`, `level`) VALUES
(1, 'Admin', 'Rbac后台管理系统', 1, 1, '', 0, 0, 1),
(2, 'Index', '后台总览', 1, 0, '', 0, 1, 2),
(5, 'getSubMenu', '获取子菜单', 1, 0, 'Admin/Index/getSubMenu', 0, 2, 3),
(3, 'index', '显示主页', 1, 0, 'fa-dashboard', 0, 2, 3),
(4, 'getMenu', '获取菜单', 1, 0, 'Admin/Index/getMenu', 0, 2, 3),
(6, 'Node', '节点管理', 1, 1, 'fa-desktop', 0, 1, 2),
(7, 'index', '所有节点', 1, 1, 'fa-list', 0, 6, 3),
(8, 'addNode', '添加节点', 1, 1, 'fa-plus-circle', 0, 6, 3),
(24, 'getSingleRole', '获取单个角色组', 1, 0, '', 0, 16, 3),
(10, 'editNode', '修改节点', 1, 1, 'fa-pencil', 0, 6, 3),
(11, 'getSingleNode', '获取单个节点', 1, 0, 'Admin/Node/getSingleNode', 0, 6, 3),
(12, 'User', '管理员', 1, 1, 'fa-user', 0, 1, 2),
(13, 'index', '所有管理员', 1, 1, 'fa-user', 0, 12, 3),
(14, 'addUser', '添加管理员', 1, 1, 'fa-plus-circle', 0, 12, 3),
(23, 'deleteRole', '删除角色组', 1, 0, '', 0, 16, 3),
(16, 'Role', '权限管理', 1, 1, ' fa-cogs', 0, 1, 2),
(17, 'index', '所有角色组', 1, 1, 'fa-users', 0, 16, 3),
(18, 'editRole', '修改角色组', 1, 1, 'fa-pencil', 0, 16, 3),
(19, 'addRole', '添加角色组', 1, 1, 'fa-plus-circle', 0, 16, 3),
(20, 'editAccess', '编辑角色组权限', 1, 1, 'fa-pencil', 0, 16, 3),
(21, 'deleteNode', '删除节点', 1, 0, '', 0, 6, 3),
(25, 'getRoleAccess', '获取角色组权限', 1, 0, '', 0, 16, 3),
(26, 'deleteUser', '删除管理员', 1, 0, '', 0, 12, 3),
(36, 'editUser', '编辑管理员', 1, 0, '', 0, 12, 3);

-- --------------------------------------------------------

--
-- 表的结构 `think_role`
--

CREATE TABLE `think_role` (
  `id` smallint(6) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) UNSIGNED DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `think_role`
--

INSERT INTO `think_role` (`id`, `name`, `pid`, `status`, `remark`) VALUES
(1, '超级管理员', 0, 1, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `think_role_user`
--

CREATE TABLE `think_role_user` (
  `role_id` mediumint(9) UNSIGNED DEFAULT NULL,
  `user_id` char(32) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `think_role_user`
--

INSERT INTO `think_role_user` (`role_id`, `user_id`) VALUES
(1, '1');

-- --------------------------------------------------------

--
-- 表的结构 `think_user`
--

CREATE TABLE `think_user` (
  `id` int(11) NOT NULL,
  `username` text COLLATE utf8_bin NOT NULL,
  `nickname` text COLLATE utf8_bin NOT NULL,
  `password` text COLLATE utf8_bin NOT NULL,
  `email` text COLLATE utf8_bin NOT NULL,
  `createTime` datetime DEFAULT NULL,
  `loginTime` datetime DEFAULT NULL,
  `loginIp` text COLLATE utf8_bin,
  `status` tinyint(4) NOT NULL COMMENT '0表示禁用,1表示启用',
  `remark` text COLLATE utf8_bin
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- 转存表中的数据 `think_user`
--

INSERT INTO `think_user` (`id`, `username`, `nickname`, `password`, `email`, `createTime`, `loginTime`, `loginIp`, `status`, `remark`) VALUES
(1, 'admin', 'CIC', '602ca564876f6d5b41fc6215886c2ff2', '791863347@qq.com', '2017-01-11 21:00:00', '2017-08-04 16:32:23', '127.0.0.1', 1, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `think_access`
--
ALTER TABLE `think_access`
  ADD KEY `groupId` (`role_id`),
  ADD KEY `nodeId` (`node_id`);

--
-- Indexes for table `think_node`
--
ALTER TABLE `think_node`
  ADD PRIMARY KEY (`id`),
  ADD KEY `level` (`level`),
  ADD KEY `pid` (`pid`),
  ADD KEY `status` (`status`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `think_role`
--
ALTER TABLE `think_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `think_role_user`
--
ALTER TABLE `think_role_user`
  ADD KEY `group_id` (`role_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `think_user`
--
ALTER TABLE `think_user`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `think_node`
--
ALTER TABLE `think_node`
  MODIFY `id` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- 使用表AUTO_INCREMENT `think_role`
--
ALTER TABLE `think_role`
  MODIFY `id` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `think_user`
--
ALTER TABLE `think_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
