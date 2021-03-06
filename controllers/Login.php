<?php
namespace App\Controllers;
use App\Entities\User;
use App\Security\Session;

class Login extends Base {
	public function render () {
		if (!empty($_SESSION['login'])){
			Session::logOut();
		}
		if ($_SERVER['REQUEST_METHOD'] === 'POST'
)
		{
			$this->doLogin();
		}
		return [
		'view' => 'login.html',
		'data' => [
			'title' => 'Login',
		]
	];
	}
	
	public function doLogin()
	{
		$formData = [
			'email' => $_POST['email'] ?? '', 
			'password' => $_POST['password'] ?? '',
		];
		$user = new User($formData);
	}
}
