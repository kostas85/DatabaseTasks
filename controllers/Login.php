<?php
namespace App\Controllers;

class Login extends Base {
	public function render () {
		return [
		'view' => 'login.html',
		'data' => [
			'title' => 'Login',
		]
	];
	}
}
