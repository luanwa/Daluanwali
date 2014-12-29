<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
if ($_SERVER['HTTP_HOST'] == "luanwa.sinaapp.com") {
	define('THINK_PATH', './ThinkPHP/');
	define('APP_NAME', 'Index');
	define('APP_PATH', './Application/');
	define('APP_DEBUG', true);
	define('ENGINE_NAME','SAE');
	require(THINK_PATH . "ThinkPHP.php");
} else {
	define('SAE_MYSQL_HOST_M', "localhost");
	define('SAE_MYSQL_DB', "luanwa");
	define('SAE_MYSQL_USER', "root");
	define('SAE_MYSQL_PASS', "");
	define('SAE_MYSQL_PORT', 3306);

	// 检测PHP环境
	if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

	// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
	define('APP_DEBUG',True);

	// 定义应用目录
	define('APP_PATH','./Application/');

	// 引入ThinkPHP入口文件
	require './ThinkPHP/ThinkPHP.php';
}