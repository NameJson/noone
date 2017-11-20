<?php

/**
 * Class  Bootstrap
 *
 * @author  liuhao <lh@btctrade.com>
 * 引导文件
 */
class Bootstrap
{
	# 单例,储存已加载的类
	static $classMap = '';

	# 控制器传参
	public $assign = '';

	# module
	static $module = '';
	# 控制器
	static $controller = '';

	# 方法
	static $action = '';


	public static function run()
	{
		# 处理传参
		self::route();

		if (empty(self::$module)) {
			# 组建根控制器路径
			$path = APPLICATION_PATH . '/controllers/' . self::$controller . '.php';

		} else {
			# 组建module中的控制器路径
			$path = APPLICATION_PATH . '/module/' . self::$module . '/controllers/' . self::$controller . '.php';
		}

		# 获取根控制器
		if (file_exists($path)) {

			# 引入控制器文件
			include $path;
			# 实例化控制器
			$controller = new self::$controller();
			# 执行方法
			$action = self::$action;
			$controller->$action();

		} else {
			throw new Exception('控制器不存在' . self::$controller);
		}

	}

	/**
	 * Method  reload
	 * @desc  自动加载文件
	 *
	 * @author  LiuHao <lh@btctrade.com>
	 * @static
	 *
	 * @return  void
	 */
	public static function load($className = '')
	{
		# 类名必须存在
		empty($className) && exit('Need class name');

		# 通过类名得到控制器名称
		$classFile = str_replace('\\', '/', $className);

		# 若存在,不加载
		if (isset(self::$classMap[$className])) {
			return true;
		}

		# 组建根文件文件路径
		$classBaseFile = APPLICATION_PATH . '/' . $classFile . '.php';
		if (file_exists($classBaseFile)) {
			include $classBaseFile;
			self::$classMap[$className] = $className;
		} else {
			# 组建函数库文件路径
			$classLibFile = APPLICATION_PATH . '/Library/' . $classFile . '.php';
			if (file_exists($classLibFile)) {
				include $classLibFile;
				self::$classMap[$className] = $className;
			} else {
				# 皆不存在
				exit('This file no exists');
			}
		}
	}

	/**
	 * Method  assign
	 * @desc  视图传参
	 *
	 * @author  LiuHao <lh@btctrade.com>
	 *
	 * @return  void
	 */
	public function assign($name, $value)
	{
		$this->assign[$name] = $value;

	}

	/**
	 * Method  display
	 * @desc  调用视图
	 *
	 * @author  LiuHao <lh@btctrade.com>
	 *
	 * @return  void
	 */
	public function display($file = '')
	{
		# 获取来源
		$source = debug_backtrace()[0]['file'];
		# 判断是否访问Module
		if ($path = strstr($source, '/module/')) {
			$path = explode('/', trim($path));
			$fileName = explode('.', $path[count($path) - 1]);
			$file = APPLICATION_PATH . '/module/' . $path[2] . '/views/' . $fileName[0] . '/' . $file . '.phtml';
		} else {
			# 无Module
			$path = explode('/', trim($source));
			$path = $path[count($path) - 1];
			$fileName = explode('.', $path);
			$file = APPLICATION_PATH . '/views/' . $fileName[0] . '/' . $file . '.phtml';
		}

		# 判断文件是否存在
		if (file_exists($file)) {
			if (!empty($this->assign)) {
				extract($this->assign);
			}
			include $file;
		} else {
			throw new Exception('This Controller not exists');
		}

	}

	/**
	 * Method  route
	 * @desc  获取访问方式,将路由传参转换为GET传参
	 *
	 * @author  LiuHao <lh@btctrade.com>
	 *
	 * @return  void
	 */
	public
	static function route()
	{
		# 获取访问路径
		if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/') {
			$uriArr = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

			# 得到控制器
			if (isset($uriArr[0])) {
				# 含有自定义module
				if (is_dir(APPLICATION_PATH . '/module/' . $uriArr[0])) {

					# 得到module名
					if (isset($uriArr[0])) {
						self::$module = $uriArr[0];
						unset($uriArr[0]);
					}
					# 得到控制器名
					if (isset($uriArr[1])) {
						self::$controller = $uriArr[1];
						unset($uriArr[1]);
					}
					# 得到方法名
					if (isset($uriArr[2])) {
						self::$action = $uriArr[2];
						unset($uriArr[2]);
					}

					# URL多余部分转换为 GET参数
					for ($i = 3; $i <= count($uriArr); $i += 2) {
						# 参数成对出现,则赋值
						if (isset($uriArr[$i + 1])) {
							$_GET[$uriArr[$i]] = $uriArr[$i + 1];
						}
					}


					dt($_GET);

					# 未自定义module
				} else {
					# 得到控制器名
					if (isset($uriArr[0])) {
						self::$controller = $uriArr[0];
						unset($uriArr[0]);
					}
					# 得到方法名
					if (isset($uriArr[1])) {
						self::$action = $uriArr[1];
						unset($uriArr[1]);
					}

					# URL多余部分转换为 GET参数
					for ($i = 0; $i <= count($uriArr); $i += 2) {
						# 参数成对出现,则赋值
						if (isset($uriArr[$i + 1])) {
							$_GET[$uriArr[$i]] = $uriArr[$i + 1];
						}
					}
				}
			}

		} else {
			self::$controller = 'index';
			self::$action = 'index';
		}
	}

}
