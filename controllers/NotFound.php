<?php
namespace App\Controllers;

class NotFound extends Base {
	public function render () {
		return [
		'view' => '404.html',
		'data' => [
			'title' => 'Not Found',
		]
	];
	}
}
