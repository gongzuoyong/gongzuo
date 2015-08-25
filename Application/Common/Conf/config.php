<?php
return array(
	// 配置数据库
	'DB_TYPE' => 'mysql',
	'DB_HOST'=>'127.0.0.1',
	'DB_NAME'=>'shop2',
	'DB_USER'=>'root',
	'DB_PWD'=>'',
	'DB_PREFIX'=>'sh_',
	// 开启令牌验证 -> 防止表单重复提交和外站提交
	'TOKEN_ON' => FALSE,
	'TOKEN_NAME' => '__hash__',
	'TOKEN_TYPE' => 'md5',
	'TOKEN_RESET' => true,
	// 设置默认的模块
	'DEFAULT_MODULE' => 'Admin',
);