<?php 

namespace App\Entities;
use App\Security\Session;
use App\Database\Database;

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
				
		$result = $a && $b && $c && $d && $e;
		
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
	
	public function doRegistration($formData){
		$isValid = $this->validateRegistration($formData);
		if ($isValid)
		{
			$db = Database::init();
			$statement = $db->prepare("INSERT INTO users(name, email, password, salt`) VALUES($this->name, $this->email, sha1($this->password), md5(rand(10,1000))");
			$statement->execute();
			Session::addErrorMessage('Registration Successful');
			
		}
	}
	
	public function doLogin($formData){
		$isValid = $this->validateLogin($formData);
		if ($isValid)
		{
		
		}
	}
	
}