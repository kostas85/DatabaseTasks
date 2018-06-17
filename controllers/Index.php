<?php
namespace App\Controllers;

class Index extends Base {
	public function render () {
		return [
		'view' => 'index.html',
		'data' => [
			'title' => 'Welcome',
		]
	];
	}
}
