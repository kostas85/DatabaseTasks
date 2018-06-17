<?php 

namespace App\Database;

class Database {
	
	private static $instance; 
	
	public static function init()
	{
			if (!isset(self::$instance)) {
				include "./../config/database.php";
				$string = "mysql:dbname=".$dbconfig['dbname'].";host=".$dbconfig['host'];
				$user = $dbconfig['username'];
				$password = $dbconfig['password'];
				try
				{
					self::$instance = new \PDO($string, $user, $password);
				} catch (PDOException $e) {
					echo "Connection failed: " . $e->getMessage();
					die();
				}
			}
		return self::$instance;	
	}
	
	public static function close() {
		self::$instance->close();
	}

}