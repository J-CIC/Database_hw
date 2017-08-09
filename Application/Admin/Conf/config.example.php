<?php
return array(
	//'配置项'=>'配置值'
	'RBAC_ROLE_TABLE' => 'think_role',
	'RBAC_USER_TABLE' => 'think_role_user',
	'RBAC_ACCESS_TABLE' => 'think_access',
	'RBAC_NODE_TABLE' => 'think_node',
	'USER_AUTH_MODEL' => 'think_user',		//验证用户表模型 think_user表


	'page_num'	=> 20,
	'SPECIAL_USER' => 'CIC',	
	'USER_AUTH_ON' => true,
	'USER_AUTH_TYPE' => 2,			//1登录验证，2实时验证
	'USER_AUTH_KEY' => 'authId',			//用户认证识别号(必配)
	'ADMIN_AUTH_KEY' => 'administrator',		//超级管理员识别号(必配)
	'AUTH_PWD_ENCODER' => 'md5',
	'USER_AUTH_GATEWAY' => '/Admin/Login',	//用户认证失败，跳转URL
	'NOT_AUTH_MODULE' => 'Login,Public,UI',	//无需认证的控制器
	'REQUIRE_AUTH_MODULE' => '',
	'NOT_AUTH_ACTION' => '',			//无需认证的方法
	'REQUIRE_AUTH_ACTION' => '',
	'GUEST_AUTH_ON' => false,			//是否开启游客授权访问
	'GUEST_AUTH_ID' => 0,			//游客标记
);