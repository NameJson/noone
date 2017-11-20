<?php

namespace Library\Db;

class Mysql extends \PDO
{

	public function __construct()
	{
		$dsn = 'mysql:host=127.0.0.1;dbname=jubi_com';
		$username = 'root';
		$passwd = '';
		try {
			parent::__construct($dsn, $username, $passwd);
		} catch (\PDOException $e) {
			dd($e->getMessage());
		}
	}
}
