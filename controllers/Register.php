<?php
namespace App\Controllers;

use App\Security\Session;
use App\Entities\User;

class Register extends Base {
	public function render () {
		if ($_SERVER['REQUEST_METHOD'] === 'POST'
)
		{
			$this->doRegister();
		}
		return [
		'view' => 'register.html',
		'data' => [
			'title' => 'Register',
		]
	];
	}
	
	public function doRegister()
	{
		$formData = [
			'email' => $_POST['email'] ?? '', 
			'name' => $_POST['name'] ?? '',
			'password' => $_POST['password'] ?? '',
			'repeat_password' => $_POST['repeat_password'] ?? ''
		];
		$user = new User($formData);
	}
}
