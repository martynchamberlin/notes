<?php

class Core
{
	public $pdo; // handle of the db connection
	public static $admin = array();

	private static $instance;

	private function __construct()
	{
		// building data source name from config
		$dsn = 'mysql:host=' . Config::read('db.host') .
		';dbname='    . Config::read('db.basename') ;
		// getting DB user from config                
		$user = Config::read('db.user');
		// getting DB password from config                
		$password = Config::read('db.password');
		$this->pdo = new PDO($dsn, $user, $password);
	}

	public static function getInstance()
	{
		if (!isset(self::$instance))
		{
			$object = __CLASS__;
			self::$instance = new $object;
		}
		return self::$instance;
	}

	public static function getAdminDetails()
	{
		$core = self::getInstance();
		if (empty(self::$admin))
		{
			$sql = 'SELECT * FROM ug_admin';
			$s = $core->pdo->query($sql);
			self::$admin = $s->fetch();
		}
		return self::$admin;
	}
}

?>
