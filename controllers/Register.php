<?php
namespace App\Controllers;

class Register extends Base {
	public function render () {
		return [
		'view' => 'register.html',
		'data' => [
			'title' => 'Register',
		]
	];
	}
}
