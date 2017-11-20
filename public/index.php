<?php
/**
 * @Author:  LiuHao
 * @Date:  2017/11/18 下午3:00
 * @Email:  lh@btctrade.com
 * @File:  index.php
 * @Desc:  框架入口文件
 * 1. 定义常量
 * 2. 加载函数库
 * 3. 启动框架
 */

# 调试模式
define('DEBUG', true);

# 用户IP
foreach (array('HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR') as $v1) if (!empty($_SERVER[$v1])) {
	($tPos = strpos($ip = $_SERVER[$v1], ',')) && $ip = substr($ip, 0, $tPos);
	break;
}
define('IP', $ip);

# 定义路径, 返回规范化的绝对路径
$path = realpath(phpversion() >= 5.3 ? __DIR__ : dirname(__FILE__));
# public目录
define('PUBLIC_PATH', $path);
# 程序目录
define('APPLICATION_PATH', realpath($path. '/../Application'));

# 根据调试模式与否,显示错误信息
if (defined(DEBUG) && DEBUG) {
	ini_set('display_errors', true);
} else {
	ini_set('di splay_errors', false);
}

# 小公举
function dd($val = '')
{
	echo '<hr>';

	if ($val) {
		var_dump($val);
	}else{
		var_dump(null);
	}
}

function dt($val = '')
{
	echo '<hr>';
	if ($val) {
		var_dump($val);
	}else{
		var_dump(null);
	}
	exit('打断进程' . PHP_EOL);
}

# 引入自动加载类
$bootstrapFile = APPLICATION_PATH. '/Bootstrap.php';
include $bootstrapFile;

# 自动加载
spl_autoload_register("Bootstrap::load");

# 开启框架
\Bootstrap::run();