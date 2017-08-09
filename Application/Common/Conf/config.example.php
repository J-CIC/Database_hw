<?php
return array(
	//'配置项'=>'配置值'
	'DEFAULT_THEME'  => 'default',  // 设置默认的模板主题
	'URL_HTML_SUFFIX' => 'html',
	//视图对应分隔符，即是否要有文件夹
	//'TMPL_FILE_DEPR' => '_',
	'SHOW_PAGE_TRACE' => true,
	'URL_MODEL'	=> '2', //URL模式 
	//数据库
	'DB_TYPE'               =>  'mysqli',     // 数据库类型
	'DB_HOST'               =>  '127.0.0.1', // 服务器地址
	'DB_NAME'               =>  'rbac',          // 数据库名
	'DB_USER'               =>  'root',      // 用户名
	'DB_PWD'                =>  '',          // 密码
	'DB_PORT'               =>  '3306',        // 端口
	'DB_PREFIX'             =>  '',    // 数据库表前缀
	'DB_FIELDS_CACHE'       =>  true,        // 启用字段缓存
	'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
	'DB_BIND_PARAM'         =>  false, // 数据库写入数据自动参数绑定
	'DB_DEBUG'              =>  false,  // 数据库调试模式 3.2.3新增 
	'DB_LITE'               =>  false,  // 数据库Lite模式 3.2.3新增 
);