<?php

/**
 * @Author:  LiuHao
 * @Date:  2017/11/18 下午4:46
 * @Email:  lh@btctrade.com
 * @File:  User.php
 * @Desc:  ...
 */
class User extends Bootstrap
{

	public function __construct()
	{

	}

	/**
	 * Method  index
	 * @desc  用户控制器
	 *
	 * @author  LiuHao <lh@btctrade.com>
	 *
	 * @return  void
	 */
	public function user($name = '', $sex = '')
	{

//		$mysql = new \Library\Db\Mysql();
//		$result = $mysql->query('select * from user');
//		dd($result->fetchAll());

		$data = 'hello word';
		$title = '视图文件';
		$this->assign('name', $name);
		$this->assign('sex', $sex);
		$this->assign('title', $title);
		$this->display('user');
	}
}