<?php 

namespace App\Entities;
use App\Security\Session;
use App\Database\Database;
use PDO;

class User {
	
	private $name;
	private $email;
	private $password;
	private $salt;
	
	private const REGISTRATION_FORM = [
		'email',
		'name',
		'password',
		'repeat_password'
	];
	private const LOGIN_FORM = [
		'email',
		'password',
	];
	
	public function __construct($formData)
	{
		if (array_keys($formData) === self::REGISTRATION_FORM)
		{
			$this->name = $formData['name'];
			$this->email = $formData['email'];
			$this->password = $formData['password'];
			$this->salt = md5(rand(10,1000));
			$this->doRegistration($formData);
		}
		
		if (array_keys($formData) === self::LOGIN_FORM)
		{
			$this->doLogin($formData);
		}
	}
	
	public function validateRegistration($formData)
	{
		$a = $this->validateEmail($formData['email']);
		$b = $this->validateName($formData['name']);
		$c = $this->validatePassword($formData['password']);
		$d = $this->validateRepeatPassword($formData['repeat_password']);
		$e = $this->validatePasswordsAreTheSame($formData['password'], $formData['repeat_password']);
		$f = $this->isUserNew($formData['email']);
				
		$result = $a && $b && $c && $d && $e && $f;
		
		return $result;
	}
	
	public function validateLogin($formData)
	{
		$a = $this->validateEmail($formData['email']);
		$b = $this->validatePassword($formData['password']);
				
		$result = $a && $b;
		
		return $result;
	}
	
	private function validateEmail($email)
	{
		$result = true;
		if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			Session::addErrorMessage('Email address is not valid');
			$result = false;
		}
		return $result;
	}
	
	private function validateName($name)
	{
		$result = true;
		if (empty($name))
		{
			Session::addErrorMessage('Name is not valid');
			$result = false;
		}
		return $result;
	}
	
	private function validatePassword($password)
	{
		$result = true;
		if (empty($password))
		{
			Session::addErrorMessage('Password is not valid');
			$result = false;
		}
		return $result;
	}
	
	private function validateRepeatPassword($repeatPassword)
	{
		$result = true;
		if (empty($repeatPassword))
		{
			Session::addErrorMessage('Repeat Password is not valid');
			$result = false;
		}
		return $result;
	}
	
	private function validatePasswordsAreTheSame($password, $repeatPassword)
	{
		$result = true;
		if ($password !== $repeatPassword)
		{
			Session::addErrorMessage('Passwords are not the same');
			$result = false;
		}
		return $result;
	}
	
	private function isUserNew($email)
	{
		$db = Database::init();
		$query = $db->prepare("SELECT count(*) FROM users WHERE email = ?");
		$query->execute([$email]);
		$count = $query->fetchColumn();
		
		if ($count > 0){
			Session::addErrorMessage('User already exists');
		}
		return ($count <= 0);
	}
	
	public function doRegistration($formData){
		$isValid = $this->validateRegistration($formData);
		if ($isValid)
		{
			$shaPass = sha1($this->password.$this->salt);
			$db = Database::init();
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$statement = $db->prepare("INSERT INTO users(name,email,password,salt) VALUES(:name,:email,:password,:salt)");
			$statement->bindParam('name', $this->name);
			$statement->bindParam('email', $this->email);
			$statement->bindParam('password', $shaPass);
			$statement->bindParam('salt', $this->salt);
			$statement->execute();
		

			Session::addSuccessMessage('Registration Successful');
			
		}
	}
	
	
	public function doLogin($formData){
		$isValid = $this->validateLogin($formData);
		if ($isValid)
		{
			$email = $formData['email'];
			$plainPassword = $formData['password'];
			$db = Database::init();
			$query = $db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
			$query->execute([$email]);
			$row = $query->fetch();
			$shaPassword = sha1($plainPassword. $row['salt']);
			if ($row === false) {
				Session::addErrorMessage('Wrong username/password');
				return false;
			}
			if ($shaPassword !== $row['password']){
				Session::addErrorMessage('Wrong username/password');
				return false;
			}
			Session::logIn($email);
			Session::addSuccessMessage('You are logged in!');
			return true;
			
			
		}

	}
	
	public static function searchUsers($q){
			$db = Database::init();
			$query = $db->prepare("SELECT * FROM users WHERE email = ? OR name = ?");
			$query->execute([$q, $q]);
			$rows = $query->fetchAll();
			return $rows;
	}
	
}