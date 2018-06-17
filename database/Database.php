<?php 

namespace App\Database;

class Database {
	
	public static function init()
	{
		include "./../config/database.php";
		$string = "mysql:dbname='".$dbconfig['name']."';host=".$dbconfig['host'];
		$user = $dbconfig['username'];
		$password = $dbconfig['password'];
		try
		{
			return new \PDO($string, $user, $password);
		}
		catch (PDOException $e) {
			echo "Connection failed: " . $e->getMessage();
			die();
		}
	}

}