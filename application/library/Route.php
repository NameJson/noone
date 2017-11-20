<?php


/**
 * @Author:  LiuHao
 * @Date:  2017/11/18 下午4:48
 * @Email:  lh@btctrade.com
 * @File:  Route.php
 * @Desc:  ...
 */
class Route
{
	# 控制器
	static $ctrl = '';
	# 方法
	static $action = '';

	/**
	 * Route constructor.
	 * @author  liuhao <lh@btctrade.com>
	 * 构造函数
	 */
	public function __construct()
	{

		#
		if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/') {
			$uriArr = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

			# 得到控制器
			if (isset($uriArr[0])) {
				self::$ctrl = $uriArr[0];
				unset($uriArr[0]);
			}

			# 得到方法
			if (isset($uriArr[1])) {
				self::$action = $uriArr[1];
				unset($uriArr[1]);
			} else {
				self::$action = $uriArr[0];
			}


			# URL多余部分转换为 GET参数
			for ($i = 0; $i <= count($uriArr); $i += 2) {
				# 参数成对出现,则赋值
				if (isset($uriArr[$i + 1])) {
					$_GET[$uriArr[$i]] = $uriArr[$i + 1];
				}
			}

		} else {
			self::$ctrl = 'index';
			self::$action = 'index';
		}

	}
}
